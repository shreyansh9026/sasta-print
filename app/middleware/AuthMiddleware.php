<?php
// ── Middleware: Auth guard ─────────────────────────────────────────────────────
class AuthMiddleware {
    /** Redirect to login if not authenticated */
    public static function requireAuth(): void {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    /** Redirect if already logged in */
    public static function redirectIfAuthenticated(): void {
        if (!empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }

    /** Require admin role */
    public static function requireAdmin(): void {
        self::requireAuth();
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            http_response_code(403);
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }

    /** Check whether the current user is an admin */
    public static function isAdmin(): bool {
        return ($_SESSION['user_role'] ?? '') === 'admin';
    }

    /** Current logged-in user id or null */
    public static function userId(): ?int {
        return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    }
}
