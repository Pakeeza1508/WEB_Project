<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$userid = isset($_GET['userid']) ? (int) $_GET['userid'] : 0;
$action = trim($_GET['action'] ?? '');
if ($userid > 0 && in_array($action, ['block','unblock'])) {
    $val = $action === 'block' ? 0 : 1;
    $stmt = $conn->prepare('UPDATE users SET is_active = ? WHERE userid = ?');
    $stmt->bind_param('ii', $val, $userid);
    $stmt->execute();
}

header('Location: users.php');
exit();
