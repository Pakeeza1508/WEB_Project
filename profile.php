<?php
include "db.php";
include "auth_check.php";

$uid = $_SESSION['uid'];

// 1. Fetch User Info
$user = [
    'email' => '',
    'created_at' => null,
];

$user_stmt = $conn->prepare('SELECT email, created_at FROM users WHERE userid = ? LIMIT 1');
$user_stmt->bind_param('i', $uid);
$user_stmt->execute();
$user_res = $user_stmt->get_result();
if ($user_res && $user_res->num_rows > 0) {
    $user = $user_res->fetch_assoc();
}

$emailText = !empty($user['email']) ? $user['email'] : 'Email not available';
$memberSinceText = 'N/A';
if (!empty($user['created_at'])) {
    $ts = strtotime($user['created_at']);
    if ($ts !== false) {
        $memberSinceText = date('M Y', $ts);
    }
}

// 2. Fetch Wishlist Items (Integrated Logic)
$wish_query = "SELECT w.wid, w.spid, w.item_type,
              COALESCE(p.name, sp.name) AS p_name, 
              COALESCE(p.price, sp.price) AS p_price, 
              COALESCE(p.image, sp.image) AS p_image
              FROM wishlist w
              LEFT JOIN products p ON w.spid = p.pid AND w.item_type = 'featured'
              LEFT JOIN shop_products sp ON w.spid = sp.spid AND w.item_type = 'catalog'
              WHERE w.userid = $uid";
