<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$rid = isset($_GET['rid']) ? (int) $_GET['rid'] : 0;
$action = trim($_GET['action'] ?? '');
if ($rid > 0 && in_array($action, ['approve','reject'])) {
    $val = $action === 'approve' ? 1 : 0;
    $stmt = $conn->prepare('UPDATE reviews SET is_verified = ? WHERE rid = ?');
    $stmt->bind_param('ii', $val, $rid);
    $stmt->execute();
}

header('Location: reviews.php');
exit();
