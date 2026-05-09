<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$spid = isset($_GET['spid']) ? (int) $_GET['spid'] : 0;
$product = null;
if ($spid > 0) {
    $stmt = $conn->prepare('SELECT * FROM shop_products WHERE spid = ?');
    $stmt->bind_param('i', $spid);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
}

$pageTitle = $product ? 'Edit Product' : 'Add Product';
$pageSubtitle = 'Create and update catalog items';
$activePage = 'products.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $product ? 'Edit' : 'Add' ?> Product</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
  <?php include __DIR__ . '/inc/sidebar.php'; ?>

    <div class="card" style="max-width:980px">
    <div style="margin-bottom:12px">
      <a class="back-btn" href="index.php" title="Back to dashboard">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
        Back to Dashboard
      </a>
    </div>
      <form method="post" action="save_product.php" enctype="multipart/form-data">
        <input type="hidden" name="spid" value="<?=htmlspecialchars($product['spid'] ?? 0)?>">
        <div class="form-grid">
          <div>
            <label>Name</label>
            <input name="name" required value="<?=htmlspecialchars($product['name'] ?? '')?>">
          </div>
          <div>
            <label>Price</label>
            <input name="price" required value="<?=htmlspecialchars($product['price'] ?? '')?>">
          </div>
          <div>
            <label>Category</label>
            <?php
              $cats = [];
              $catTable = $conn->query("SHOW TABLES LIKE 'categories'");
              if ($catTable && $catTable->num_rows > 0) {
                $cres = $conn->query('SELECT name FROM categories ORDER BY name ASC');
                if ($cres) { while ($r = $cres->fetch_assoc()) $cats[] = $r['name']; }
              }
            ?>
            <?php if (count($cats) > 0): ?>
              <select name="category">
                <option value="">-- Select category --</option>
                <?php foreach ($cats as $cn): ?>
                  <option value="<?=htmlspecialchars($cn)?>" <?= (isset($product['category']) && $product['category']==$cn)?'selected':'' ?>><?=htmlspecialchars($cn)?></option>
                <?php endforeach; ?>
              </select>
            <?php else: ?>
              <input name="category" placeholder="Enter category name" value="<?=htmlspecialchars($product['category'] ?? '')?>">
              <div class="muted" style="margin-top:6px">Categories table is missing, so enter the category directly.</div>
            <?php endif; ?>
          </div>
          <div>
            <label>Stock</label>
            <input name="stock" required value="<?=htmlspecialchars($product['stock'] ?? 0)?>">
          </div>
          <div>
            <label>Discount</label>
            <input name="discount" value="<?=htmlspecialchars($product['discount'] ?? '0.00')?>">
          </div>
          <div>
            <label>New Category</label>
            <input name="new_category" placeholder="Optional new category name">
          </div>
        </div>
        <div style="margin-top:12px">
          <label>Description</label>
          <textarea name="description"><?=htmlspecialchars($product['description'] ?? '')?></textarea>
        </div>
        <div style="margin-top:12px">
          <label>Image (leave blank to keep existing)</label>
          <input type="file" name="image">
        </div>
        <?php if (!empty($product['image'])): ?>
          <div style="margin:12px 0"><img src="<?=htmlspecialchars($product['image'])?>" style="max-width:160px;border-radius:8px"></div>
        <?php endif; ?>
        <div class="toolbar" style="margin-top:16px">
          <button class="btn" type="submit">Save</button>
          <a class="btn" href="products.php">Back to products</a>
        </div>
      </form>
    </div>

  </main>
</div>
</body>
</html>
