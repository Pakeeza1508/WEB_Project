<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$userid = isset($_GET['userid']) ? (int) $_GET['userid'] : 0;
if ($userid > 0) {
    // Deleting user will cascade to orders/cart/wishlist if FKs are set
    $stmt = $conn->prepare('DELETE FROM users WHERE userid = ?');
    $stmt->bind_param('i', $userid);
    $stmt->execute();
}

header('Location: users.php');
exit();
