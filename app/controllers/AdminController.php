<?php
// ── Admin Controller ───────────────────────────────────────────────────────────
class AdminController extends Controller {

    public function dashboard(): void {
        AuthMiddleware::requireAdmin();
        $db = Database::getInstance();

        $orderModel   = new Order();
        $userModel    = new User();
        $productModel = new Product();

        $stats = [
            'total_orders'    => $orderModel->count(),
            'total_users'     => $userModel->count(),
            'total_products'  => $productModel->count(),
            'revenue'         => $orderModel->getRevenueStats(),
            'pending_orders'  => $orderModel->count('pending'),
        ];

        $recentOrders = $orderModel->getAll(10, 0);
        $pendingReviews = (new Review())->getPending();

        $this->view('admin/dashboard', [
            'title'          => 'Admin Dashboard — ' . APP_NAME,
            'stats'          => $stats,
            'recent_orders'  => $recentOrders,
            'pending_reviews'=> $pendingReviews,
            'success'        => $this->getFlash('success'),
            'error'          => $this->getFlash('error'),
        ]);
    }

    // ── Orders ────────────────────────────────────────────────────────────────
    public function orders(): void {
        AuthMiddleware::requireAdmin();
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $status = $_GET['status'] ?? null;
        $limit  = 20;
        $offset = ($page - 1) * $limit;

        $orderModel = new Order();
        $orders     = $orderModel->getAll($limit, $offset, $status);
        $total      = $orderModel->count($status);

        $this->view('admin/orders', [
            'title'       => 'Manage Orders — ' . APP_NAME,
            'orders'      => $orders,
            'total'       => $total,
            'page'        => $page,
            'pages'       => ceil($total / $limit),
            'status'      => $status,
        ]);
    }

