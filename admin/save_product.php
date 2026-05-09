<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

function moveUploadedImage($file) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return null;
    $dir = __DIR__ . '/../uploads/products/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $name = uniqid('p_', true) . '.' . $ext;
    $target = $dir . $name;
    if (move_uploaded_file($file['tmp_name'], $target)) {
        return 'uploads/products/' . $name;
    }
    return null;
}

$spid = isset($_POST['spid']) ? (int) $_POST['spid'] : 0;
$name = trim($_POST['name'] ?? '');
$posted_category = trim($_POST['category'] ?? '');
$new_category = trim($_POST['new_category'] ?? '');
$category = $new_category !== '' ? $new_category : $posted_category;
$price = floatval($_POST['price'] ?? 0);
$stock = intval($_POST['stock'] ?? 0);
$discount = floatval($_POST['discount'] ?? 0.00);
$description = trim($_POST['description'] ?? '');

if ($name === '') {
    header('Location: product_form.php?error=missing'); exit();
}

$imagePath = null;
if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $imagePath = moveUploadedImage($_FILES['image']);
}

if ($spid > 0) {
    if ($imagePath) {
        $stmt = $conn->prepare('UPDATE shop_products SET name=?, category=?, price=?, stock=?, discount=?, description=?, image=? WHERE spid=?');
        $stmt->bind_param('ssdidssi', $name, $category, $price, $stock, $discount, $description, $imagePath, $spid);
    } else {
        $stmt = $conn->prepare('UPDATE shop_products SET name=?, category=?, price=?, stock=?, discount=?, description=? WHERE spid=?');
        $stmt->bind_param('ssdidsi', $name, $category, $price, $stock, $discount, $description, $spid);
    }
    $stmt->execute();
} else {
    $stmt = $conn->prepare('INSERT INTO shop_products (name, category, price, stock, discount, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssdidss', $name, $category, $price, $stock, $discount, $description, $imagePath);
    $stmt->execute();
}

header('Location: products.php');
exit();
