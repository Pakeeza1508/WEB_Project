-- Create categories table for shopping_db
-- Import this into phpMyAdmin or run it in MySQL

USE shopping_db;

-- Drop existing table if needed (comment this out if you want to keep existing data)
-- DROP TABLE IF EXISTS categories;

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
  cid INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL UNIQUE,
  slug VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (cid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample categories
INSERT INTO categories (name, slug) VALUES
  ('Shoes', 'shoes'),
  ('Fashion', 'fashion'),
  ('Clothing', 'clothing'),
  ('Accessories', 'accessories'),
  ('Cosmetics', 'cosmetics'),
  ('Bag', 'bag'),
  ('Sunglasses', 'sunglasses')
ON DUPLICATE KEY UPDATE name=VALUES(name);
