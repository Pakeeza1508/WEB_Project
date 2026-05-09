<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

// Support low_stock filter
$products = [];
if (!empty($_GET['low_stock'])) {
  $threshold = 5;
  $stmt = $conn->prepare('SELECT * FROM shop_products WHERE stock <= ? ORDER BY stock ASC');
  $stmt->bind_param('i', $threshold);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($res) { while ($r = $res->fetch_assoc()) $products[] = $r; }
  $pageSubtitle = 'Low stock products (<= ' . $threshold . ' units)';
} else {
  $res = $conn->query('SELECT * FROM shop_products ORDER BY spid DESC');
  if ($res) { while ($r = $res->fetch_assoc()) $products[] = $r; }
}

$pageTitle = 'Products';
$pageSubtitle = 'Manage product catalog and stock';
$activePage = 'products.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Manage Products</title>
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
      <div class="toolbar" style="margin-bottom:12px">
        <a class="btn" href="product_form.php">Add Product</a>
      </div>
      <table>
        <thead><tr><th>#</th><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Discount</th><th>Stock</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($products as $p): ?>
          <tr>
            <td><?=htmlspecialchars($p['spid'])?></td>
            <td><?php if (!empty($p['image'])): ?><img class="thumb" src="<?=htmlspecialchars($p['image'])?>" alt=""><?php endif; ?></td>
            <td><?=htmlspecialchars($p['name'])?></td>
            <td><?=htmlspecialchars($p['category'])?></td>
            <td>$<?=number_format($p['price'],2)?></td>
            <td><?=number_format((float)($p['discount'] ?? 0), 2)?>%</td>
            <td><?=htmlspecialchars($p['stock'] ?? 0)?></td>
            <td>
              <a class="btn" href="product_form.php?spid=<?=urlencode($p['spid'])?>">Edit</a>
              <a class="btn danger" href="delete_product.php?spid=<?=urlencode($p['spid'])?>" onclick="return confirm('Delete product?')">Delete</a>
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
