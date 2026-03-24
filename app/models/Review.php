<?php
// ── Product Review Model ───────────────────────────────────────────────────────
class Review {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getForProduct(int $productId): array {
        $stmt = $this->db->prepare(
            "SELECT r.*, u.name as user_name
             FROM reviews r JOIN users u ON u.id = r.user_id
             WHERE r.product_id = ? AND r.is_approved = 1
             ORDER BY r.created_at DESC"
        );
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function getAvgRating(int $productId): float {
        $stmt = $this->db->prepare("SELECT AVG(rating) FROM reviews WHERE product_id = ? AND is_approved = 1");
        $stmt->execute([$productId]);
        return round((float)$stmt->fetchColumn(), 1);
    }

    public function create(int $userId, int $productId, int $rating, string $comment): bool {
        // Prevent duplicate review
        $check = $this->db->prepare("SELECT id FROM reviews WHERE user_id = ? AND product_id = ?");
        $check->execute([$userId, $productId]);
        if ($check->fetch()) return false;

        $stmt = $this->db->prepare(
            "INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$userId, $productId, $rating, $comment]);
    }

    /** Admin: get pending reviews */
    public function getPending(): array {
        $stmt = $this->db->query(
            "SELECT r.*, u.name as user_name, p.name as product_name
             FROM reviews r JOIN users u ON u.id = r.user_id JOIN products p ON p.id = r.product_id
             WHERE r.is_approved = 0 ORDER BY r.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function approve(int $id): bool {
        return $this->db->prepare("UPDATE reviews SET is_approved = 1 WHERE id = ?")->execute([$id]);
    }
}
