<?php
// Safe migration: ensure `orders.created_at` exists.
header('Content-Type: text/plain; charset=utf-8');

require_once __DIR__ . '/../db.php';

echo "Starting orders.created_at fix...\n";

// Check table exists
$tblRes = $conn->query("SHOW TABLES LIKE 'orders'");
if (!$tblRes || $tblRes->num_rows === 0) {
    echo "ERROR: orders table not found.\n";
    exit(1);
}

// Helper to check column
function column_exists($conn, $table, $column) {
    $t = $conn->real_escape_string($table);
    $c = $conn->real_escape_string($column);
    $q = $conn->query("SHOW COLUMNS FROM `$t` LIKE '$c'");
    return ($q && $q->num_rows > 0);
}

if (column_exists($conn, 'orders', 'created_at')) {
    echo "OK: orders.created_at already exists — nothing to do.\n";
    exit(0);
}

if (column_exists($conn, 'orders', 'order_date')) {
    // Rename order_date -> created_at preserving type
    echo "Found orders.order_date — attempting to rename to created_at...\n";
    $sql = "ALTER TABLE `orders` CHANGE `order_date` `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
    if ($conn->query($sql) === TRUE) {
        echo "SUCCESS: Renamed order_date -> created_at.\n";
        exit(0);
    } else {
        echo "ERROR: Failed to rename column: " . $conn->error . "\n";
        // fallback: try adding created_at instead
    }
}

// If reached here, add created_at column
echo "Adding created_at column as TIMESTAMP with CURRENT_TIMESTAMP default...\n";
$sql2 = "ALTER TABLE `orders` ADD `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `status`";
if ($conn->query($sql2) === TRUE) {
    echo "SUCCESS: added created_at.\n";
    exit(0);
} else {
    echo "ERROR: Failed to add created_at: " . $conn->error . "\n";
    exit(1);
}

?>
