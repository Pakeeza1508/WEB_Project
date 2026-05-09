-- Database schema for LuxeStore / shopping_db
-- Import this into MySQL or phpMyAdmin before using the app.

-- ============================================================
-- DATABASE & SETUP
-- ============================================================

CREATE DATABASE IF NOT EXISTS shopping_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE shopping_db;

SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS users (
  userid INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (userid),
  UNIQUE KEY uq_users_username (username),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS products (
  pid INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  category VARCHAR(100) NOT NULL,
  tag VARCHAR(50) NOT NULL DEFAULT 'new',
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  image VARCHAR(255) NOT NULL,
  PRIMARY KEY (pid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS categories (
  cid INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL UNIQUE,
  slug VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (cid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- STEP 1: SHOP PRODUCTS WITH INVENTORY MANAGEMENT
-- Description: Products available for sale with stock tracking
-- Features:
--   - stock: Current inventory count (auto-decrements on purchase)
--   - discount: Percentage discount (0-100)
--   - description: Product details for customers
--   - rating: Average customer rating (0-5)
--   - categories: Auto-syncs new category names into the categories table
-- ============================================================
CREATE TABLE IF NOT EXISTS shop_products (
  spid INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  category VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  stock INT(11) NOT NULL DEFAULT 0,
  discount DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  description TEXT DEFAULT NULL,
  image VARCHAR(255) NOT NULL,
  rating DECIMAL(2,1) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (spid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS cart (
  cart_id INT(11) NOT NULL AUTO_INCREMENT,
  userid INT(11) NOT NULL,
  spid INT(11) NOT NULL,
  qty INT(11) NOT NULL DEFAULT 1,
  item_type VARCHAR(20) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (cart_id),
  KEY idx_cart_userid (userid),
  KEY idx_cart_spid (spid),
  CONSTRAINT fk_cart_user FOREIGN KEY (userid) REFERENCES users (userid) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS wishlist (
  wid INT(11) NOT NULL AUTO_INCREMENT,
  userid INT(11) NOT NULL,
  spid INT(11) NOT NULL,
  item_type VARCHAR(20) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (wid),
  KEY idx_wishlist_userid (userid),
  KEY idx_wishlist_spid (spid),
  CONSTRAINT fk_wishlist_user FOREIGN KEY (userid) REFERENCES users (userid) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS orders (
  order_id INT(11) NOT NULL AUTO_INCREMENT,
  userid INT(11) NOT NULL,
  total_amount INT(11) NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (order_id),
  KEY idx_orders_userid (userid),
  CONSTRAINT fk_orders_user FOREIGN KEY (userid) REFERENCES users (userid) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- STEP 2: ORDER ITEMS & STOCK DEDUCTION
-- Description: Tracks individual items in each order
-- Features:
--   - Stores snapshot of price at time of purchase (for historical accuracy)
--   - Tracks quantity purchased
--   - Auto-triggered on checkout: stock is decremented from shop_products
--   - Used by analytics/reports for sales tracking
-- ============================================================
CREATE TABLE IF NOT EXISTS order_items (
  oi_id INT(11) NOT NULL AUTO_INCREMENT,
  order_id INT(11) NOT NULL,
  spid INT(11) NOT NULL,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  qty INT(11) NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (oi_id),
  KEY idx_order_items_order_id (order_id),
  KEY idx_order_items_spid (spid),
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders (order_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS reviews (
  rid INT(11) NOT NULL AUTO_INCREMENT,
  userid INT(11) DEFAULT NULL,
  spid INT(11) DEFAULT NULL,
  rating INT(11) DEFAULT NULL CHECK (rating <= 5),
  comment TEXT DEFAULT NULL,
  is_verified TINYINT(4) DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (rid),
  KEY idx_reviews_userid (userid),
  CONSTRAINT fk_reviews_user FOREIGN KEY (userid) REFERENCES users (userid) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE products
  ADD COLUMN IF NOT EXISTS tag VARCHAR(50) NOT NULL DEFAULT 'new';

ALTER TABLE users
  MODIFY password VARCHAR(255) NOT NULL;

ALTER TABLE shop_products
  ADD COLUMN IF NOT EXISTS stock INT(11) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS discount DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  ADD COLUMN IF NOT EXISTS description TEXT DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS rating DECIMAL(2,1) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

INSERT INTO categories (name, slug) VALUES
  ('Shoes', 'shoes'),
  ('Fashion', 'fashion'),
  ('Clothing', 'clothing'),
  ('Accessories', 'accessories'),
  ('Cosmetics', 'cosmetics'),
  ('Bag', 'bag'),
  ('Sunglasses', 'sunglasses')
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO products (pid, name, category, tag, price, image) VALUES
  (1, 'Classic Leather Sneakers', 'Shoes', 'new', 8999.00, 'https://via.placeholder.com/400x400?text=Sneakers'),
  (2, 'Premium Sunglasses', 'Accessories', 'trending', 4999.00, 'https://via.placeholder.com/400x400?text=Sunglasses'),
  (3, 'Designer Handbag', 'Bags', 'bestseller', 15999.00, 'https://via.placeholder.com/400x400?text=Handbag')
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  category = VALUES(category),
  tag = VALUES(tag),
  price = VALUES(price),
  image = VALUES(image);

INSERT INTO shop_products (spid, name, category, price, stock, image) VALUES
  (1, 'Luxury Watch', 'Accessories', 24999.00, 10, 'https://via.placeholder.com/400x400?text=Watch'),
  (2, 'Elegant Dress', 'Fashion', 12999.00, 15, 'https://via.placeholder.com/400x400?text=Dress'),
  (3, 'Everyday Tote Bag', 'Bags', 7999.00, 20, 'https://via.placeholder.com/400x400?text=Tote+Bag')
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  category = VALUES(category),
  price = VALUES(price),
  stock = VALUES(stock),
  image = VALUES(image);

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- ANALYTICS & REPORTS FEATURES IMPLEMENTED
-- ============================================================
-- DASHBOARD (admin/index.php) - Quick Summary & Alerts
--   ✓ Total Users card
--   ✓ Total Products card
--   ✓ Total Orders card
--   ✓ Total Admins card
--   ✓ Revenue card (with link to Reports)
--   ✓ Low Stock Alert card (Threshold: 5 units)
--   ✓ Recent Orders table (last 5)
--   ✓ Low Stock Items table (with Update action)
--   ✓ Admin Accounts table
--
-- REPORTS (admin/reports.php) - Detailed Analytics
--   STEP 1: Key Metrics ✓ COMPLETED
--     ✓ Total Revenue (all-time)
--     ✓ Total Orders count
--     ✓ Total Customers count
--     ✓ Pending Orders count (orders needing attention)
--
--   STEP 2: Stock Alerts REMOVED (Handled by Dashboard)
--     ⚠️ Removed: Low Stock Items (use Dashboard instead)
--     ⚠️ Removed: Out of Stock Items (use Dashboard instead)
--
--   STEP 3: Order Status Breakdown ✓ COMPLETED
--     ✓ Pending orders count (color: orange)
--     ✓ Confirmed orders count (color: blue)
--     ✓ Shipped orders count (color: purple)
--     ✓ Delivered orders count (color: green)
--     ✓ Cancelled orders count (color: red)
--
--   STEP 4: Category-wise Sales ✓ COMPLETED
--     ✓ Revenue by category
--     ✓ Total units sold per category
--     ✓ Order count per category
--     ✓ Sorted by highest revenue first
--
--   Monthly Revenue Trends ✓ COMPLETED
--     ✓ Revenue by month (last 12 months)
--
--   Top Selling Products ✓ COMPLETED
--     ✓ Top 10 products by units sold
--     ✓ Revenue per product
--
--   Top Customers ✓ COMPLETED
--     ✓ Top 10 customers by spending
--     ✓ Order count per customer
--
-- ============================================================
-- All analytics features are fully operational!
-- No duplication between Dashboard and Reports
-- ============================================================
 