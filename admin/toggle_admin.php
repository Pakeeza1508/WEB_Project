<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$admin_id = isset($_GET['admin_id']) ? (int) $_GET['admin_id'] : 0;
$action = $_GET['action'] ?? '';
if ($admin_id <= 0 || !in_array($action, ['block','unblock'])) {
    header('Location: admins.php'); exit();
}

// Prevent blocking yourself
if (isset($_SESSION['admin_id']) && (int) $_SESSION['admin_id'] === $admin_id) {
    header('Location: admins.php?error=cannot_modify_self'); exit();
}

// Ensure is_active column exists
$q = $conn->query("SHOW COLUMNS FROM `admin_users` LIKE 'is_active'");
if (!$q || $q->num_rows === 0) {
    $conn->query("ALTER TABLE `admin_users` ADD `is_active` TINYINT(1) NOT NULL DEFAULT 1");
}

$val = ($action === 'block') ? 0 : 1;
$stmt = $conn->prepare('UPDATE admin_users SET is_active = ? WHERE admin_id = ? LIMIT 1');
$stmt->bind_param('ii', $val, $admin_id);
$stmt->execute();

header('Location: admins.php'); exit();

?>
