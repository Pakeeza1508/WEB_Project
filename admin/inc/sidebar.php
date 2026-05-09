<?php
if (!isset($activePage)) {
    $activePage = '';
}
if (!isset($pageTitle)) {
    $pageTitle = 'Admin Panel';
}
if (!isset($pageSubtitle)) {
    $pageSubtitle = 'Manage your store';
}
$adminUser = htmlspecialchars($_SESSION['admin_user'] ?? 'Admin', ENT_QUOTES, 'UTF-8');

$navItems = [
    'index.php' => 'Dashboard',
    'products.php' => 'Products',
    'categories.php' => 'Categories',
    'orders.php' => 'Orders',
    'users.php' => 'Users',
    'reviews.php' => 'Reviews',
    'reports.php' => 'Reports',
];
?>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <div class="admin-brand">
      <strong>LUXE ADMIN</strong>
      <span><?=htmlspecialchars($adminUser)?> · <?=htmlspecialchars($pageSubtitle)?></span>
    </div>
    <nav class="admin-nav">
      <?php foreach ($navItems as $href => $label): ?>
        <a href="<?=$href?>" class="<?= $activePage === $href ? 'active' : '' ?>">
          <span><?=htmlspecialchars($label)?></span>
          <span>›</span>
        </a>
      <?php endforeach; ?>
      <a href="logout.php" style="margin-top:10px;color:#fecaca">Logout</a>
    </nav>
  </aside>
  <main class="admin-main">
    <div class="page-head">
      <div>
        <h1><?=htmlspecialchars($pageTitle)?></h1>
        <div class="muted"><?=htmlspecialchars($pageSubtitle)?></div>
      </div>
      <div class="admin-tools">
        <a class="btn" href="index.php">Dashboard</a>
        <a class="btn" href="reports.php">Reports</a>
      </div>
    </div>
