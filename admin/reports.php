<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

// Key Metrics
$totalRevenue = 0;
$totalOrders = 0;
$totalCustomers = 0;
$pendingOrders = 0;

// Total Revenue
$rev = $conn->query("SELECT IFNULL(SUM(total_amount),0) AS total FROM orders");
if ($rev) { $r = $rev->fetch_assoc(); $totalRevenue = $r['total']; }

// Total Orders
$ord = $conn->query("SELECT COUNT(*) AS count FROM orders");
if ($ord) { $r = $ord->fetch_assoc(); $totalOrders = $r['count']; }

// Total Customers
$cust = $conn->query("SELECT COUNT(*) AS count FROM users");
if ($cust) { $r = $cust->fetch_assoc(); $totalCustomers = $r['count']; }

// Pending Orders
$pend = $conn->query("SELECT COUNT(*) AS count FROM orders WHERE status = 'Pending'");
if ($pend) { $r = $pend->fetch_assoc(); $pendingOrders = $r['count']; }

// Order Status Breakdown
$orderStatus = [];
$statusRes = $conn->query("SELECT status, COUNT(*) AS count FROM orders GROUP BY status");
if ($statusRes) { while ($r = $statusRes->fetch_assoc()) $orderStatus[$r['status']] = $r['count']; }

