<?php
include_once __DIR__ . '/../db.php';

// Ensure table exists even if seeder was not run yet.
$conn->query("CREATE TABLE IF NOT EXISTS admin_users (
  admin_id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(150) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'admin',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (admin_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$conn->query("ALTER TABLE admin_users ADD COLUMN IF NOT EXISTS email VARCHAR(150) DEFAULT NULL");
$conn->query("ALTER TABLE admin_users ADD COLUMN IF NOT EXISTS role VARCHAR(50) DEFAULT 'admin'");
$conn->query("ALTER TABLE admin_users ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
$conn->query("ALTER TABLE admin_users MODIFY username VARCHAR(100) NOT NULL UNIQUE");
$conn->query("ALTER TABLE admin_users MODIFY password VARCHAR(255) NOT NULL");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($username === '' || $email === '' || $password === '' || $confirmPassword === '') {
        $error = 'Please fill all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Password and confirm password do not match.';
    } else {
        $checkStmt = $conn->prepare('SELECT admin_id FROM admin_users WHERE username = ? OR email = ? LIMIT 1');
        $checkStmt->bind_param('ss', $username, $email);
        $checkStmt->execute();
        $existsRes = $checkStmt->get_result();

        if ($existsRes && $existsRes->num_rows > 0) {
            $error = 'Username or email is already registered as admin.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'admin';
            $insertStmt = $conn->prepare('INSERT INTO admin_users (username, email, password, role) VALUES (?, ?, ?, ?)');
            $insertStmt->bind_param('ssss', $username, $email, $hash, $role);

            if ($insertStmt->execute()) {
                $_SESSION['admin_id'] = $insertStmt->insert_id;
                $_SESSION['admin_user'] = $username;
                header('Location: index.php');
                exit();
            }

            $error = 'Unable to register admin right now. Please try again.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Register</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    :root{--primary:#6366f1;--bg:#0f172a}
    body{font-family:'Poppins',sans-serif;background:var(--bg);color:#fff;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;padding:24px}
    .card{background:rgba(255,255,255,0.04);padding:30px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.4);width:420px;max-width:100%}
    h2{margin:0 0 12px 0}
    p.muted{color:rgba(255,255,255,0.6);font-size:13px;margin-bottom:14px}
    input{width:100%;padding:12px;margin:8px 0;border-radius:8px;border:1px solid rgba(255,255,255,0.12);background:transparent;color:#fff;box-sizing:border-box}
    button{width:100%;padding:12px;border-radius:8px;border:none;background:var(--primary);color:#fff;font-weight:600;cursor:pointer;margin-top:8px}
    .error{background:#ff4d4f;color:#fff;padding:8px;border-radius:6px;margin-bottom:12px;text-align:center}
    .alt{margin-top:14px;text-align:center;font-size:13px;color:rgba(255,255,255,0.65)}
    .alt a{color:#a5b4fc;text-decoration:none}
  </style>
</head>
<body>
  <div class="card">
    <h2>Create Admin Account</h2>
    <p class="muted">Register a new admin who can access the admin panel.</p>

    <?php if (!empty($error)): ?>
      <div class="error"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>

    <form method="post" action="">
      <input name="username" placeholder="Username" autocomplete="username" required>
      <input name="email" type="email" placeholder="Email" autocomplete="email" required>
      <input name="password" type="password" placeholder="Password" autocomplete="new-password" required>
      <input name="confirm_password" type="password" placeholder="Confirm Password" autocomplete="new-password" required>
      <button type="submit">Register Admin</button>
    </form>

    <div class="alt">Already have admin access? <a href="login.php">Sign in</a></div>
  </div>
</body>
</html>
