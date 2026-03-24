<?php
// ── Middleware: Rate Limiting ──────────────────────────────────────────────────
class RateLimitMiddleware {
    private static string $dir = __DIR__ . '/../../storage/rate_limits/';
    private static int $maxRequests = 60;   // per window
    private static int $window = 60;        // seconds

    /**
     * Call at the start of any API route.
     * Aborts with 429 if too many requests from this IP.
     */
    public static function check(string $endpoint = 'global'): void {
        if (!is_dir(self::$dir)) {
            mkdir(self::$dir, 0755, true);
        }
        $ip   = self::getIp();
        $key  = preg_replace('/[^a-z0-9_]/', '_', $ip . '_' . $endpoint);
        $file = self::$dir . $key . '.json';

        $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        $now  = time();

        // Purge old timestamps
        $data['hits'] = array_filter($data['hits'] ?? [], fn($t) => $t > $now - self::$window);

        if (count($data['hits']) >= self::$maxRequests) {
            http_response_code(429);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Too many requests. Please slow down.', 'retry_after' => self::$window]);
            exit;
        }

        $data['hits'][] = $now;
        file_put_contents($file, json_encode($data));
    }

    private static function getIp(): string {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
