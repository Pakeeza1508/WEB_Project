<?php
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/../db.php';

echo "Starting common columns fixer...\n";

function table_exists($conn, $table) {
    $t = $conn->real_escape_string($table);
    $r = $conn->query("SHOW TABLES LIKE '$t'");
    return ($r && $r->num_rows > 0);
}

function col_exists($conn, $table, $col) {
    $t = $conn->real_escape_string($table);
    $c = $conn->real_escape_string($col);
    $q = $conn->query("SHOW COLUMNS FROM `$t` LIKE '$c'");
    return ($q && $q->num_rows > 0);
}

$tasks = [];

// users: is_active, created_at
if (table_exists($conn, 'users')) {
    if (!col_exists($conn, 'users', 'is_active')) {
        $tasks[] = "ALTER TABLE `users` ADD `is_active` TINYINT(1) NOT NULL DEFAULT 1";
    }
    if (!col_exists($conn, 'users', 'created_at')) {
        $tasks[] = "ALTER TABLE `users` ADD `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
    }
}

// admin_users: ensure email, role, created_at, password length
if (table_exists($conn, 'admin_users')) {
    if (!col_exists($conn, 'admin_users', 'email')) {
        $tasks[] = "ALTER TABLE `admin_users` ADD `email` VARCHAR(150) DEFAULT NULL";
    }
    if (!col_exists($conn, 'admin_users', 'role')) {
        $tasks[] = "ALTER TABLE `admin_users` ADD `role` VARCHAR(50) DEFAULT 'admin'";
    }
    if (!col_exists($conn, 'admin_users', 'created_at')) {
        $tasks[] = "ALTER TABLE `admin_users` ADD `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
    }
    // ensure password is long enough
    $tasks[] = "ALTER TABLE `admin_users` MODIFY password VARCHAR(255) NOT NULL";
}

// shop_products: stock
if (table_exists($conn, 'shop_products')) {
    if (!col_exists($conn, 'shop_products', 'stock')) {
        $tasks[] = "ALTER TABLE `shop_products` ADD `stock` INT NOT NULL DEFAULT 0";
    }
    if (!col_exists($conn, 'shop_products', 'discount')) {
        $tasks[] = "ALTER TABLE `shop_products` ADD `discount` DECIMAL(10,2) NOT NULL DEFAULT 0.00";
    }
    if (!col_exists($conn, 'shop_products', 'description')) {
        $tasks[] = "ALTER TABLE `shop_products` ADD `description` TEXT DEFAULT NULL";
    }
}

// orders: total_amount
if (table_exists($conn, 'orders')) {
    if (!col_exists($conn, 'orders', 'total_amount')) {
        $tasks[] = "ALTER TABLE `orders` ADD `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00";
    }
}

// categories table
if (!table_exists($conn, 'categories')) {
    $tasks[] = "CREATE TABLE `categories` (\n        `cid` INT(11) NOT NULL AUTO_INCREMENT,\n        `name` VARCHAR(150) NOT NULL UNIQUE,\n        `slug` VARCHAR(150) NOT NULL UNIQUE,\n        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,\n        PRIMARY KEY (`cid`)\n    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
}

if (empty($tasks)) {
    echo "Nothing to do — all common columns appear present.\n";
    exit(0);
}

foreach ($tasks as $sql) {
    echo "Running: $sql\n";
    if ($conn->query($sql) === TRUE) {
        echo "-> OK\n";
    } else {
        echo "-> ERROR: " . $conn->error . "\n";
    }
}

echo "Done.\n";

?>
