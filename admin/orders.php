<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$orders = [];
$res = $conn->query('SELECT * FROM orders ORDER BY created_at DESC');
if ($res) { while ($r = $res->fetch_assoc()) $orders[] = $r; }

$pageTitle = 'Orders';
$pageSubtitle = 'Review orders and invoices';
$activePage = 'orders.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Orders</title>
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
    <div class="card">
      <table>
        <thead><tr><th>#</th><th>User</th><th>Total</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td>#<?=htmlspecialchars($o['order_id'])?></td>
            <td><?=htmlspecialchars($o['userid'])?></td>
            <td>$<?=number_format($o['total_amount'],2)?></td>
            <td><span class="pill"><?=htmlspecialchars($o['status'])?></span></td>
            <td><?=htmlspecialchars($o['created_at'])?></td>
            <td>
              <a class="btn" href="order_view.php?order_id=<?=urlencode($o['order_id'])?>">View</a>
              <a class="btn" href="invoice.php?order_id=<?=urlencode($o['order_id'])?>">Invoice</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </main>
</div>
</body>
</html>
