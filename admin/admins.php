<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$admins = [];
$res = $conn->query('SELECT admin_id, username, email, role, IFNULL(created_at,"") AS created_at, IFNULL(is_active,1) AS is_active FROM admin_users ORDER BY admin_id DESC');
if ($res) { while ($r = $res->fetch_assoc()) $admins[] = $r; }

$pageTitle = 'Admin Accounts';
$pageSubtitle = 'Manage administrator accounts';
$activePage = 'users.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admins</title>
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
        <a class="btn" href="register.php">Add Admin</a>
      </div>
      <table>
        <thead><tr><th>#</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($admins as $a): ?>
          <tr>
            <td><?=htmlspecialchars($a['admin_id'])?></td>
            <td><?=htmlspecialchars($a['username'])?></td>
            <td><?=htmlspecialchars($a['email'] ?? '')?></td>
            <td><?=htmlspecialchars($a['role'] ?? 'admin')?></td>
            <td><?=htmlspecialchars($a['created_at'])?></td>
            <td><?= $a['is_active'] ? 'Active' : 'Blocked' ?></td>
            <td>
              <a class="btn" href="register.php?admin_id=<?=urlencode($a['admin_id'])?>">Edit</a>
              <?php if ($a['is_active']): ?>
                <a class="btn" href="toggle_admin.php?admin_id=<?=urlencode($a['admin_id'])?>&action=block" onclick="return confirm('Block this admin?')">Block</a>
              <?php else: ?>
                <a class="btn" href="toggle_admin.php?admin_id=<?=urlencode($a['admin_id'])?>&action=unblock">Unblock</a>
              <?php endif; ?>
              <a class="btn danger" href="delete_admin.php?admin_id=<?=urlencode($a['admin_id'])?>" onclick="return confirm('Delete this admin?')">Delete</a>
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
