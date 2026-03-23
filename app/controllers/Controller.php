<?php
class Controller {
    public function view($view, $data = []) {
        extract($data);
        require_once '../app/views/layout/header.php';
        require_once '../app/views/' . $view . '.php';
        require_once '../app/views/layout/footer.php';
    }

    public function json($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit();
    }
    
    public function redirect($path) {
        header("Location: " . BASE_URL . $path);
        exit();
    }
}
