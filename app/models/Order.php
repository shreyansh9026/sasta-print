<?php
class Order {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($user_id, $guest_email, $total, $address, $items) {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("INSERT INTO orders (user_id, guest_email, total_amount, shipping_address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $guest_email, $total, $address]);
            $order_id = $this->db->lastInsertId();

            $stmtItem = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, size, material, design_data, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            foreach ($items as $item) {
                $stmtItem->execute([
                    $order_id,
                    $item['product_id'],
                    $item['quantity'],
                    $item['size'] ?? null,
                    $item['material'] ?? null,
                    $item['design_data'] ?? null,
                    $item['price']
                ]);
            }
            $this->db->commit();
            return $order_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
