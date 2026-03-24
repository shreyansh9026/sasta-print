-- ============================================================
-- SastaPrint — Full Database Schema (Advanced Version)
-- Run this on a FRESH database OR use migration_upgrade.sql
-- for an existing installation.
-- ============================================================

CREATE DATABASE IF NOT EXISTS print_service CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE print_service;

-- ── Users ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(100)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    phone      VARCHAR(20)   DEFAULT NULL,
    role       ENUM('customer','admin') DEFAULT 'customer',
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Categories ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS categories (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    slug        VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Products ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS products (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT          NOT NULL,
    name        VARCHAR(150) NOT NULL,
    slug        VARCHAR(150) NOT NULL UNIQUE,
    description TEXT,
    base_price  DECIMAL(10,2) NOT NULL,
    image_url   VARCHAR(255),
    is_active   TINYINT(1)   DEFAULT 1,
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    INDEX idx_slug (slug),
    INDEX idx_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Product Attributes ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS product_attributes (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    product_id      INT          NOT NULL,
    attribute_type  ENUM('size','material','finish') NOT NULL,
    attribute_value VARCHAR(100) NOT NULL,
    price_modifier  DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Coupons ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS coupons (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    code             VARCHAR(50)  NOT NULL UNIQUE,
    type             ENUM('percent','flat') NOT NULL DEFAULT 'percent',
    value            DECIMAL(10,2) NOT NULL,
    min_order_amount DECIMAL(10,2) DEFAULT 0.00,
    usage_limit      INT          DEFAULT 0,  -- 0 = unlimited
    used_count       INT          DEFAULT 0,
    expiry_date      DATE         DEFAULT NULL,
    is_active        TINYINT(1)   DEFAULT 1,
    created_at       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Orders ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS orders (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT          DEFAULT NULL,
    guest_email      VARCHAR(100) DEFAULT NULL,
    total_amount     DECIMAL(10,2) NOT NULL,
    discount_amount  DECIMAL(10,2) DEFAULT 0.00,
    coupon_code      VARCHAR(50)  DEFAULT NULL,
    status           ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    payment_id       VARCHAR(100) DEFAULT NULL,
    payment_status   ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
    shipping_address TEXT,
    created_at       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_payment_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Order Items ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS order_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    order_id    INT         NOT NULL,
    product_id  INT         NOT NULL,
    quantity    INT         NOT NULL,
    size        VARCHAR(50) DEFAULT NULL,
    material    VARCHAR(50) DEFAULT NULL,
    design_data JSON        DEFAULT NULL,
    price       DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    INDEX idx_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Reviews ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS reviews (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT         NOT NULL,
    product_id  INT         NOT NULL,
    rating      TINYINT     NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment     TEXT,
    is_approved TINYINT(1)  DEFAULT 0,
    created_at  TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY uq_user_product (user_id, product_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── ─────────────────────────────────────────────────────────
-- Sample Data
-- ── ─────────────────────────────────────────────────────────

INSERT IGNORE INTO users (name, email, password, role) VALUES
('Admin', 'admin@sastaprint.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- default password: "password"

INSERT IGNORE INTO categories (name, slug, description) VALUES
('Business Cards', 'business-cards', 'High quality professional business cards.'),
('Banners',        'banners',        'Vinyl and fabric banners for outdoor/indoor use.'),
('Yard Signs',     'yard-signs',     'Corrugated plastic yard signs.'),
('Stickers',       'stickers',       'Custom die-cut stickers.');

INSERT IGNORE INTO products (category_id, name, slug, description, base_price, image_url) VALUES
(1, 'Standard Business Card', 'standard-business-card', 'Premium standard size business card.', 9.99,  'https://asset.gecdesigns.com/img/visiting-card-templates/sleek-and-modern-business-card-template-for-professionals-1680966557025-cover.webp'),
(2, 'Vinyl Banner',           'vinyl-banner',           'Durable outdoor vinyl banner.',        25.00, 'https://maagsdesigns.com/cdn/shop/files/productimg-maagsdesings-114.png'),
(3, 'Campaign Yard Sign',     'campaign-yard-sign',     'Perfect for election or real estate.',  15.00, 'https://mir-s3-cdn-cf.behance.net/project_modules/1400/4548de199900439.6659239029572.jpg'),
(4, 'Die Cut Stickers',       'die-cut-stickers',       'Custom shape vinyl stickers.',          5.00,  'https://images.unsplash.com/photo-1572375992501-4b0892d50c69?q=80&w=1920&auto=format&fit=crop');

INSERT IGNORE INTO product_attributes (product_id, attribute_type, attribute_value, price_modifier) VALUES
(1, 'material', 'Matte',    0.00),
(1, 'material', 'Glossy',   2.00),
(2, 'size',     '2x4 ft',   0.00),
(2, 'size',     '3x6 ft',  15.00),
(3, 'size',     '18x24 in', 0.00),
(3, 'size',     '24x36 in',10.00);

INSERT IGNORE INTO coupons (code, type, value, usage_limit, expiry_date, min_order_amount) VALUES
('WELCOME10', 'percent', 10.00, 0, NULL,       0.00),
('FLAT50',    'flat',    50.00, 100, '2026-12-31', 200.00);
