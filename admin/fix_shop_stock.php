<?php
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/../db.php';

echo "Starting shop_products.stock fix...\n";

// Check table exists
$tblRes = $conn->query("SHOW TABLES LIKE 'shop_products'");
if (!$tblRes || $tblRes->num_rows === 0) {
    echo "ERROR: shop_products table not found.\n";
    exit(1);
}

function col_exists($conn, $table, $col) {
    $t = $conn->real_escape_string($table);
    $c = $conn->real_escape_string($col);
    $q = $conn->query("SHOW COLUMNS FROM `$t` LIKE '$c'");
    return ($q && $q->num_rows > 0);
}

if (col_exists($conn, 'shop_products', 'stock')) {
    echo "OK: shop_products.stock already exists.\n";
    exit(0);
}

// If there's a 'quantity' or 'qty' column, rename it to 'stock'
foreach (['quantity', 'qty', 'amount', 'inventory'] as $candidate) {
    if (col_exists($conn, 'shop_products', $candidate)) {
        echo "Found column '$candidate' — attempting to rename to 'stock'...\n";
        // try to preserve type by reading column type
        $res = $conn->query("SHOW FIELDS FROM `shop_products` LIKE '$candidate'");
        $row = $res ? $res->fetch_assoc() : null;
        $type = $row['Type'] ?? 'INT(11)';
        $null = ($row && $row['Null'] === 'YES') ? 'NULL' : 'NOT NULL';
        $default = isset($row['Default']) && $row['Default'] !== null ? "DEFAULT '" . $conn->real_escape_string($row['Default']) . "'" : '';
        $sql = "ALTER TABLE `shop_products` CHANGE `$candidate` `stock` $type $null $default";
        if ($conn->query($sql) === TRUE) {
            echo "SUCCESS: Renamed $candidate -> stock.\n";
            exit(0);
        } else {
            echo "ERROR: Failed to rename $candidate: " . $conn->error . "\n";
            // continue to next candidate
        }
    }
}

// Otherwise add the stock column
echo "Adding stock column as INT NOT NULL DEFAULT 0...\n";

// Try to place after price if exists
$after = col_exists($conn, 'shop_products', 'price') ? ' AFTER `price`' : '';
$sql2 = "ALTER TABLE `shop_products` ADD `stock` INT NOT NULL DEFAULT 0" . $after;
if ($conn->query($sql2) === TRUE) {
    echo "SUCCESS: Added stock column.\n";
    exit(0);
} else {
    echo "ERROR: Failed to add stock: " . $conn->error . "\n";
    exit(1);
}

?>
