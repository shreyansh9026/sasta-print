<?php
class HomeController extends Controller {
    public function index(): void {
        // Use cache for homepage products (Phase 3 Performance)
        $products = Cache::get('home_products');
        if (!$products) {
            $productModel = new Product();
            $products = $productModel->getAll();
            Cache::set('home_products', $products, 600);
        }
        $this->view('home/index', [
            'title'       => APP_NAME . ' — Professional Online Printing Services',
            'description' => 'Order custom business cards, banners, stickers and yard signs online. Fast delivery across India. Use our design tool to customize your prints.',
            'products'    => $products,
        ]);
    }
}
