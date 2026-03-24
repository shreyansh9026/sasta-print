<?php
class Order {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(int|null $userId, ?string $guestEmail, float $total, string $address, array $items, ?string $couponCode = null, float $discount = 0): int|false {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                "INSERT INTO orders (user_id, guest_email, total_amount, discount_amount, coupon_code, shipping_address)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$userId, $guestEmail, $total, $discount, $couponCode, $address]);
            $orderId = $this->db->lastInsertId();

            $stmtItem = $this->db->prepare(
                "INSERT INTO order_items (order_id, product_id, quantity, size, material, design_data, price)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            foreach ($items as $item) {
                $designData = isset($item['design_data']) && $item['design_data'] !== null
                    ? (is_array($item['design_data']) ? json_encode($item['design_data']) : $item['design_data'])
                    : null;

                $stmtItem->execute([
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['size']     ?? null,
                    $item['material'] ?? null,
                    $designData,
                    $item['price']
                ]);
            }

            $this->db->commit();
            return (int)$orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            Logger::error('Order creation failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByIdForUser(int $id, int $userId): array|false {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    public function getItems(int $orderId): array {
        $stmt = $this->db->prepare(
            "SELECT oi.*, p.name as product_name, p.image_url
             FROM order_items oi
             JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = ?"
        );
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool {
        $allowed = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($status, $allowed)) return false;
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function updatePayment(int $id, string $paymentId, string $paymentStatus): bool {
        $stmt = $this->db->prepare(
            "UPDATE orders SET payment_id = ?, payment_status = ? WHERE id = ?"
        );
        return $stmt->execute([$paymentId, $paymentStatus, $id]);
    }

    /** Admin: get all orders paginated */
    public function getAll(int $limit = 20, int $offset = 0, ?string $status = null): array {
        if ($status) {
            $stmt = $this->db->prepare(
                "SELECT o.*, u.name as user_name, u.email as user_email
                 FROM orders o LEFT JOIN users u ON u.id = o.user_id
                 WHERE o.status = ?
                 ORDER BY o.created_at DESC LIMIT ? OFFSET ?"
            );
            $stmt->execute([$status, $limit, $offset]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT o.*, u.name as user_name, u.email as user_email
                 FROM orders o LEFT JOIN users u ON u.id = o.user_id
                 ORDER BY o.created_at DESC LIMIT ? OFFSET ?"
            );
            $stmt->execute([$limit, $offset]);
        }
        return $stmt->fetchAll();
    }

    public function count(?string $status = null): int {
        if ($status) {
            return (int)$this->db->prepare("SELECT COUNT(*) FROM orders WHERE status = ?")
                                 ->execute([$status]) ? $this->db->query("SELECT FOUND_ROWS()")->fetchColumn() : 0;
        }
        return (int)$this->db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    }

    /** Revenue stats for admin dashboard */
    public function getRevenueStats(): array {
        $stats = [];
        // Total revenue
        $stats['total'] = (float)$this->db->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE payment_status='paid'")->fetchColumn();
        // This month
        $stats['month'] = (float)$this->db->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE payment_status='paid' AND MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())")->fetchColumn();
        // Today
        $stats['today'] = (float)$this->db->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE payment_status='paid' AND DATE(created_at)=CURDATE()")->fetchColumn();
        // Daily revenue last 30 days
        $stmt = $this->db->query("SELECT DATE(created_at) as date, SUM(total_amount) as revenue FROM orders WHERE payment_status='paid' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY DATE(created_at) ORDER BY date");
        $stats['daily'] = $stmt->fetchAll();
        return $stats;
    }
}
