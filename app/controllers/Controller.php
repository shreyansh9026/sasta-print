<?php
/**
 * Base Controller
 * All controllers extend this class.
 */
class Controller {
    /** Render a view wrapped in layout */
    public function view(string $view, array $data = []): void {
        extract($data);
        require_once '../app/views/layout/header.php';
        require_once '../app/views/' . $view . '.php';
        require_once '../app/views/layout/footer.php';
    }

    /** Send JSON response */
    public function json(mixed $data, int $status = 200): void {
        header('Content-Type: application/json');
        header('X-Content-Type-Options: nosniff');
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /** Redirect to path under BASE_URL */
    public function redirect(string $path): void {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    /** Flash a message into session */
    public function flash(string $type, string $message): void {
        $_SESSION['flash'][$type] = $message;
    }

    /** Get and consume a flash message */
    public function getFlash(string $type): ?string {
        $msg = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $msg;
    }
}
