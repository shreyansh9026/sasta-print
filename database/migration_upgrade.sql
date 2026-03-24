-- ============================================================
-- SastaPrint — UPGRADE MIGRATION
-- Run this if you already have the old database installed.
-- Safe to run multiple times (uses ALTER TABLE IF NOT EXISTS)
-- ============================================================

USE print_service;

-- Add new columns to users
ALTER TABLE users 
    ADD COLUMN IF NOT EXISTS phone VARCHAR(20) DEFAULT NULL AFTER email;

-- Add payment columns to orders
ALTER TABLE orders
    ADD COLUMN IF NOT EXISTS discount_amount  DECIMAL(10,2) DEFAULT 0.00 AFTER total_amount,
    ADD COLUMN IF NOT EXISTS coupon_code      VARCHAR(50)   DEFAULT NULL AFTER discount_amount,
    ADD COLUMN IF NOT EXISTS payment_id       VARCHAR(100)  DEFAULT NULL AFTER coupon_code,
    ADD COLUMN IF NOT EXISTS payment_status   ENUM('pending','paid','failed','refunded') DEFAULT 'pending' AFTER payment_id;

-- Add is_active to products
ALTER TABLE products
    ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1 AFTER image_url;

-- Add finish to product_attributes
ALTER TABLE product_attributes
    MODIFY COLUMN attribute_type ENUM('size','material','finish') NOT NULL;

-- Create coupons table
CREATE TABLE IF NOT EXISTS coupons (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    code             VARCHAR(50)   NOT NULL UNIQUE,
    type             ENUM('percent','flat') NOT NULL DEFAULT 'percent',
    value            DECIMAL(10,2) NOT NULL,
    min_order_amount DECIMAL(10,2) DEFAULT 0.00,
    usage_limit      INT           DEFAULT 0,
    used_count       INT           DEFAULT 0,
    expiry_date      DATE          DEFAULT NULL,
    is_active        TINYINT(1)    DEFAULT 1,
    created_at       TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create reviews table
CREATE TABLE IF NOT EXISTS reviews (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT        NOT NULL,
    product_id  INT        NOT NULL,
    rating      TINYINT    NOT NULL,
    comment     TEXT,
    is_approved TINYINT(1) DEFAULT 0,
    created_at  TIMESTAMP  DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY uq_user_product (user_id, product_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add indexes for performance
ALTER TABLE orders  ADD INDEX IF NOT EXISTS idx_status (status);
ALTER TABLE orders  ADD INDEX IF NOT EXISTS idx_payment_status (payment_status);
ALTER TABLE products ADD INDEX IF NOT EXISTS idx_category (category_id);

-- Sample coupons
INSERT IGNORE INTO coupons (code, type, value, usage_limit, expiry_date, min_order_amount) VALUES
('WELCOME10', 'percent', 10.00, 0, NULL, 0.00),
('FLAT50',    'flat',    50.00, 100, '2026-12-31', 200.00);

-- Ensure Admin Account Exists
-- default password: "password"
INSERT IGNORE INTO users (name, email, password, role) VALUES
('Admin', 'admin@sastaprint.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
