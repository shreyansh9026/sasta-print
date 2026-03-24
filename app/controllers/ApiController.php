<?php
// ── API v1 Controller ──────────────────────────────────────────────────────────
class ApiController extends Controller {

    // ── Products ──────────────────────────────────────────────────────────────
    public function getProducts(): void {
        RateLimitMiddleware::check('products');
        $cached = Cache::get('api_products');
        if ($cached) { $this->json($cached); return; }
        $productModel = new Product();
        $data = $productModel->getAll();
        Cache::set('api_products', $data, 300);
        $this->json($data);
    }

    // ── Pricing Calculator ────────────────────────────────────────────────────
    public function calculatePrice(): void {
        RateLimitMiddleware::check('pricing');
        $data      = json_decode(file_get_contents('php://input'), true) ?? [];
        $productId = (int)($data['product_id'] ?? 0);
        $size      = htmlspecialchars($data['size'] ?? '');
        $material  = htmlspecialchars($data['material'] ?? '');
        $qty       = max(1, (int)($data['quantity'] ?? 1));

        $productModel = new Product();
        $product = $productModel->getById($productId);
        if (!$product) { $this->json(['error' => 'Product not found'], 404); return; }

        $base = (float)$product['base_price'];
        $modifiers = 0.0;
        foreach ($productModel->getAttributes($productId) as $attr) {
            if (($attr['attribute_type'] === 'size'     && $attr['attribute_value'] === $size) ||
                ($attr['attribute_type'] === 'material' && $attr['attribute_value'] === $material)) {
                $modifiers += (float)$attr['price_modifier'];
            }
        }
        $unitPrice = $base + $modifiers;
        $total     = $unitPrice * $qty;
        $this->json(['unit_price' => number_format($unitPrice, 2), 'total' => number_format($total, 2)]);
    }

    // ── Coupon Validation ─────────────────────────────────────────────────────
    public function validateCoupon(): void {
        RateLimitMiddleware::check('coupon');
        $data    = json_decode(file_get_contents('php://input'), true) ?? [];
        $code    = strtoupper(trim($data['code'] ?? ''));
        $amount  = (float)($data['amount'] ?? 0);

        if (!$code) { $this->json(['error' => 'Coupon code required'], 400); return; }

        $couponModel = new Coupon();
        $coupon = $couponModel->findByCode($code);

        if (!$coupon || !$couponModel->isValid($coupon)) {
            $this->json(['valid' => false, 'error' => 'Invalid or expired coupon code.']);
            return;
        }
        if ($amount < (float)$coupon['min_order_amount']) {
            $this->json(['valid' => false, 'error' => 'Minimum order ₹' . number_format($coupon['min_order_amount'], 2) . ' required.']);
            return;
        }
        $discount = $couponModel->calculateDiscount($coupon, $amount);
        $this->json(['valid' => true, 'discount' => $discount, 'type' => $coupon['type'], 'value' => $coupon['value']]);
    }

