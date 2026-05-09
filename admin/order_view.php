<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;
if ($order_id <= 0) { header('Location: orders.php'); exit(); }

$stmt = $conn->prepare('SELECT * FROM orders WHERE order_id = ?');
$stmt->bind_param('i', $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

$user = null;
if ($order) {
    $uid = (int)$order['userid'];
    $u = $conn->prepare('SELECT userid, username, email FROM users WHERE userid = ?');
    $u->bind_param('i', $uid);
    $u->execute();
    $user = $u->get_result()->fetch_assoc();
}

  $pageTitle = 'Order #' . $order_id;
  $pageSubtitle = 'View details and update status';
  $activePage = 'orders.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Order #<?=$order_id?></title>
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
    <div class="card" style="max-width:1100px">
      <?php if ($order): ?>
        <div class="toolbar" style="justify-content:space-between;align-items:center;margin-bottom:14px">
          <div>
            <div class="muted">User: <?=htmlspecialchars($user['username'] ?? $order['userid'])?> (<?=htmlspecialchars($user['email'] ?? '')?>)</div>
            <div class="muted">Date: <?=htmlspecialchars($order['created_at'])?></div>
          </div>
          <div class="toolbar">
            <a class="btn" href="orders.php">Back to orders</a>
            <a class="btn" href="invoice.php?order_id=<?=$order_id?>">Invoice</a>
          </div>
        </div>

        <div class="content-stack">
          <div class="card">
            <div class="toolbar" style="justify-content:space-between;align-items:center">
              <div>
                <h2 style="margin:0">Order #<?=$order_id?></h2>
                <div class="muted">Total: $<?=number_format($order['total_amount'],2)?></div>
              </div>
              <span class="pill"><?=htmlspecialchars($order['status'])?></span>
            </div>
          </div>

          <?php
            $items = [];
            $ist = $conn->prepare('SELECT spid, name, price, qty, total FROM order_items WHERE order_id = ?');
            $ist->bind_param('i', $order_id);
            $ist->execute();
            $ir = $ist->get_result();
            if ($ir) { while ($r = $ir->fetch_assoc()) $items[] = $r; }
          ?>

          <div class="card">
            <h3 class="section-title">Items</h3>
            <table>
              <thead><tr><th>Description</th><th>Qty</th><th>Unit</th><th>Amount</th></tr></thead>
              <tbody>
              <?php if (count($items) > 0): ?>
                <?php foreach ($items as $it): ?>
                  <tr>
                    <td><?=htmlspecialchars($it['name'])?></td>
                    <td><?=htmlspecialchars($it['qty'])?></td>
                    <td>$<?=number_format($it['price'],2)?></td>
                    <td>$<?=number_format($it['total'],2)?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="4" style="text-align: center; color: #666; padding: 20px; font-size: 14px;">
                  ℹ️ No items recorded for this order. This order was placed before item tracking was implemented.
                </td></tr>
              <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="card">
            <h3 class="section-title">Update Status</h3>
            <form method="post" action="update_order.php">
              <input type="hidden" name="order_id" value="<?=$order_id?>">
              <div class="form-grid">
                <div>
                  <label>Status</label>
                  <select name="status" style="padding: 10px; border-radius: 6px; border: 1px solid #ddd; font-size: 14px; cursor: pointer;">
                    <?php $statuses = ['Pending','Confirmed','Shipped','Delivered','Cancelled'];
                      foreach ($statuses as $s): ?>
                      <option value="<?=$s?>" <?=($order['status']==$s)?'selected':''?>><?=$s?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="toolbar" style="margin-top:12px">
                <button class="btn" type="submit">Save</button>
              </div>
            </form>
          </div>
        </div>

      <?php else: ?>
        <p>Order not found.</p>
      <?php endif; ?>
    </div>

  </main>
</div>
</body>
</html>
