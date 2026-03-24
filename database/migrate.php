<?php
$root = dirname(__DIR__);  // sasta print directory
require $root . '/config/Env.php';
Env::load($root . '/.env');
try {
    $pdo = new PDO(
        'mysql:host=' . Env::get('DB_HOST') . ';port=' . Env::get('DB_PORT', '3306'),
        Env::get('DB_USER'),
        Env::get('DB_PASS'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    // Create database if missing
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . Env::get('DB_NAME') . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . Env::get('DB_NAME') . "`");
    
    $sql   = file_get_contents(__DIR__ . '/migration_upgrade.sql');
    // Split on semicolons respecting line comments
    $stmts = preg_split('/;\s*\n/', $sql);
    $ok = $fail = 0;
    foreach ($stmts as $stmt) {
        $stmt = trim(preg_replace('/--[^\n]*/', '', $stmt));
        if (empty($stmt)) continue;
        try {
            $pdo->exec($stmt);
            $ok++;
        } catch (PDOException $e) {
            // Log but continue — some ALTER IF NOT EXISTS may not be supported on older MySQL
            echo "WARN: " . $e->getMessage() . "\n";
            $fail++;
        }
    }
    echo "Migration complete: $ok statements OK, $fail warnings.\n";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
