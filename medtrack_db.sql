-- MedTrack Database Schema
-- Improved version with proper constraints and security

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+05:30";

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `medtrack_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `medtrack_db`;

-- --------------------------------------------------------
-- Table structure for admin accounts
-- --------------------------------------------------------

CREATE TABLE `admin_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('super_admin','admin') DEFAULT 'admin',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for shopkeeper accounts
-- --------------------------------------------------------

CREATE TABLE `shopkeeper_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_name` varchar(100) NOT NULL,
  `shopkeeper_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `shop_image` varchar(255) DEFAULT NULL,
  `shop_details` text,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `shop_name` (`shop_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for product categories
-- --------------------------------------------------------

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for products
-- --------------------------------------------------------

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `shop_id` int(11) NOT NULL,
  `image_01` varchar(255) DEFAULT NULL,
  `image_02` varchar(255) DEFAULT NULL,
  `image_03` varchar(255) DEFAULT NULL,
  `is_prescription_required` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `shop_id` (`shop_id`),
  CONSTRAINT `products_category_fk` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_shop_fk` FOREIGN KEY (`shop_id`) REFERENCES `shopkeeper_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for orders
-- --------------------------------------------------------

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(15) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `order_status` enum('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
  `razorpay_order_id` varchar(100) DEFAULT NULL,
  `razorpay_payment_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `orders_product_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for product views tracking
-- --------------------------------------------------------

CREATE TABLE `product_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `view_count` int(11) DEFAULT 0,
  `last_viewed` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`),
  CONSTRAINT `product_views_product_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Insert sample data
-- --------------------------------------------------------

-- Insert admin account (password: admin123)
INSERT INTO `admin_accounts` (`username`, `email`, `password_hash`, `full_name`, `role`) VALUES
('admin', 'admin@medtrack.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'super_admin');

-- Insert product categories
INSERT INTO `product_categories` (`name`, `description`) VALUES
('Antibiotics', 'Medications that fight bacterial infections'),
('Pain Relief', 'Medications for pain management'),
('Cardiovascular', 'Medications for heart and blood vessel conditions'),
('Diabetes', 'Medications for diabetes management'),
('Respiratory', 'Medications for breathing conditions'),
('Mental Health', 'Medications for mental health conditions'),
('Rare Diseases', 'Medications for rare and orphan diseases');

-- Insert sample shopkeeper
INSERT INTO `shopkeeper_accounts` (`shop_name`, `shopkeeper_name`, `email`, `password_hash`, `phone`, `address`, `city`, `state`, `pincode`) VALUES
('MedTrack Pharmacy', 'John Doe', 'john@medtrack.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', '123 Main Street', 'Mumbai', 'Maharashtra', '400001');

-- Insert sample product
INSERT INTO `products` (`name`, `category_id`, `price`, `description`, `stock_quantity`, `shop_id`) VALUES
('Paracetamol 500mg', 2, 5.00, 'Effective pain relief medication for headaches and fever', 100, 1);

-- Set up indexes for better performance
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_shop ON products(shop_id);
CREATE INDEX idx_orders_status ON orders(payment_status, order_status);
CREATE INDEX idx_shopkeeper_location ON shopkeeper_accounts(city, state);

COMMIT;
