<?php
class HomeController extends Controller {
    public function index() {
        $productModel = new Product();
        $products = $productModel->getAll();
        $this->view('home/index', ['title' => 'SastaPrint - Professional Printing Services', 'products' => $products]);
    }
}
