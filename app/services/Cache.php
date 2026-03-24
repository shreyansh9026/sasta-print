<?php
// ── Cache Service ──────────────────────────────────────────────────────────────
class Cache {
    private static string $dir = __DIR__ . '/../../storage/cache/';

    public static function get(string $key): mixed {
        if (!CACHE_ENABLED) return null;
        $file = self::path($key);
        if (!file_exists($file)) return null;
        $data = json_decode(file_get_contents($file), true);
        if (!$data || time() > $data['expires']) {
            @unlink($file);
            return null;
        }
        return $data['value'];
    }

    public static function set(string $key, mixed $value, int $ttl = 0): void {
        if (!CACHE_ENABLED) return;
        if (!is_dir(self::$dir)) mkdir(self::$dir, 0755, true);
        file_put_contents(self::path($key), json_encode([
            'value'   => $value,
            'expires' => time() + ($ttl ?: CACHE_TTL),
        ]));
    }

    public static function forget(string $key): void {
        $file = self::path($key);
        if (file_exists($file)) @unlink($file);
    }

    public static function flush(): void {
        array_map('unlink', glob(self::$dir . '*.cache'));
    }

    private static function path(string $key): string {
        return self::$dir . md5($key) . '.cache';
    }
}
