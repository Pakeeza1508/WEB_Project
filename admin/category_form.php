<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$cid = isset($_GET['cid']) ? (int) $_GET['cid'] : 0;
$cat = null;
if ($cid > 0) {
    $stmt = $conn->prepare('SELECT * FROM categories WHERE cid = ?');
    $stmt->bind_param('i', $cid);
    $stmt->execute();
    $cat = $stmt->get_result()->fetch_assoc();
}

function slugify($text){ return strtolower(preg_replace('/[^A-Za-z0-9-]+/','-',trim($text))); }

$pageTitle = $cat ? 'Edit Category' : 'Add Category';
$pageSubtitle = 'Keep the product catalog organized';
$activePage = 'categories.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $cat ? 'Edit' : 'Add' ?> Category</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
  <?php include __DIR__ . '/inc/sidebar.php'; ?>

    <div class="card" style="max-width:820px">
      <form method="post" action="save_category.php">
        <input type="hidden" name="cid" value="<?=htmlspecialchars($cat['cid'] ?? 0)?>">
        <div class="form-grid">
          <div>
            <label>Name</label>
            <input name="name" required value="<?=htmlspecialchars($cat['name'] ?? '')?>">
          </div>
          <div>
            <label>Slug (optional)</label>
            <input name="slug" value="<?=htmlspecialchars($cat['slug'] ?? '')?>">
          </div>
        </div>
          <div style="margin-bottom:12px">
            <a class="back-btn" href="index.php" title="Back to dashboard">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
              Back to Dashboard
            </a>
          </div>
        <div class="toolbar" style="margin-top:16px">
          <button class="btn" type="submit">Save</button>
          <a class="btn" href="categories.php">Back to categories</a>
        </div>
      </form>
    </div>

  </main>
</div>
</body>
</html>
