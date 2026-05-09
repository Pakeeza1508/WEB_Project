<?php
// Run from browser once to add missing columns for product management.
include_once __DIR__ . '/../db.php';

// Allow running from CLI for convenience (skips admin session check)
if (php_sapi_name() !== 'cli') {
    require_once __DIR__ . '/inc/auth.php';
    require_admin();
}

$queries = [
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00",
    "ALTER TABLE orders MODIFY COLUMN total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00",
    "ALTER TABLE shop_products ADD COLUMN IF NOT EXISTS stock INT(11) NOT NULL DEFAULT 0",
    "ALTER TABLE shop_products ADD COLUMN IF NOT EXISTS discount DECIMAL(10,2) NOT NULL DEFAULT 0.00",
    "ALTER TABLE shop_products ADD COLUMN IF NOT EXISTS description TEXT DEFAULT NULL",
    "CREATE TABLE IF NOT EXISTS categories (
       cid INT(11) NOT NULL AUTO_INCREMENT,
       name VARCHAR(150) NOT NULL UNIQUE,
       slug VARCHAR(150) NOT NULL UNIQUE,
       created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (cid)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS is_active TINYINT(1) NOT NULL DEFAULT 1",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
];

// Helper to check if a column exists in current database
function columnExists($conn, $table, $column) {
    $t = $conn->real_escape_string($table);
    $c = $conn->real_escape_string($column);
    $sql = "SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$t' AND COLUMN_NAME = '$c'";
    $res = $conn->query($sql);
    if (! $res) return false;
    $row = $res->fetch_assoc();
    return ((int)$row['cnt']) > 0;
}

// If legacy column names exist, queue renames so other code expects `order_id`, `userid`, `status`.
if (columnExists($conn, 'orders', 'oid') && !columnExists($conn, 'orders', 'order_id')) {
    $queries[] = "ALTER TABLE orders CHANGE `oid` `order_id` INT(11) NOT NULL AUTO_INCREMENT";
}
if (columnExists($conn, 'orders', 'uid') && !columnExists($conn, 'orders', 'userid')) {
    $queries[] = "ALTER TABLE orders CHANGE `uid` `userid` INT(11) NOT NULL";
}
if (columnExists($conn, 'orders', 'order_status') && !columnExists($conn, 'orders', 'status')) {
    $queries[] = "ALTER TABLE orders CHANGE `order_status` `status` VARCHAR(30) NOT NULL DEFAULT 'Pending'";
}

// After renames, create order_items table with FK to normalized orders(order_id)
$queries[] = "CREATE TABLE IF NOT EXISTS order_items (
         oi_id INT(11) NOT NULL AUTO_INCREMENT,
         order_id INT(11) NOT NULL,
         spid INT(11) DEFAULT NULL,
         name VARCHAR(255) NOT NULL,
         price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
         qty INT(11) NOT NULL DEFAULT 1,
         total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
         created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
         PRIMARY KEY (oi_id),
         KEY idx_order_items_order (order_id),
         CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
 

$output = [];
foreach ($queries as $q) {
    if ($conn->query($q) === TRUE) {
        $output[] = "OK: $q";
    } else {
        $output[] = "Error: " . $conn->error . " -- ($q)";
    }
}

echo "<h2>Migration Results</h2>";
echo "<pre>" . implode("\n", $output) . "</pre>";
echo "<p>Delete this file after migration.</p>";