// Category-wise Sales (Revenue by Category)
$categorySales = [];
$catRes = $conn->query("
  SELECT 
    sp.category,
    COUNT(DISTINCT oi.order_id) AS orders,
    SUM(oi.qty) AS total_qty,
    SUM(oi.total) AS revenue
  FROM order_items oi
  LEFT JOIN shop_products sp ON oi.spid = sp.spid
  WHERE sp.category IS NOT NULL
  GROUP BY sp.category
  ORDER BY revenue DESC
");
if ($catRes) { while ($r = $catRes->fetch_assoc()) $categorySales[] = $r; }

// Monthly revenue (last 12 months)
$monthly = [];
$mres = $conn->query("SELECT DATE_FORMAT(created_at,'%Y-%m') AS ym, IFNULL(SUM(total_amount),0) AS revenue FROM orders GROUP BY ym ORDER BY ym DESC LIMIT 12");
if ($mres) { while ($r = $mres->fetch_assoc()) $monthly[] = $r; }

// Top selling products
$topProducts = [];
$tres = $conn->query("SELECT oi.spid, oi.name, SUM(oi.qty) AS total_qty, SUM(oi.total) AS revenue FROM order_items oi GROUP BY oi.spid ORDER BY total_qty DESC LIMIT 10");
if ($tres) { while ($r = $tres->fetch_assoc()) $topProducts[] = $r; }

// Top customers by orders
$topCustomers = [];
$cres = $conn->query("SELECT u.userid, u.username, COUNT(o.order_id) AS orders_count, IFNULL(SUM(o.total_amount),0) AS total_spent FROM users u LEFT JOIN orders o ON u.userid = o.userid GROUP BY u.userid ORDER BY orders_count DESC LIMIT 10");
if ($cres) { while ($r = $cres->fetch_assoc()) $topCustomers[] = $r; }

$pageTitle = 'Reports & Analytics';
$pageSubtitle = 'Sales trends, top products, and customer insights';
$activePage = 'reports.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Reports & Analytics</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
  <?php include __DIR__ . '/inc/sidebar.php'; ?>
    <div style="margin-bottom:12px">
      <a class="back-btn" href="index.php" title="Back to dashboard">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
        Back to Dashboard
      </a>
    </div>

    <!-- Key Metrics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px; margin-bottom: 24px;">
      <div class="card" style="padding: 20px; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div style="font-size: 12px; opacity: 0.9; margin-bottom: 8px;">TOTAL REVENUE</div>
        <div style="font-size: 28px; font-weight: 600;">$<?=number_format($totalRevenue, 2)?></div>
        <div style="font-size: 11px; opacity: 0.8; margin-top: 4px;">All-time</div>
      </div>

      <div class="card" style="padding: 20px; text-align: center; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
        <div style="font-size: 12px; opacity: 0.9; margin-bottom: 8px;">TOTAL ORDERS</div>
        <div style="font-size: 28px; font-weight: 600;"><?=$totalOrders?></div>
        <div style="font-size: 11px; opacity: 0.8; margin-top: 4px;">All orders</div>
      </div>

      <div class="card" style="padding: 20px; text-align: center; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
        <div style="font-size: 12px; opacity: 0.9; margin-bottom: 8px;">TOTAL CUSTOMERS</div>
        <div style="font-size: 28px; font-weight: 600;"><?=$totalCustomers?></div>
        <div style="font-size: 11px; opacity: 0.8; margin-top: 4px;">Registered users</div>
      </div>

      <div class="card" style="padding: 20px; text-align: center; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
        <div style="font-size: 12px; opacity: 0.9; margin-bottom: 8px;">PENDING ORDERS</div>
        <div style="font-size: 28px; font-weight: 600; color: #fff;"><?=$pendingOrders?></div>
        <div style="font-size: 11px; opacity: 0.8; margin-top: 4px; color: #fff;">Needs attention</div>
      </div>
    </div>

    <!-- Order Status Breakdown Section -->
    <div class="card" style="margin-bottom: 24px;">
      <h3 class="section-title">📊 Order Status Breakdown</h3>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
        <?php
          $statusColors = [
            'Pending' => '#ff9800',
            'Confirmed' => '#2196f3',
            'Shipped' => '#9c27b0',
            'Delivered' => '#4caf50',
            'Cancelled' => '#f44336'
          ];
        ?>
        <?php foreach ($statusColors as $status => $color): ?>
          <?php $count = $orderStatus[$status] ?? 0; ?>
          <div style="background-color: {$color}20; border-left: 4px solid {$color}; padding: 16px; border-radius: 8px;">
            <div style="font-size: 12px; color: {$color}; font-weight: 600; margin-bottom: 8px;"><?=$status?></div>
            <div style="font-size: 24px; font-weight: 600; color: {$color};"><?=$count?></div>
            <div style="font-size: 11px; color: #666; margin-top: 4px;"><?php echo $count == 1 ? 'order' : 'orders'; ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Category-wise Sales Section -->
    <div class="card" style="margin-bottom: 24px;">
      <h3 class="section-title">📦 Sales by Category</h3>
      <?php if (count($categorySales) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Category</th>
              <th>Orders</th>
              <th>Units Sold</th>
              <th>Revenue</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($categorySales as $cat): ?>
              <tr>
                <td><strong><?=htmlspecialchars($cat['category'] ?? 'Uncategorized')?></strong></td>
                <td><?=$cat['orders']?></td>
                <td><?=$cat['total_qty']?></td>
                <td>$<?=number_format($cat['revenue'], 2)?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p style="text-align: center; color: #666; padding: 20px;">No sales data available yet</p>
      <?php endif; ?>
    </div>

    <div class="content-stack">
      <div class="card">
        <h3 class="section-title">Monthly Revenue (last 12)</h3>
        <table>
          <thead><tr><th>Month</th><th>Revenue</th></tr></thead>
          <tbody>
            <?php foreach ($monthly as $m): ?>
              <tr><td><?=htmlspecialchars($m['ym'])?></td><td>$<?=number_format($m['revenue'],2)?></td></tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h3 class="section-title">Top Selling Products</h3>
        <table>
          <thead><tr><th>Product</th><th>Qty Sold</th><th>Revenue</th></tr></thead>
          <tbody>
            <?php foreach ($topProducts as $p): ?>
              <tr>
                <td><?=htmlspecialchars($p['name'])?></td>
                <td><?=htmlspecialchars($p['total_qty'])?></td>
                <td>$<?=number_format($p['revenue'],2)?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h3 class="section-title">Top Customers</h3>
        <table>
          <thead><tr><th>User</th><th>Orders</th><th>Total Spent</th></tr></thead>
          <tbody>
            <?php foreach ($topCustomers as $c): ?>
              <tr>
                <td><?=htmlspecialchars($c['username'])?> (ID <?=htmlspecialchars($c['userid'])?>)</td>
                <td><?=htmlspecialchars($c['orders_count'])?></td>
                <td>$<?=number_format($c['total_spent'],2)?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>
</div>
</body>
</html>
