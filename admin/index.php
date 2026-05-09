<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

// Stats
$usersCount = (int) $conn->query('SELECT COUNT(*) AS c FROM users')->fetch_assoc()['c'];
$productsCount = (int) $conn->query('SELECT COUNT(*) AS c FROM shop_products')->fetch_assoc()['c'];
$ordersCount = (int) $conn->query('SELECT COUNT(*) AS c FROM orders')->fetch_assoc()['c'];
$adminsCount = (int) $conn->query('SELECT COUNT(*) AS c FROM admin_users')->fetch_assoc()['c'];
$revenue = (float) $conn->query("SELECT IFNULL(SUM(total_amount),0) AS r FROM orders")->fetch_assoc()['r'];

// Recent orders
$recentOrders = [];
$res = $conn->query('SELECT order_id, userid, total_amount, status, created_at FROM orders ORDER BY created_at DESC LIMIT 5');
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $recentOrders[] = $row;
    }
}

// Low stock alerts (products with stock <= threshold)
$lowStockThreshold = 5;
$lowStockItems = [];
$lsRes = $conn->prepare('SELECT spid, name, stock FROM shop_products WHERE stock <= ? ORDER BY stock ASC LIMIT 10');
$lsRes->bind_param('i', $lowStockThreshold);
$lsRes->execute();
$lsResR = $lsRes->get_result();
if ($lsResR) {
  while ($r = $lsResR->fetch_assoc()) $lowStockItems[] = $r;
}
$lowStockCount = count($lowStockItems);

// Admin accounts summary
$adminAccounts = [];
$adminRes = $conn->query('SELECT admin_id, username, email, role, created_at FROM admin_users ORDER BY created_at DESC LIMIT 10');
if ($adminRes) {
  while ($a = $adminRes->fetch_assoc()) {
    $adminAccounts[] = $a;
  }
}

$pageTitle = 'Admin Dashboard';
$pageSubtitle = 'Overview and quick actions';
$activePage = 'index.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
  <?php include __DIR__ . '/inc/sidebar.php'; ?>

    <div class="stats-grid">
      <a href="users.php" style="text-decoration:none;color:inherit;display:block">
        <div class="stat card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; min-height: 160px; box-sizing: border-box; overflow: hidden;">
          <h3 style="color: rgba(255,255,255,0.9); font-size: 11px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">TOTAL USERS</h3>
          <div style="font-size: max(24px, min(8vw, 36px)); font-weight: 700; word-break: break-word; line-height: 1.2;"><?=$usersCount?></div>
        </div>
      </a>

      <a href="products.php" style="text-decoration:none;color:inherit;display:block">
        <div class="stat card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; min-height: 160px; box-sizing: border-box; overflow: hidden;">
          <h3 style="color: rgba(255,255,255,0.9); font-size: 11px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">TOTAL PRODUCTS</h3>
          <div style="font-size: max(24px, min(8vw, 36px)); font-weight: 700; word-break: break-word; line-height: 1.2;"><?=$productsCount?></div>
        </div>
      </a>

      <a href="orders.php" style="text-decoration:none;color:inherit;display:block">
        <div class="stat card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border: none; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; min-height: 160px; box-sizing: border-box; overflow: hidden;">
          <h3 style="color: rgba(255,255,255,0.9); font-size: 11px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">TOTAL ORDERS</h3>
          <div style="font-size: max(24px, min(8vw, 36px)); font-weight: 700; word-break: break-word; line-height: 1.2;"><?=$ordersCount?></div>
        </div>
      </a>

      <a href="admins.php" style="text-decoration:none;color:inherit;display:block">
        <div class="stat card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; border: none; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; min-height: 160px; box-sizing: border-box; overflow: hidden;">
          <h3 style="color: rgba(255,255,255,0.9); font-size: 11px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">TOTAL ADMINS</h3>
          <div style="font-size: max(24px, min(8vw, 36px)); font-weight: 700; word-break: break-word; line-height: 1.2;"><?=$adminsCount?></div>
        </div>
      </a>

      <a href="reports.php" style="text-decoration:none;color:inherit;display:block">
        <div class="stat card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; min-height: 160px; box-sizing: border-box; overflow: hidden;">
          <h3 style="color: rgba(255,255,255,0.9); font-size: 11px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">REVENUE</h3>
          <div style="font-size: max(20px, min(6vw, 32px)); font-weight: 700; word-break: break-word; line-height: 1.2; overflow: hidden; text-overflow: ellipsis;">$<?=number_format($revenue,2)?></div>
          <div style="margin-top: auto; color: rgba(255,255,255,0.8); font-size: 10px; margin-top: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">View full analytics</div>
        </div>
      </a>

      <a href="products.php?low_stock=1" style="text-decoration:none;color:inherit;display:block">
        <div class="stat card" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); color: white; border: none; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; min-height: 160px; box-sizing: border-box; overflow: hidden;">
          <h3 style="color: rgba(255,255,255,0.9); font-size: 11px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">⚠️ LOW STOCK</h3>
          <div style="font-size: max(24px, min(8vw, 36px)); font-weight: 700; word-break: break-word; line-height: 1.2;"><?=$lowStockCount?></div>
          <div style="margin-top: auto; color: rgba(255,255,255,0.8); font-size: 10px; margin-top: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Threshold: <?=$lowStockThreshold?> units</div>
        </div>
      </a>
    </div>

    <div class="content-stack">
      <div class="card">
        <h3 class="section-title">Recent Orders</h3>
        <table>
          <thead><tr><th>Order</th><th>User</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
          <tbody>
          <?php foreach ($recentOrders as $o): ?>
            <tr>
              <td>#<?=htmlspecialchars($o['order_id'])?></td>
              <td><?=htmlspecialchars($o['userid'])?></td>
              <td>$<?=number_format($o['total_amount'],2)?></td>
              <td><span class="pill"><?=htmlspecialchars($o['status'])?></span></td>
              <td><?=htmlspecialchars($o['created_at'])?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php if ($lowStockCount > 0): ?>
        <div class="card">
          <h3 class="section-title">Low Stock Items</h3>
          <table>
            <thead><tr><th>Product</th><th>Stock</th><th>Action</th></tr></thead>
            <tbody>
              <?php foreach ($lowStockItems as $it): ?>
                <tr>
                  <td><?=htmlspecialchars($it['name'])?></td>
                  <td><?=htmlspecialchars($it['stock'])?></td>
                  <td><a class="btn" href="product_form.php?spid=<?=urlencode($it['spid'])?>">Update</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

      <div class="card">
        <h3 class="section-title">Admin Accounts</h3>
        <table>
          <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th></tr></thead>
          <tbody>
          <?php foreach ($adminAccounts as $a): ?>
            <tr>
              <td>#<?=htmlspecialchars($a['admin_id'])?></td>
              <td><?=htmlspecialchars($a['username'])?></td>
              <td><?=htmlspecialchars($a['email'] ?? '')?></td>
              <td><span class="pill"><?=htmlspecialchars($a['role'] ?? 'admin')?></span></td>
              <td><?=htmlspecialchars($a['created_at'] ?? '')?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (count($adminAccounts) === 0): ?>
            <tr><td colspan="5">No admin accounts found.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>
</div>
</body>
</html>
