<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$admin_id = isset($_GET['admin_id']) ? (int) $_GET['admin_id'] : 0;
if ($admin_id <= 0) {
    header('Location: admins.php'); exit();
}

// Prevent deleting yourself
if (isset($_SESSION['admin_id']) && (int) $_SESSION['admin_id'] === $admin_id) {
    header('Location: admins.php?error=cannot_delete_self'); exit();
}

$stmt = $conn->prepare('DELETE FROM admin_users WHERE admin_id = ? LIMIT 1');
$stmt->bind_param('i', $admin_id);
$stmt->execute();

header('Location: admins.php?deleted=1');
exit();

?>
