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

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Invoice #<?=$order_id?></title>
  <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
  <div class="invoice">
    <div class="header">
      <div>
        <h2>LuxeStore</h2>
        <div>Invoice #: <?=$order_id?></div>
        <div>Date: <?=htmlspecialchars($order['created_at'] ?? '')?></div>
      </div>
      <div>
        <strong>Bill To:</strong>
        <div><?=htmlspecialchars($user['username'] ?? $order['userid'])?></div>
        <div><?=htmlspecialchars($user['email'] ?? '')?></div>
      </div>
    </div>

    <h3 style="margin-top:18px">Order Items</h3>
    <?php
      $items = [];
      $ist = $conn->prepare('SELECT spid, name, price, qty, total FROM order_items WHERE order_id = ?');
      $ist->bind_param('i', $order_id);
      $ist->execute();
      $ir = $ist->get_result();
      if ($ir) { while ($row = $ir->fetch_assoc()) $items[] = $row; }
    ?>
    <table>
      <thead><tr><th>Description</th><th>Qty</th><th>Unit</th><th>Amount</th></tr></thead>
      <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td><?=htmlspecialchars($it['name'])?></td>
          <td><?=htmlspecialchars($it['qty'])?></td>
          <td>$<?=number_format($it['price'],2)?></td>
          <td>$<?=number_format($it['total'],2)?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr><th colspan="3">Total</th><th>$<?=number_format($order['total_amount'],2)?></th></tr>
      </tfoot>
    </table>

    <div style="margin-top:18px">
      <a class="btn" href="orders.php">Back</a>
      <a class="btn" href="#" onclick="window.print();return false;">Print</a>
    </div>
  </div>
</body>
</html>
