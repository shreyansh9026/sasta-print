<?php
class Product {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $cached = Cache::get('products_all');
        if ($cached) return $cached;
        $stmt = $this->db->query(
            "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC"
        );
        $data = $stmt->fetchAll();
        Cache::set('products_all', $data, 300);
        return $data;
    }

    public function getBySlug(string $slug): array|false {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.slug = ?"
        );
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByCategory(int $categoryId): array {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = ?");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function getAttributes(int $productId): array {
        $stmt = $this->db->prepare("SELECT * FROM product_attributes WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO products (category_id, name, slug, description, base_price, image_url) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $result = $stmt->execute([
            $data['category_id'],
            $data['name'],
            $data['slug'],
            $data['description'] ?? '',
            $data['base_price'],
            $data['image_url'] ?? '',
        ]);
        if ($result) Cache::forget('products_all');
        return $result;
    }

    public function delete(int $id): bool {
        $result = $this->db->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
        if ($result) Cache::forget('products_all');
        return $result;
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    }

    public function makeSlug(string $name): string {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9\s-]/', '', $name));
        $slug = preg_replace('/\s+/', '-', trim($slug));
        // Ensure uniqueness
        $base = $slug;
        $i    = 1;
        while ($this->db->prepare("SELECT id FROM products WHERE slug = ?")->execute([$slug]) &&
               $this->db->prepare("SELECT id FROM products WHERE slug = ?")->fetch()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function getCategories(): array {
        return $this->db->query("SELECT * FROM categories ORDER BY name")->fetchAll();
    }

    public function search(string $query): array {
        $q = '%' . $query . '%';
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id
             WHERE p.name LIKE ? OR p.description LIKE ?"
        );
        $stmt->execute([$q, $q]);
        return $stmt->fetchAll();
    }
}