$wish_res = $conn->query($wish_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account | LuxeStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #6366f1; --dark: #0f172a; --glass: rgba(255, 255, 255, 0.05); --glass-border: rgba(255, 255, 255, 0.1); }
        body { background: var(--dark); color: white; font-family: 'Poppins'; margin: 0; }
        
        .profile-container { padding: 50px 8%; }
        
        /* HEADER CARD */
        .user-card {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(15, 23, 42, 0.9));
            border-radius: 30px; border: 1px solid var(--glass-border);
            padding: 40px; display: flex; align-items: center; gap: 40px;
            margin-bottom: 50px; position: relative; overflow: hidden;
        }
        .user-avatar-lg { width: 120px; height: 120px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 4rem; box-shadow: 0 0 30px rgba(99, 102, 241, 0.4); }
        .user-details h1 { font-size: 2.5rem; margin: 0; }
        .user-details p { opacity: 0.6; margin: 5px 0; }
        
        .logout-link { position: absolute; top: 30px; right: 40px; color: #ef4444; text-decoration: none; font-weight: 600; font-size: 0.9rem; }

        /* WISHLIST SECTION */
        .section-title { font-size: 1.8rem; margin-bottom: 30px; display: flex; align-items: center; gap: 15px; }
        .section-title i { color: var(--primary); }

        .wish-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px; }
        .wish-card { 
            background: var(--glass); border-radius: 20px; border: 1px solid var(--glass-border); 
            padding: 15px; transition: 0.4s; position: relative; 
        }
        .wish-card:hover { transform: translateY(-10px); border-color: var(--primary); }
        
        .img-box { background: white; border-radius: 15px; height: 180px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .img-box img { max-width: 80%; max-height: 80%; object-fit: contain; }

        .remove-btn { 
            position: absolute; top: 10px; right: 10px; 
            background: #ef4444; color: white; width: 30px; height: 30px; 
            border-radius: 50%; display: flex; align-items: center; 
            justify-content: center; text-decoration: none; font-size: 0.8rem;
        }

        .wish-info { margin-top: 15px; text-align: center; }
        .wish-info h3 { font-size: 1rem; margin-bottom: 5px; }
        .wish-price { color: var(--primary); font-weight: bold; }

        .btn-move-cart { 
            width: 100%; margin-top: 15px; padding: 10px; 
            background: var(--primary); color: white; border: none; 
            border-radius: 10px; cursor: pointer; transition: 0.3s;
        }
        .btn-move-cart:hover { background: #4f46e5; }
    </style>
</head>
<body>

    <?php include "navbar.php"; ?>

    <div class="profile-container">
        <!-- USER INFO -->
        <div class="user-card">
            <a href="logout.php" class="logout-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M0 256C0 114.62 114.62 0 256 0s256 114.62 256 256-114.62 256-256 256S0 397.38 0 256zm280 48v-30.75c0-15.2-9.39-28.64-23.52-34.51l-26.5-9.85c-5.3-1.97-8.98-7.08-8.98-12.88V152c0-8.84 7.16-16 16-16h24c8.84 0 16 7.16 16 16v64.01c0 7.88 4.48 15.1 11.5 18.47l26.5 9.86c23.64 8.8 39.98 31.74 39.98 57.21V304c0 17.67-14.33 32-32 32h-45.02c-8.84 0-16-7.16-16-16zm72-80H224c-17.67 0-32 14.33-32 32v16c0 17.67 14.33 32 32 32h128c17.67 0 32-14.33 32-32v-16c0-17.67-14.33-32-32-32z"/></svg> Logout</a>
            <div class="user-avatar-lg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="48" height="48" fill="currentColor" aria-hidden="true"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm89.6 32h-16.7c-22.2 10.4-46.8 16-72.9 16s-50.7-5.6-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"/></svg>
            </div>
            <div class="user-details">
                <p>Welcome Back,</p>
                <h1><?= htmlspecialchars($_SESSION['uname']) ?></h1>
                <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M502.3 190.8L327.4 338.3c-15.4 13.1-37.6 13.1-52.9 0L9.7 190.8C3.5 185.4 0 177.7 0 169.5V80c0-26.5 21.5-48 48-48h416c26.5 0 48 21.5 48 48v89.5c0 8.2-3.5 15.9-9.7 21.3zM480 128L320 272 160 128H48L256 278.6 464 128h-16zM0 208v224c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V208l-176 148.8c-24.5 20.8-58.8 20.8-83.3 0L0 208z"/></svg> <?= htmlspecialchars($emailText) ?></p>
                <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M152 64H296c13.3 0 24 10.7 24 24v40h48c26.5 0 48 21.5 48 48v48H0v-48c0-26.5 21.5-48 48-48h48V88c0-13.3 10.7-24 24-24zm-8 192h224v208c0 26.5-21.5 48-48 48H192c-26.5 0-48-21.5-48-48V256zm32 48v112h160V304H176z"/></svg> Member since <?= htmlspecialchars($memberSinceText) ?></p>
            </div>
        </div>

        <!-- WISHLIST INTEGRATED -->
        <h2 class="section-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M512 111.1c0 57.6-27.5 111.1-62.2 151.8-29.5 34.9-61.8 64.1-99.3 94.2C305.7 380.1 265.9 408.6 256 418c-9.9-9.4-49.7-37.9-94.5-60.9-37.5-30.1-69.8-59.3-99.3-94.2C27.5 222.2 0 168.7 0 111.1 0 49.8 49.8 0 111.1 0 143.7 0 174 15.4 192 40.6 210 15.4 240.3 0 272.9 0 334.2 0 384 49.8 384 111.1c0 57.6-27.5 111.1-62.2 151.8z"/></svg> My Wishlist <span>(<?= $wish_res->num_rows ?>)</span></h2>
        
        <div class="wish-grid">
            <?php if ($wish_res->num_rows > 0): ?>
                <?php while ($row = $wish_res->fetch_assoc()): ?>
                <div class="wish-card">
                    <a href="wishlist_logic.php?remove_wish=<?= $row['wid'] ?>" class="remove-btn" title="Remove">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="12" height="12" fill="currentColor" aria-hidden="true"><path d="M242.7 256l100.1-100.1c12.5-12.5 12.5-32.8 0-45.3L286.3 25.7c-12.5-12.5-32.8-12.5-45.3 0L141 125.7 40.9 25.7c-12.5-12.5-32.8-12.5-45.3 0L-2.4 110.6c-12.5 12.5-12.5 32.8 0 45.3L97.7 256-2.4 356.1c-12.5 12.5-12.5 32.8 0 45.3l53.5 53.5c12.5 12.5 32.8 12.5 45.3 0L141 386.3l100.1 100.1c12.5 12.5 32.8 12.5 45.3 0l53.5-53.5c12.5-12.5 12.5-32.8 0-45.3L242.7 256z"/></svg>
                    </a>
                    <div class="img-box">
                        <img src="<?= $row['p_image'] ?>">
                    </div>
                    <div class="wish-info">
                        <h3><?= $row['p_name'] ?></h3>
                        <p class="wish-price">Rs <?= number_format($row['p_price']) ?></p>
                        
                        <form method="post" action="add_to_cart.php">
                            <!-- Correct ID based on type -->
                            <input type="hidden" name="<?= ($row['item_type'] == 'featured') ? 'pid' : 'spid' ?>" value="<?= $row['spid'] ?>">
                            <button class="btn-move-cart">Add to Cart</button>
                        </form>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 50px; opacity: 0.3;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="48" height="48" fill="currentColor" aria-hidden="true"><path d="M462.3 62.6c-54.5-46.4-136-38.4-186.4 13.7L256 96.5l-19.9-20.2C185.7 24.2 104.2 16.2 49.7 62.6-16.3 130.3-10.6 241.5 43 295.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0L469 295.9c53.6-54.4 59.3-165.6-6.7-233.3z"/></svg>
                    <p>Your wishlist is empty. Start saving items you love!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>