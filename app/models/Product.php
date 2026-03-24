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

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE products SET category_id = ?, name = ?, slug = ?, description = ?, base_price = ?, image_url = ? WHERE id = ?"
        );
        $result = $stmt->execute([
            $data['category_id'],
            $data['name'],
            $data['slug'],
            $data['description'] ?? '',
            $data['base_price'],
            $data['image_url'] ?? '',
            $id
        ]);
        if ($result) {
            Cache::forget('products_all');
            Cache::forget('product_page_' . $data['slug']);
        }
        return $result;
    }

    public function delete(int $id): bool {
        $this->db->prepare("DELETE FROM product_attributes WHERE product_id = ?")->execute([$id]);
        $result = $this->db->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
        if ($result) Cache::forget('products_all');
        return $result;
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    }

    public function makeSlug(string $name, int $excludeId = 0): string {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9\s-]/', '', $name));
        $slug = preg_replace('/\s+/', '-', trim($slug));
        $base = $slug;
        $i    = 1;
        $stmt = $this->db->prepare("SELECT id FROM products WHERE slug = ? AND id != ?");
        while (true) {
            $stmt->execute([$slug, $excludeId]);
            if (!$stmt->fetch()) break;
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    // ── Categories ────────────────────────────────────────────────────────────
    public function getCategories(): array {
        return $this->db->query("SELECT * FROM categories ORDER BY name")->fetchAll();
    }

    public function createCategory(string $name): bool {
        return $this->db->prepare("INSERT INTO categories (name) VALUES (?)")->execute([$name]);
    }

    public function updateCategory(int $id, string $name): bool {
        return $this->db->prepare("UPDATE categories SET name = ? WHERE id = ?")->execute([$name, $id]);
    }

    public function deleteCategory(int $id): bool {
        // Prevent deleting category if it has products (safer approach)
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
        $stmt->execute([$id]);
        if ((int)$stmt->fetchColumn() > 0) return false;
        
        return $this->db->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
    }

    // ── Attributes (Sizes/Materials) ──────────────────────────────────────────
    public function setAttributes(int $productId, array $attributes): bool {
        // Simple approach: Wipe and recreate for this product
        $this->db->prepare("DELETE FROM product_attributes WHERE product_id = ?")->execute([$productId]);
        
        $stmt = $this->db->prepare(
            "INSERT INTO product_attributes (product_id, attribute_type, attribute_value, price_modifier)
             VALUES (?, ?, ?, ?)"
        );
        foreach ($attributes as $attr) {
            $stmt->execute([
                $productId,
                $attr['type'],
                $attr['value'],
                $attr['price']
            ]);
        }
        return true;
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
