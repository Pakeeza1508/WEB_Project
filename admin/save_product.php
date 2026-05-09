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
$rating = floatval($_POST['rating'] ?? 0);

if ($name === '') {
    header('Location: product_form.php?error=missing'); exit();
}

if ($category === '') {
    header('Location: product_form.php?error=category'); exit();
}

if ($category !== '') {
    $catSlug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $category));
    $catStmt = $conn->prepare('INSERT INTO categories (name, slug) VALUES (?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name)');
    $catStmt->bind_param('ss', $category, $catSlug);
    $catStmt->execute();
    $catStmt->close();
}

$imagePath = null;
if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $imagePath = moveUploadedImage($_FILES['image']);
}

if ($spid > 0) {
    if ($imagePath) {
        $stmt = $conn->prepare('UPDATE shop_products SET name=?, category=?, price=?, stock=?, discount=?, description=?, rating=?, image=? WHERE spid=?');
        $stmt->bind_param('ssdidsdsi', $name, $category, $price, $stock, $discount, $description, $rating, $imagePath, $spid);
    } else {
        $stmt = $conn->prepare('UPDATE shop_products SET name=?, category=?, price=?, stock=?, discount=?, description=?, rating=? WHERE spid=?');
        $stmt->bind_param('ssdidsdi', $name, $category, $price, $stock, $discount, $description, $rating, $spid);
    }
    $stmt->execute();
} else {
    $stmt = $conn->prepare('INSERT INTO shop_products (name, category, price, stock, discount, description, rating, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssdidsds', $name, $category, $price, $stock, $discount, $description, $rating, $imagePath);
    $stmt->execute();
}

header('Location: products.php');
exit();
