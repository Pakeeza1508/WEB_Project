<?php
include "db.php";
include "auth_check.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | LuxeStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #6366f1; --dark: #0f172a; --glass: rgba(255, 255, 255, 0.05); }
        body { background: var(--dark); color: white; font-family: 'Poppins'; padding: 40px 6%; }
        
        .cart-container { max-width: 1100px; margin: auto; }
        h1 { margin-bottom: 30px; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 10px; }

        table { width: 100%; border-collapse: collapse; background: var(--glass); border-radius: 20px; overflow: hidden; margin-top: 20px; }
        th { background: rgba(255,255,255,0.1); padding: 20px; text-align: left; color: var(--primary); text-transform: uppercase; font-size: 13px; letter-spacing: 1px; }
        td { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; }
        
        .product-info { display: flex; align-items: center; gap: 15px; }
        .product-info img { width: 70px; height: 70px; object-fit: contain; background: white; border-radius: 12px; padding: 5px; }
        .product-info span { font-weight: 600; }
        
        input[type="number"] { width: 60px; padding: 8px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.3); color: white; text-align: center; }
        
        .btn-update { background: #10b981; color: white; border: none; padding: 10px; border-radius: 8px; cursor: pointer; transition: 0.3s; }
        .btn-update:hover { background: #059669; }
        .btn-delete { background: #ef4444; color: white; border: none; padding: 10px; border-radius: 8px; cursor: pointer; transition: 0.3s; text-decoration: none; display: inline-block; }
        .btn-delete:hover { background: #dc2626; }
        
        .cart-summary { margin-top: 40px; background: var(--glass); padding: 40px; border-radius: 30px; text-align: right; border: 1px solid rgba(255,255,255,0.1); }
        .grand-total { font-size: 2.5rem; font-weight: bold; color: var(--primary); margin-bottom: 20px; }
        .checkout-btn { padding: 18px 50px; background: var(--primary); color: white; border: none; border-radius: 50px; font-weight: bold; cursor: pointer; transition: 0.3s; font-size: 1.1rem; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3); }
        .checkout-btn:hover { background: #4f46e5; transform: translateY(-5px); }
    </style>
</head>
<body>

<div class="cart-container">
    <a href="all-products.php" style="color: white; text-decoration: none; opacity: 0.6; font-size: 14px;"><i class="fa fa-arrow-left"></i> BACK TO SHOP</a>
    <br><br>
    <h1>Your Shopping Bag</h1>

    <table>
        <thead>
            <tr>
                <th>Product Details</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $uid = $_SESSION['uid'];
            $grand_total = 0;

            // DUAL JOIN LOGIC: Fetches data from 'products' OR 'shop_products' based on item_type
            $query = "SELECT c.cart_id, c.qty, c.item_type,
                      COALESCE(p.name, sp.name) AS p_name, 
                      COALESCE(p.price, sp.price) AS p_price, 
                      COALESCE(p.image, sp.image) AS p_image
                      FROM cart c
                      LEFT JOIN products p ON c.spid = p.pid AND c.item_type = 'featured'
                      LEFT JOIN shop_products sp ON c.spid = sp.spid AND c.item_type = 'catalog'
                      WHERE c.userid = $uid";

            $res = $conn->query($query);

            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $subtotal = $row['p_price'] * $row['qty'];
                    $grand_total += $subtotal;
            ?>
            <tr>
                <td>
                    <div class="product-info">
                        <img src="<?= $row['p_image'] ?>">
                        <div>
                            <span><?= $row['p_name'] ?></span>
                            <small style="display:block; opacity:0.4; font-size:10px;"><?= strtoupper($row['item_type']) ?> COLLECTION</small>
                        </div>
                    </div>
                </td>
                <td>Rs <?= number_format($row['p_price']) ?></td>
                <td>
                    <form action="manage_cart.php" method="POST" style="display: flex; gap: 8px;">
                        <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
                        <input type="number" name="qty" value="<?= $row['qty'] ?>" min="1">
                        <button name="update_qty" class="btn-update"><i class="fa fa-sync"></i></button>
                    </form>
                </td>
                <td><b style="color:white;">Rs <?= number_format($subtotal) ?></b></td>
                <td>
                    <!-- Uses delete_id which matches manage_cart.php logic -->
                    <a href="manage_cart.php?delete_id=<?= $row['cart_id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to remove this item?')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php 
                } 
            } else { 
                echo "<tr><td colspan='5' style='text-align:center; padding:80px; opacity:0.5;'><h3>Your cart is currently empty.</h3><a href='all-products.php' style='color:var(--primary);'>Go shop now</a></td></tr>"; 
            } 
            ?>
        </tbody>
    </table>

    <?php if($grand_total > 0): ?>
    <div class="cart-summary">
        <p style="opacity: 0.5; margin-bottom: 5px;">Grand Total</p>
        <div class="grand-total">Rs <?= number_format($grand_total) ?></div>
        <div style="margin: 15px 0; padding: 15px; background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.3); border-radius: 15px; text-align: center;">
    <i class="fa fa-truck" style="color: var(--primary); margin-right: 8px;"></i>
    <span style="font-weight: 600;">Estimated Delivery:</span>
    <span style="color: #10b981; font-weight: 600;">
        <?php 
            echo date('d M', strtotime('+3 days')) . " - " . date('d M', strtotime('+5 days'));
        ?>
    </span>
    <br>
    <small style="opacity: 0.6;">Fast delivery across Pakistan 🇵🇰</small>
</div>
        <form action="checkout.php" method="POST">
            <input type="hidden" name="total" value="<?= $grand_total ?>">
            <button class="checkout-btn">PROCEED TO CHECKOUT</button>
        </form>
    </div>
    <?php endif; ?>
</div>

</body>
</html>