    // ── Cart ──────────────────────────────────────────────────────────────────
    public function addToCart(): void {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        $_SESSION['cart'][] = [
            'product_id'  => (int)($data['product_id']  ?? 0),
            'name'        => htmlspecialchars($data['name']     ?? ''),
            'size'        => htmlspecialchars($data['size']     ?? ''),
            'material'    => htmlspecialchars($data['material'] ?? ''),
            'quantity'    => max(1, (int)($data['quantity']     ?? 1)),
            'price'       => (float)($data['price']             ?? 0),
            'image'       => htmlspecialchars($data['image']    ?? ''),
            'design_data' => $data['design_data']               ?? null,
        ];
        $this->json(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
    }

    public function removeFromCart(): void {
        $data  = json_decode(file_get_contents('php://input'), true) ?? [];
        $index = (int)($data['index'] ?? -1);
        if (isset($_SESSION['cart'][$index])) {
            array_splice($_SESSION['cart'], $index, 1);
        }
        $this->json(['success' => true, 'cart_count' => count($_SESSION['cart'] ?? [])]);
    }

    // ── Place Order ───────────────────────────────────────────────────────────
    public function placeOrder(): void {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) { $this->json(['error' => 'Cart is empty'], 400); return; }

        $subtotal = array_reduce($cart, fn($c, $i) => $c + ($i['price'] * $i['quantity']), 0.0);
        $discount = 0.0;
        $couponCode = null;

        // Apply coupon if provided
        if (!empty($data['coupon_code'])) {
            $couponModel = new Coupon();
            $coupon = $couponModel->findByCode($data['coupon_code']);
            if ($coupon && $couponModel->isValid($coupon)) {
                $discount   = $couponModel->calculateDiscount($coupon, $subtotal);
                $couponCode = $coupon['code'];
                $couponModel->incrementUsage($coupon['id']);
            }
        }

        $total   = max(0, $subtotal - $discount);
        $address = htmlspecialchars($data['address'] ?? '');

        $orderModel  = new Order();
        $userId      = $_SESSION['user_id'] ?? null;
        $guestEmail  = $userId ? null : htmlspecialchars($data['email'] ?? 'guest@example.com');

        $orderId = $orderModel->create($userId, $guestEmail, $total, $address, $cart, $couponCode, $discount);
        if (!$orderId) { $this->json(['error' => 'Order failed. Please try again.'], 500); return; }

        unset($_SESSION['cart']);
        Logger::info('Order placed', ['order_id' => $orderId, 'user_id' => $userId, 'total' => $total]);

        // Create Razorpay payment order
        try {
            $razorpayOrder = PaymentService::createOrder($total, 'ORD-' . $orderId);
            $this->json([
                'success'          => true,
                'order_id'         => $orderId,
                'razorpay_order'   => $razorpayOrder,
                'razorpay_key'     => RAZORPAY_KEY_ID ?: 'demo',
            ]);
        } catch (PaymentException $e) {
            // Order saved, but payment init failed — still return order id
            $this->json(['success' => true, 'order_id' => $orderId, 'payment_error' => $e->getMessage()]);
        }
    }

    // ── Payment Verification ──────────────────────────────────────────────────
    public function verifyPayment(): void {
        $data      = json_decode(file_get_contents('php://input'), true) ?? [];
        $orderId   = (int)($data['order_id'] ?? 0);
        $rpOrderId = $data['razorpay_order_id'] ?? '';
        $paymentId = $data['razorpay_payment_id'] ?? '';
        $signature = $data['razorpay_signature'] ?? '';

        if (!$orderId || !$paymentId) { $this->json(['error' => 'Missing parameters'], 400); return; }

        if (!PaymentService::verifySignature($rpOrderId, $paymentId, $signature)) {
            Logger::security('Payment signature mismatch', ['order_id' => $orderId]);
            $this->json(['error' => 'Payment verification failed.'], 400);
            return;
        }

        $orderModel = new Order();
        $order = $orderModel->getById($orderId);
        if (!$order) { $this->json(['error' => 'Order not found'], 404); return; }

        $orderModel->updatePayment($orderId, $paymentId, 'paid');
        $orderModel->updateStatus($orderId, 'processing');

        // Send confirmation email
        $items = $orderModel->getItems($orderId);
        $order = $orderModel->getById($orderId); // refresh
        MailService::sendOrderConfirmation($order, $items);

        // Generate invoice
        InvoiceService::generate($order, $items);

        Logger::info('Payment verified', ['order_id' => $orderId, 'payment_id' => $paymentId]);
        $this->json(['success' => true, 'message' => 'Payment confirmed! Order is being processed.']);
    }

    // ── Reviews ───────────────────────────────────────────────────────────────
    public function addReview(): void {
        if (!isset($_SESSION['user_id'])) { $this->json(['error' => 'Login required'], 401); return; }

        $data   = json_decode(file_get_contents('php://input'), true) ?? [];
        $rating  = max(1, min(5, (int)($data['rating'] ?? 0)));
        $comment = htmlspecialchars(trim($data['comment'] ?? ''));
        $prodId  = (int)($data['product_id'] ?? 0);

        if (!$prodId || !$rating) { $this->json(['error' => 'Invalid review data'], 400); return; }

        $reviewModel = new Review();
        if ($reviewModel->create((int)$_SESSION['user_id'], $prodId, $rating, $comment)) {
            $this->json(['success' => true, 'message' => 'Review submitted for approval.']);
        } else {
            $this->json(['error' => 'You have already reviewed this product.'], 409);
        }
    }
}
