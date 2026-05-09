<?php
// Navbar partial for LuxeStore
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($conn) || !($conn instanceof mysqli)) {
    include_once __DIR__ . '/db.php';
}

$uid = isset($_SESSION['uid']) ? (int) $_SESSION['uid'] : 0;
$uname = isset($_SESSION['uname']) ? htmlspecialchars($_SESSION['uname'], ENT_QUOTES, 'UTF-8') : 'Guest';

if (!function_exists('getHeaderCount')) {
    function getHeaderCount(mysqli $conn, int $uid): int {
        if ($uid <= 0) {
            return 0;
        }

        $sql = 'SELECT IFNULL(SUM(qty), 0) AS total FROM cart WHERE userid = ?';
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return (int) ($row['total'] ?? 0);
    }
}

$c_count = getHeaderCount($conn, $uid);
$profileLink = $uid > 0 ? 'profile.php' : 'login.php';
?>

<nav class="navbar">
    <a href="shop.php" class="logo">LUXE<span>STORE</span></a>

    <div class="nav-links">
        <a href="shop.php" class="nav-item">Home</a>
        <a href="all-products.php" class="nav-item">Shop</a>

        <a href="cart.php" class="nav-icon-link" aria-label="Cart">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM7.3 6H19a1 1 0 0 0 .96-.72l1.5-5A1 1 0 0 0 20.5 0H5.2L4.6.55 1.6 4.26A1 1 0 0 0 2.5 6h4.8z"/>
            </svg>
            <?php if ($c_count > 0): ?>
                <span class="badge"><?= $c_count ?></span>
            <?php endif; ?>
        </a>

        <a href="<?= $profileLink ?>" class="user-pill">
            <div class="avatar-sm" aria-hidden="true">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm-7 19a7 7 0 0 1 14 0h-2a5 5 0 0 0-10 0H5z"/>
                </svg>
            </div>
            <span class="display-name"><?= $uname ?></span>
        </a>
    </div>
</nav>

<style>
:root {
    --primary: #6366f1;
    --glass-border: rgba(255, 255, 255, 0.12);
}

.navbar {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: rgba(15, 23, 42, 0.94);
    backdrop-filter: blur(14px);
    padding: 15px 6%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--glass-border);
}

.logo {
    font-size: 24px;
    font-weight: 700;
    color: white;
    text-decoration: none;
}

.logo span {
    color: var(--primary);
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 25px;
    flex-wrap: wrap;
}

.nav-item,
.nav-icon-link,
.user-pill {
    color: white;
    text-decoration: none;
}

.nav-item {
    font-size: 14px;
    opacity: 0.85;
    transition: 0.3s;
}

.nav-item:hover {
    opacity: 1;
    color: var(--primary);
}

.nav-icon-link {
    position: relative;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
}

.nav-icon-link:hover {
    color: var(--primary);
}

.nav-icon-link svg,
.avatar-sm svg {
    width: 18px;
    height: 18px;
    display: block;
    fill: white;
}

.badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: var(--primary);
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 999px;
    font-weight: 700;
    border: 2px solid rgba(15, 23, 42, 0.9);
}

.user-pill {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.06);
    padding: 6px 14px 6px 8px;
    border-radius: 999px;
    border: 1px solid var(--glass-border);
    transition: 0.3s;
}

.user-pill:hover {
    background: rgba(99, 102, 241, 0.16);
}

.avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
}

.display-name {
    white-space: nowrap;
}
</style>
