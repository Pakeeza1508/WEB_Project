<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: orders.php'); exit();
}

$order_id = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
$status = trim($_POST['status'] ?? '');

if ($order_id > 0 && $status !== '') {
    $stmt = $conn->prepare('UPDATE orders SET status = ? WHERE order_id = ?');
    $stmt->bind_param('si', $status, $order_id);
    $stmt->execute();
}

header('Location: order_view.php?order_id=' . $order_id);
exit();
