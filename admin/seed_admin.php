<?php
// Seeder: creates admin_users table (if missing) and inserts a default admin user.
include_once __DIR__ . '/../db.php';

// Create table if not exists
$sql = "CREATE TABLE IF NOT EXISTS admin_users (
  admin_id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(150) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'admin',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (admin_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$conn->query($sql);

// Make sure older versions of the table get the expected columns too.
$conn->query("ALTER TABLE admin_users ADD COLUMN IF NOT EXISTS email VARCHAR(150) DEFAULT NULL");
$conn->query("ALTER TABLE admin_users ADD COLUMN IF NOT EXISTS role VARCHAR(50) DEFAULT 'admin'");
$conn->query("ALTER TABLE admin_users ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
$conn->query("ALTER TABLE admin_users MODIFY password VARCHAR(255) NOT NULL");
$conn->query("ALTER TABLE admin_users MODIFY username VARCHAR(100) NOT NULL UNIQUE");

$defaultUser = 'admin';
$defaultPass = 'admin123';
$hash = password_hash($defaultPass, PASSWORD_DEFAULT);

$stmt = $conn->prepare('INSERT INTO admin_users (username, email, password, role) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE password = VALUES(password)');
$email = 'admin@example.com';
$role = 'super';
$stmt->bind_param('ssss', $defaultUser, $email, $hash, $role);
$ok = $stmt->execute();

if ($ok) {
    echo "Admin user created/updated.<br>Username: <strong>$defaultUser</strong><br>Password: <strong>$defaultPass</strong><br>";
    echo "<a href=\"login.php\">Go to admin login</a><br>";
    echo "<strong>Important:</strong> Delete this file after first use for security.";
} else {
    echo "Error seeding admin user: " . $conn->error;
}
