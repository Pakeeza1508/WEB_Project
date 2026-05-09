<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$filter = trim($_GET['filter'] ?? 'all');
$sql = 'SELECT r.rid, r.userid, r.spid, r.rating, r.comment, r.is_verified, r.created_at, u.username, sp.name AS product_name FROM reviews r LEFT JOIN users u ON r.userid = u.userid LEFT JOIN shop_products sp ON r.spid = sp.spid';
if ($filter === 'pending') {
    $sql .= ' WHERE r.is_verified = 0';
} elseif ($filter === 'approved') {
    $sql .= ' WHERE r.is_verified = 1';
}
$sql .= ' ORDER BY r.created_at DESC';

$res = $conn->query($sql);
$reviews = [];
if ($res) { while ($r = $res->fetch_assoc()) $reviews[] = $r; }

$pageTitle = 'Reviews';
$pageSubtitle = 'Approve, reject, or remove customer feedback';
$activePage = 'reviews.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Reviews Moderation</title>
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
        <a class="btn" href="reviews.php?filter=all">All</a>
        <a class="btn" href="reviews.php?filter=pending">Pending</a>
        <a class="btn" href="reviews.php?filter=approved">Approved</a>
      </div>
      <table>
        <thead><tr><th>#</th><th>Product</th><th>User</th><th>Rating</th><th>Comment</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($reviews as $r): ?>
          <tr>
            <td><?=htmlspecialchars($r['rid'])?></td>
            <td><?=htmlspecialchars($r['product_name'] ?? '—')?></td>
            <td><?=htmlspecialchars($r['username'] ?? 'Guest')?></td>
            <td><?=htmlspecialchars($r['rating'])?></td>
            <td><?=nl2br(htmlspecialchars($r['comment']))?></td>
            <td><span class="pill"><?= $r['is_verified'] ? 'Approved' : 'Pending' ?></span></td>
            <td>
              <?php if (!$r['is_verified']): ?>
                <a class="btn" href="toggle_review.php?rid=<?=urlencode($r['rid'])?>&action=approve">Approve</a>
              <?php else: ?>
                <a class="btn" href="toggle_review.php?rid=<?=urlencode($r['rid'])?>&action=reject">Reject</a>
              <?php endif; ?>
              <a class="btn danger" href="delete_review.php?rid=<?=urlencode($r['rid'])?>" onclick="return confirm('Delete this review?')">Delete</a>
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
