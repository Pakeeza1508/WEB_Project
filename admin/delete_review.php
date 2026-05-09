<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$rid = isset($_GET['rid']) ? (int) $_GET['rid'] : 0;
if ($rid > 0) {
    $stmt = $conn->prepare('DELETE FROM reviews WHERE rid = ?');
    $stmt->bind_param('i', $rid);
    $stmt->execute();
}

header('Location: reviews.php');
exit();
