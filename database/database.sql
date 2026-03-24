CREATE DATABASE IF NOT EXISTS print_service;
USE print_service;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description TEXT,
    base_price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE product_attributes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    attribute_type ENUM('size', 'material') NOT NULL,
    attribute_value VARCHAR(100) NOT NULL,
    price_modifier DECIMAL(10, 2) DEFAULT 0.00,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT, 
    guest_email VARCHAR(100),
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    size VARCHAR(50),
    material VARCHAR(50),
    design_data JSON, 
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert Sample Data
INSERT INTO users (name, email, password, role) VALUES 
('Admin', 'admin@sastaprint.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); -- password: password

INSERT INTO categories (name, slug, description) VALUES 
('Business Cards', 'business-cards', 'High quality professional business cards.'),
('Banners', 'banners', 'Vinyl and fabric banners.'),
('Yard Signs', 'yard-signs', 'Corrugated plastic yard signs.'),
('Stickers', 'stickers', 'Custom die-cut stickers.');

INSERT INTO products (category_id, name, slug, description, base_price, image_url) VALUES 
(1, 'Standard Business Card', 'standard-business-card', 'Premium standard size business card.', 9.99, 'https://asset.gecdesigns.com/img/visiting-card-templates/sleek-and-modern-business-card-template-for-professionals-1680966557025-cover.webp'),
(2, 'Vinyl Banner', 'vinyl-banner', 'Durable outdoor vinyl banner.', 25.00, 'https://maagsdesigns.com/cdn/shop/files/productimg-maagsdesings-114.png?v=1750541560'),
(3, 'Campaign Yard Sign', 'campaign-yard-sign', 'Perfect for election or real estate.', 15.00, 'https://mir-s3-cdn-cf.behance.net/project_modules/1400/4548de199900439.6659239029572.jpg'),
(4, 'Die Cut Stickers', 'die-cut-stickers', 'Custom shape vinyl stickers.', 5.00, 'https://images.unsplash.com/photo-1572375992501-4b0892d50c69?q=80&w=1920&auto=format&fit=crop');

INSERT INTO product_attributes (product_id, attribute_type, attribute_value, price_modifier) VALUES 
(1, 'material', 'Matte', 0.00),
(1, 'material', 'Glossy', 2.00),
(2, 'size', '2x4 ft', 0.00),
(2, 'size', '3x6 ft', 15.00),
(3, 'size', '18x24 in', 0.00),
(3, 'size', '24x36 in', 10.00);
