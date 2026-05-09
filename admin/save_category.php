<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$cid = isset($_POST['cid']) ? (int) $_POST['cid'] : 0;
$name = trim($_POST['name'] ?? '');
$slug = trim($_POST['slug'] ?? '');
if ($slug === '') { $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/','-', $name)); }

if ($name === '') { header('Location: category_form.php?error=missing'); exit(); }

if ($cid > 0) {
    $stmt = $conn->prepare('UPDATE categories SET name=?, slug=? WHERE cid = ?');
    $stmt->bind_param('ssi', $name, $slug, $cid);
    $stmt->execute();
} else {
    $stmt = $conn->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)');
    $stmt->bind_param('ss', $name, $slug);
    $stmt->execute();
}

header('Location: categories.php');
exit();
