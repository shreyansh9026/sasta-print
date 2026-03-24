<?php
class ProductController extends Controller {
    
    public function index(): void {
        $products = Cache::get('products_all_view');
        if (!$products) {
            $productModel = new Product();
            $products = $productModel->getAll();
            Cache::set('products_all_view', $products, 3600);
        }
        $this->view('product/index', [
            'title'       => 'Browse Print Products — ' . APP_NAME,
            'description' => 'Explore our full catalog of custom business cards, large format banners, and more.',
            'products'    => $products
        ]);
    }

    public function show(string $slug): void {
        $cacheKey = 'product_page_' . $slug;
        $data = Cache::get($cacheKey);
        
        if (!$data) {
            $productModel = new Product();
            $product = $productModel->getBySlug($slug);
            if (!$product) {
                http_response_code(404);
                $this->view('404', ['title' => 'Product Not Found']);
                return;
            }
            $attributes = $productModel->getAttributes((int)$product['id']);
            $reviews    = (new Review())->getByProduct((int)$product['id']);
            
            $data = [
                'product'    => $product,
                'attributes' => $attributes,
                'reviews'    => $reviews
            ];
            Cache::set($cacheKey, $data, 1800);
        }
        
        $this->view('product/show', [
            'title'      => $data['product']['name'] . ' — Order Custom Prints',
            'product'    => $data['product'],
            'attributes' => $data['attributes'],
            'reviews'    => $data['reviews']
        ]);
    }

    public function design(string $id): void {
        $productModel = new Product();
        $product = $productModel->getById((int)$id);
        if (!$product) {
            http_response_code(404);
            $this->view('404');
            return;
        }
        $this->view('product/design', [
            'title'   => 'Custom Design: ' . $product['name'],
            'product' => $product
        ]);
    }
}
