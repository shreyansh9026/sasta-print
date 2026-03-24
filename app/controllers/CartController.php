<?php
class CartController extends Controller {
    public function index(): void {
        $cart = $_SESSION['cart'] ?? [];
        $this->view('cart/index', ['title' => 'Your Cart — ' . APP_NAME, 'cart' => $cart]);
    }

    public function checkout(): void {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $this->redirect('/cart');
        }
        $this->view('cart/checkout', ['title' => 'Checkout — ' . APP_NAME, 'cart' => $cart]);
    }

    public function success(string $id): void {
        $orderModel = new Order();
        $order = $orderModel->getById((int)$id);
        $this->view('cart/success', [
            'title' => 'Order Confirmed! — ' . APP_NAME,
            'order' => $order ?: ['id' => $id],
        ]);
    }

    public function track(string $id): void {
        $orderModel = new Order();
        $order = $orderModel->getById((int)$id);
        if (!$order) {
            $this->redirect('/');
            return;
        }
        $items = $orderModel->getItems((int)$id);
        $this->view('user/order_detail', [
            'title' => 'Track Order #' . $id,
            'order' => $order,
            'items' => $items,
        ]);
    }
}
