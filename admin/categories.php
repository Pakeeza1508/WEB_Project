<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$cats = [];
$res = $conn->query('SELECT * FROM categories ORDER BY name ASC');
if ($res) { while ($r = $res->fetch_assoc()) $cats[] = $r; }

$pageTitle = 'Categories';
$pageSubtitle = 'Organize products into categories';
$activePage = 'categories.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Categories</title>
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
        <a class="btn" href="category_form.php">Add Category</a>
      </div>
      <table>
        <thead><tr><th>#</th><th>Name</th><th>Slug</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($cats as $c): ?>
          <tr>
            <td><?=htmlspecialchars($c['cid'])?></td>
            <td><?=htmlspecialchars($c['name'])?></td>
            <td><?=htmlspecialchars($c['slug'])?></td>
            <td>
              <a class="btn" href="category_form.php?cid=<?=urlencode($c['cid'])?>">Edit</a>
              <a class="btn danger" href="delete_category.php?cid=<?=urlencode($c['cid'])?>" onclick="return confirm('Delete category?')">Delete</a>
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
