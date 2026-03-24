<?php
// ── Logger ─────────────────────────────────────────────────────────────────────
class Logger {
    private static string $logDir = __DIR__ . '/../../storage/logs/';

    public static function info(string $message, array $context = []): void {
        self::write('INFO', $message, $context);
    }
    public static function warning(string $message, array $context = []): void {
        self::write('WARNING', $message, $context);
    }
    public static function error(string $message, array $context = []): void {
        self::write('ERROR', $message, $context);
    }
    public static function security(string $message, array $context = []): void {
        self::write('SECURITY', $message, $context, 'security.log');
    }

    private static function write(string $level, string $message, array $context, string $file = 'app.log'): void {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }
        $contextStr = empty($context) ? '' : ' ' . json_encode($context);
        $line = sprintf(
            "[%s] [%s] %s%s\n",
            date('Y-m-d H:i:s'),
            $level,
            $message,
            $contextStr
        );
        file_put_contents(self::$logDir . $file, $line, FILE_APPEND | LOCK_EX);
        if ($level === 'ERROR') {
            error_log($message . $contextStr);
        }
    }
}
