<?php
class CartController extends Controller {
    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        $this->view('cart/index', ['title' => 'Your Cart', 'cart' => $cart]);
    }

    public function checkout() {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $this->redirect('/cart');
        }
        $this->view('cart/checkout', ['title' => 'Checkout', 'cart' => $cart]);
    }
}
