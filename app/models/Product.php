<?php
class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id");
        return $stmt->fetchAll();
    }

    public function getBySlug($slug) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAttributes($product_id) {
        $stmt = $this->db->prepare("SELECT * FROM product_attributes WHERE product_id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll();
    }
}
