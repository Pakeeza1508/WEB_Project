<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$spid = isset($_GET['spid']) ? (int) $_GET['spid'] : 0;
if ($spid > 0) {
    $stmt = $conn->prepare('SELECT image FROM shop_products WHERE spid = ?');
    $stmt->bind_param('i', $spid);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row && !empty($row['image'])) {
        $local = __DIR__ . '/../' . $row['image'];
        if (file_exists($local)) @unlink($local);
    }
    $stmt = $conn->prepare('DELETE FROM shop_products WHERE spid = ?');
    $stmt->bind_param('i', $spid);
    $stmt->execute();
}

header('Location: products.php');
exit();
