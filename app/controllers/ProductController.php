<?php
class ProductController extends Controller {
    public function index() {
        $productModel = new Product();
        $products = $productModel->getAll();
        $this->view('product/index', ['title' => 'All Products', 'products' => $products]);
    }

    public function show($slug) {
        $productModel = new Product();
        $product = $productModel->getBySlug($slug);
        if (!$product) {
            http_response_code(404);
            $this->view('404');
            return;
        }
        $attributes = $productModel->getAttributes($product['id']);
        $this->view('product/show', ['title' => $product['name'], 'product' => $product, 'attributes' => $attributes]);
    }

    public function design($id) {
        $productModel = new Product();
        $product = $productModel->getById($id);
        if (!$product) {
            http_response_code(404);
            $this->view('404');
            return;
        }
        $this->view('product/design', ['title' => 'Design ' . $product['name'], 'product' => $product]);
    }
}
