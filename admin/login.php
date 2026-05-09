<?php
include_once __DIR__ . '/../db.php';

// Ensure admin table exists for fresh databases.
$conn->query("CREATE TABLE IF NOT EXISTS admin_users (
  admin_id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(150) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'admin',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (admin_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Normalize schema if table existed with older structure.
$conn->query("ALTER TABLE admin_users ADD COLUMN IF NOT EXISTS email VARCHAR(150) DEFAULT NULL");
$conn->query("ALTER TABLE admin_users ADD COLUMN IF NOT EXISTS role VARCHAR(50) DEFAULT 'admin'");
$conn->query("ALTER TABLE admin_users ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
$conn->query("ALTER TABLE admin_users MODIFY username VARCHAR(100) NOT NULL UNIQUE");
$conn->query("ALTER TABLE admin_users MODIFY password VARCHAR(255) NOT NULL");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please fill both fields.';
    } else {
        $stmt = $conn->prepare('SELECT admin_id, username, password FROM admin_users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_id'] = $row['admin_id'];
                $_SESSION['admin_user'] = $row['username'];
                header('Location: index.php');
                exit();
            } else {
                $error = 'Invalid credentials.';
            }
        } else {
            $error = 'Invalid credentials.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/admin.css">
  <style>
    :root{--primary:#6366f1;--bg:#0f172a}
    body{font-family:'Poppins',sans-serif;background:var(--bg);color:#fff;display:flex;align-items:center;justify-content:center;height:100vh;margin:0}
    .card{background:rgba(255,255,255,0.04);padding:30px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.4);width:360px}
    input{width:100%;padding:12px;margin:8px 0;border-radius:8px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:#fff}
    button{width:100%;padding:12px;border-radius:8px;border:none;background:var(--primary);color:#fff;font-weight:600;cursor:pointer}
    .error{background:#ff4d4f;color:#fff;padding:8px;border-radius:6px;margin-bottom:12px;text-align:center}
    .muted{color:rgba(255,255,255,0.6);font-size:13px;text-align:center;margin-top:12px}
  </style>
</head>
<body>
  <div class="card">
    <h2 style="margin:0 0 12px 0">Admin Sign In</h2>
    <?php if (!empty($error)): ?>
      <div class="error"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    <form method="post" action="">
      <input name="username" placeholder="Username" autocomplete="username">
      <input name="password" type="password" placeholder="Password" autocomplete="current-password">
      <button type="submit">Sign In</button>
    </form>
    <div class="muted">New admin? <a href="register.php" style="color:#a5b4fc;text-decoration:none">Create account</a></div>
  </div>
</body>
</html>