    public function orderDetail(string $id): void {
        AuthMiddleware::requireAdmin();
        $orderModel = new Order();
        $order = $orderModel->getById((int)$id);
        if (!$order) { $this->redirect('/admin/orders'); return; }
        $items = $orderModel->getItems((int)$id);
        $this->view('admin/order_detail', [
            'title' => 'Order #' . $id . ' — Admin',
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function updateOrderStatus(): void {
        AuthMiddleware::requireAdmin();
        $data   = json_decode(file_get_contents('php://input'), true) ?? [];
        $id     = (int)($data['order_id'] ?? 0);
        $status = $data['status'] ?? '';

        $orderModel = new Order();
        $order = $orderModel->getById($id);
        if (!$order) { $this->json(['error' => 'Order not found'], 404); return; }

        if ($orderModel->updateStatus($id, $status)) {
            MailService::sendStatusUpdate($order, $status);
            Logger::info('Order status updated', ['order_id' => $id, 'status' => $status]);
            $this->json(['success' => true]);
        } else {
            $this->json(['error' => 'Update failed'], 500);
        }
    }

    // ── Products ──────────────────────────────────────────────────────────────
    public function products(): void {
        AuthMiddleware::requireAdmin();
        $productModel = new Product();
        $this->view('admin/products', [
            'title'    => 'Manage Products — ' . APP_NAME,
            'products' => $productModel->getAll(),
            'success'  => $this->getFlash('success'),
        ]);
    }

    public function addProduct(): void {
        AuthMiddleware::requireAdmin();
        $v = new Validator($_POST);
        $v->required('name')->required('base_price')->numeric('base_price')
          ->required('category_id')->numeric('category_id');

        if ($v->fails()) {
            $this->flash('error', implode(' ', $v->errors()));
            $this->redirect('/admin/products');
            return;
        }

        $productModel = new Product();
        $productModel->create([
            'name'        => $v->get('name'),
            'slug'        => $productModel->makeSlug($v->get('name')),
            'category_id' => (int)$v->get('category_id'),
            'description' => $v->get('description', ''),
            'base_price'  => (float)$v->get('base_price'),
            'image_url'   => $v->get('image_url', ''),
        ]);
        Cache::forget('api_products');
        $this->flash('success', 'Product added successfully.');
        $this->redirect('/admin/products');
    }

    public function deleteProduct(string $id): void {
        AuthMiddleware::requireAdmin();
        $productModel = new Product();
        if ($productModel->delete((int)$id)) {
            $this->json(['success' => true]);
        }
        $this->json(['error' => 'Delete failed'], 500);
    }

    public function editProduct(string $id): void {
        AuthMiddleware::requireAdmin();
        $productModel = new Product();
        $product = $productModel->getById((int)$id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $v = new Validator($_POST);
            $v->required('name')->required('category_id')->numeric('base_price');
            
            if ($v->passes()) {
                $data = $v->all();
                $data['slug'] = $productModel->makeSlug($data['name'], (int)$id);
                $productModel->update((int)$id, $data);
                
                // Handle Attributes
                $attrs = [];
                if (!empty($_POST['attr_type'])) {
                    foreach ($_POST['attr_type'] as $idx => $type) {
                        if (!empty($_POST['attr_value'][$idx])) {
                            $attrs[] = [
                                'type'  => $type,
                                'value' => $_POST['attr_value'][$idx],
                                'price' => (float)($_POST['attr_price'][$idx] ?? 0)
                            ];
                        }
                    }
                }
                $productModel->setAttributes((int)$id, $attrs);
                
                $this->flash('success', 'Product updated successfully.');
                $this->redirect('/admin/products');
            }
        }
        
        $this->view('admin/product_edit', [
            'title'      => 'Edit Product: ' . ($product['name'] ?? 'Unknown'),
            'product'    => $product,
            'categories' => $productModel->getCategories(),
            'attributes' => $productModel->getAttributes((int)$id),
            'error'      => $this->getFlash('error')
        ]);
    }

    // ── Categories ────────────────────────────────────────────────────────────
    public function categories(): void {
        AuthMiddleware::requireAdmin();
        $productModel = new Product();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add'])) {
                $productModel->createCategory($_POST['name']);
            } elseif (isset($_POST['edit'])) {
                $productModel->updateCategory((int)$_POST['id'], $_POST['name']);
            } elseif (isset($_POST['delete'])) {
                $productModel->deleteCategory((int)$_POST['id']);
            }
            $this->redirect('/admin/categories');
        }
        
        $this->view('admin/categories', [
            'title'      => 'Manage Categories',
            'categories' => $productModel->getCategories()
        ]);
    }

    // ── Coupons ───────────────────────────────────────────────────────────────
    public function coupons(): void {
        AuthMiddleware::requireAdmin();
        $couponModel = new Coupon();
        $this->view('admin/coupons', [
            'title'   => 'Manage Coupons — ' . APP_NAME,
            'coupons' => $couponModel->getAll(),
            'success' => $this->getFlash('success'),
        ]);
    }

    public function addCoupon(): void {
        AuthMiddleware::requireAdmin();
        $v = new Validator($_POST);
        $v->required('code')->required('type')->required('value')->numeric('value');
        if ($v->fails()) {
            $this->flash('error', implode(' ', $v->errors()));
            $this->redirect('/admin/coupons');
            return;
        }
        (new Coupon())->create($_POST);
        $this->flash('success', 'Coupon created.');
        $this->redirect('/admin/coupons');
    }

    public function deleteCoupon(string $id): void {
        AuthMiddleware::requireAdmin();
        (new Coupon())->delete((int)$id);
        $this->json(['success' => true]);
    }

    // ── Reviews ───────────────────────────────────────────────────────────────
    public function approveReview(string $id): void {
        AuthMiddleware::requireAdmin();
        (new Review())->approve((int)$id);
        $this->json(['success' => true]);
    }

    // ── Users ─────────────────────────────────────────────────────────────────
    public function users(): void {
        AuthMiddleware::requireAdmin();
        $userModel = new User();
        $this->view('admin/users', [
            'title' => 'Manage Users — ' . APP_NAME,
            'users' => $userModel->getAll(),
        ]);
    }

    // ── Analytics API ─────────────────────────────────────────────────────────
    public function analyticsData(): void {
        AuthMiddleware::requireAdmin();
        $orderModel = new Order();
        $this->json([
            'revenue' => $orderModel->getRevenueStats(),
            'orders_by_status' => Database::getInstance()->query(
                "SELECT status, COUNT(*) as count FROM orders GROUP BY status"
            )->fetchAll(),
            'top_products' => Database::getInstance()->query(
                "SELECT p.name, SUM(oi.quantity) as total_sold
                 FROM order_items oi JOIN products p ON p.id = oi.product_id
                 GROUP BY oi.product_id ORDER BY total_sold DESC LIMIT 5"
            )->fetchAll(),
        ]);
    }

    // ── Invoice Download ──────────────────────────────────────────────────────
    public function downloadInvoice(string $id): void {
        AuthMiddleware::requireAdmin();
        $orderModel = new Order();
        $order = $orderModel->getById((int)$id);
        if (!$order) { $this->redirect('/admin/orders'); return; }
        $items = $orderModel->getItems((int)$id);
        $path  = InvoiceService::generate($order, $items);
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="invoice-' . $id . '.pdf"');
        readfile($path);
        exit;
    }
}
