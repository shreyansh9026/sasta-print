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
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email
                FROM orders o LEFT JOIN users u ON u.id = o.user_id";
        
        if ($status) {
            $sql .= " WHERE o.status = :status";
        }
        
        $sql .= " ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        if ($status) {
            $stmt->bindValue(':status', $status);
        }
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function count(?string $status = null): int {
        if ($status) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM orders WHERE status = ?");
            $stmt->execute([$status]);
            return (int)$stmt->fetchColumn();
        }
        $res = $this->db->query("SELECT COUNT(*) FROM orders");
        return $res ? (int)$res->fetchColumn() : 0;
    }

    /** Revenue stats for admin dashboard */
    public function getRevenueStats(): array {
        $stats = ['total' => 0, 'month' => 0, 'today' => 0, 'daily' => []];
        
        try {
            // Total revenue
            $res = $this->db->query("SELECT SUM(total_amount) FROM orders WHERE payment_status='paid'");
            $stats['total'] = (float)($res ? $res->fetchColumn() : 0);
            
            // This month
            $res = $this->db->query("SELECT SUM(total_amount) FROM orders WHERE payment_status='paid' AND MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())");
            $stats['month'] = (float)($res ? $res->fetchColumn() : 0);
            
            // Today
            $res = $this->db->query("SELECT SUM(total_amount) FROM orders WHERE payment_status='paid' AND DATE(created_at)=CURDATE()");
            $stats['today'] = (float)($res ? $res->fetchColumn() : 0);
            
            // Daily revenue last 30 days
            $res = $this->db->query("SELECT DATE(created_at) as date, SUM(total_amount) as revenue FROM orders WHERE payment_status='paid' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY DATE(created_at) ORDER BY date");
            if ($res) {
                $stats['daily'] = $res->fetchAll();
            }
        } catch (Exception $e) {
            Logger::error('Revenue stats query failed', ['msg' => $e->getMessage()]);
        }
        
        return $stats;
    }
}
