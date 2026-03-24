<?php
class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT id, name, email, role, phone, created_at FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(string $name, string $email, string $password): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function update(int $id, array $fields): bool {
        $allowed = ['name', 'phone'];
        $set = [];
        $values = [];
        foreach ($fields as $k => $v) {
            if (in_array($k, $allowed)) {
                $set[]    = "$k = ?";
                $values[] = $v;
            }
        }
        if (empty($set)) return false;
        $values[] = $id;
        $stmt = $this->db->prepare("UPDATE users SET " . implode(', ', $set) . " WHERE id = ?");
        return $stmt->execute($values);
    }

    public function updatePassword(int $id, string $newPassword): bool {
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $id]);
    }

    public function getOrders(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT o.*, 
                    COUNT(oi.id) as item_count
             FROM orders o
             LEFT JOIN order_items oi ON oi.order_id = o.id
             WHERE o.user_id = ?
             GROUP BY o.id
             ORDER BY o.created_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /** Admin: list all users paginated */
    public function getAll(int $limit = 20, int $offset = 0): array {
        $stmt = $this->db->prepare(
            "SELECT id, name, email, role, phone, created_at FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?"
        );
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function count(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }
}
