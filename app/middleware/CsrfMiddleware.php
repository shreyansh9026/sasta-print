<?php
// ── Middleware: CSRF Protection ────────────────────────────────────────────────
class CsrfMiddleware {
    /** Generate and store a CSRF token in session */
    public static function generateToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /** Verify token from POST; die on mismatch */
    public static function verify(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        $token = $_POST['_csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            die(json_encode(['error' => 'CSRF token mismatch. Please refresh and try again.']));
        }
    }

    /** Render hidden input field */
    public static function field(): string {
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars(self::generateToken()) . '">';
    }
}
