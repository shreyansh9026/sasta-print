<?php
// ── Coupon Model ───────────────────────────────────────────────────────────────
class Coupon {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByCode(string $code): array|false {
        $stmt = $this->db->prepare(
            "SELECT * FROM coupons WHERE code = ? AND is_active = 1 AND (expiry_date IS NULL OR expiry_date >= CURDATE()) LIMIT 1"
        );
        $stmt->execute([strtoupper($code)]);
        return $stmt->fetch();
    }

    /** Calculate discount amount based on coupon type */
    public function calculateDiscount(array $coupon, float $subtotal): float {
        if ($coupon['type'] === 'percent') {
            return round($subtotal * ($coupon['value'] / 100), 2);
        }
        return min((float)$coupon['value'], $subtotal); // flat discount capped at subtotal
    }

    /** Increment usage counter */
    public function incrementUsage(int $id): void {
        $this->db->prepare("UPDATE coupons SET used_count = used_count + 1 WHERE id = ?")
                 ->execute([$id]);
    }

    public function isValid(array $coupon): bool {
        if (!$coupon['is_active']) return false;
        if ($coupon['usage_limit'] > 0 && $coupon['used_count'] >= $coupon['usage_limit']) return false;
        if ($coupon['expiry_date'] && strtotime($coupon['expiry_date']) < time()) return false;
        return true;
    }

    /** Admin CRUD */
    public function getAll(): array {
        return $this->db->query("SELECT * FROM coupons ORDER BY created_at DESC")->fetchAll();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO coupons (code, type, value, usage_limit, expiry_date, min_order_amount)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            strtoupper($data['code']),
            $data['type'],
            $data['value'],
            $data['usage_limit'] ?? 0,
            $data['expiry_date'] ?? null,
            $data['min_order_amount'] ?? 0,
        ]);
    }

    public function delete(int $id): bool {
        return $this->db->prepare("DELETE FROM coupons WHERE id = ?")->execute([$id]);
    }
}
