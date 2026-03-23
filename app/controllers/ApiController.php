<?php
class ApiController extends Controller {
    public function getProducts() {
        $productModel = new Product();
        $this->json($productModel->getAll());
    }

    public function calculatePrice() {
        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['product_id'] ?? 0;
        $size = $data['size'] ?? '';
        $material = $data['material'] ?? '';
        $qty = $data['quantity'] ?? 1;

        $productModel = new Product();
        $product = $productModel->getById($productId);
        if (!$product) {
            $this->json(['error' => 'Product not found'], 404);
            return;
        }

        $base = $product['base_price'];
        $modifiers = 0;
        $attrs = $productModel->getAttributes($productId);
        foreach ($attrs as $attr) {
            if (($attr['attribute_type'] == 'size' && $attr['attribute_value'] == $size) ||
                ($attr['attribute_type'] == 'material' && $attr['attribute_value'] == $material)) {
                $modifiers += $attr['price_modifier'];
            }
        }

        $total = ($base + $modifiers) * $qty;
        $this->json(['price' => number_format($total, 2, '.', '')]);
    }

    public function addToCart() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][] = [
            'product_id' => $data['product_id'],
            'name' => $data['name'],
            'size' => $data['size'] ?? '',
            'material' => $data['material'] ?? '',
            'quantity' => $data['quantity'] ?? 1,
            'price' => $data['price'] ?? 0,
            'image' => $data['image'] ?? '',
            'design_data' => $data['design_data'] ?? null
        ];
        $this->json(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
    }

    public function placeOrder() {
        $data = json_decode(file_get_contents('php://input'), true);
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $this->json(['error' => 'Cart is empty'], 400);
            return;
        }

        $total = array_reduce($cart, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $orderModel = new Order();
        $userId = $_SESSION['user_id'] ?? null;
        $guestEmail = $userId ? null : ($data['email'] ?? 'guest@example.com');
        
        $orderId = $orderModel->create($userId, $guestEmail, $total, $data['address'] ?? '', $cart);
        if ($orderId) {
            unset($_SESSION['cart']);
            $this->json(['success' => true, 'order_id' => $orderId]);
        } else {
            $this->json(['error' => 'Order failed'], 500);
        }
    }
}
