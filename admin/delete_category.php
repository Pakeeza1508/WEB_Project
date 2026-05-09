<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$cid = isset($_GET['cid']) ? (int) $_GET['cid'] : 0;
if ($cid > 0) {
    // set products to Uncategorized (optional) then delete category
    $stmt = $conn->prepare('UPDATE shop_products SET category = ? WHERE category = (SELECT name FROM categories WHERE cid = ?)');
    $uncat = 'Uncategorized';
    $stmt->bind_param('si', $uncat, $cid);
    @$stmt->execute();

    $stmt = $conn->prepare('DELETE FROM categories WHERE cid = ?');
    $stmt->bind_param('i', $cid);
    $stmt->execute();
}

header('Location: categories.php');
exit();
