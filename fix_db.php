<?php
include __DIR__ . '/db.php';

// Create table
$sql = "CREATE TABLE IF NOT EXISTS `categories` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`cid`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if ($conn->query($sql)) {
    echo "Table 'categories' created successfully.\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

// Populate with distinct categories from shop_products
$sql2 = "INSERT IGNORE INTO `categories` (`name`, `slug`) 
         SELECT DISTINCT category, LOWER(REPLACE(category, ' ', '-')) 
         FROM shop_products 
         WHERE category IS NOT NULL AND category != '';";
$conn->query($sql2);

// Populate with distinct categories from products that might not be in shop_products
$sql3 = "INSERT IGNORE INTO `categories` (`name`, `slug`) 
         SELECT DISTINCT category, LOWER(REPLACE(category, ' ', '-')) 
         FROM products 
         WHERE category IS NOT NULL AND category != '';";
$conn->query($sql3);

echo "Categories table populated successfully.\n";
?>
