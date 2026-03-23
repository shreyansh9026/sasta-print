<?php
class AdminController extends Controller {
    public function dashboard() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
        $db = Database::getInstance();
        $orders = $db->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10")->fetchAll();
        $this->view('admin/dashboard', ['title' => 'Admin Dashboard', 'orders' => $orders]);
    }
}
