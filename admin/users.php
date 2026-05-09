<?php
require_once __DIR__ . '/inc/auth.php';
include_once __DIR__ . '/../db.php';
require_admin();

$users = [];
$res = $conn->query('SELECT userid, username, email, created_at, IFNULL(is_active,1) AS is_active FROM users ORDER BY userid DESC');
if ($res) { while ($r = $res->fetch_assoc()) $users[] = $r; }

$pageTitle = 'Users';
$pageSubtitle = 'Block, unblock, and manage customer accounts';
$activePage = 'users.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Users</title>
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
        <thead><tr><th>#</th><th>Username</th><th>Email</th><th>Joined</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
            <td><?=htmlspecialchars($u['userid'])?></td>
            <td><?=htmlspecialchars($u['username'])?></td>
            <td><?=htmlspecialchars($u['email'])?></td>
            <td><?=htmlspecialchars($u['created_at'])?></td>
            <td><?= $u['is_active'] ? 'Active' : 'Blocked' ?></td>
            <td>
              <?php if ($u['is_active']): ?>
                <a class="btn" href="toggle_user.php?userid=<?=urlencode($u['userid'])?>&action=block" onclick="return confirm('Block this user?')">Block</a>
              <?php else: ?>
                <a class="btn" href="toggle_user.php?userid=<?=urlencode($u['userid'])?>&action=unblock">Unblock</a>
              <?php endif; ?>
              <a class="btn danger" href="delete_user.php?userid=<?=urlencode($u['userid'])?>" onclick="return confirm('Delete this user and all related data?')">Delete</a>
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
