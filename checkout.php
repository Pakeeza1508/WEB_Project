<?php
include "db.php";
include "auth_check.php";
include "navbar.php";

$uid = $_SESSION['uid'];
$grand_total = 0;

// Fetch Cart for Summary
$query = "SELECT c.qty, c.spid, c.item_type,
          COALESCE(p.name, sp.name) AS p_name, 
          COALESCE(p.price, sp.price) AS p_price, 
          COALESCE(p.image, sp.image) AS p_image
          FROM cart c
          LEFT JOIN products p ON c.spid = p.pid AND c.item_type = 'featured'
          LEFT JOIN shop_products sp ON c.spid = sp.spid AND c.item_type = 'catalog'
          WHERE c.userid = $uid";
$cart_res = $conn->query($query);

if ($cart_res->num_rows == 0) {
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Checkout | LuxeStore</title>
    <style>
        :root { --primary: #6366f1; --dark: #0f172a; --glass: rgba(255, 255, 255, 0.05); }
        body { background: var(--dark); color: white; font-family: 'Poppins'; margin: 0; }
        
        .checkout-wrapper { display: grid; grid-template-columns: 1fr 400px; gap: 40px; padding: 50px 8%; }

        /* LEFT SIDE: FORM */
        .checkout-form-section { background: var(--glass); padding: 40px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.1); }
        h2 { margin-bottom: 25px; font-size: 1.5rem; display: flex; align-items: center; gap: 10px; }
        h2 i { color: var(--primary); }

        .input-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .full-width { grid-column: span 2; }
        
        label { display: block; font-size: 0.8rem; margin-bottom: 8px; opacity: 0.6; }
        input, select { width: 100%; padding: 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2); color: white; outline: none; }
        input:focus { border-color: var(--primary); }

        /* PAYMENT METHODS */
        .payment-methods { display: flex; gap: 15px; margin-top: 15px; }
        .method-card { flex: 1; padding: 20px; border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; text-align: center; cursor: pointer; transition: 0.3s; }
        .method-card i { font-size: 1.5rem; display: block; margin-bottom: 10px; }
        .method-card.active { border-color: var(--primary); background: rgba(99, 102, 241, 0.1); }

        /* RIGHT SIDE: SUMMARY */
        .order-summary { background: #1e293b; padding: 30px; border-radius: 30px; height: fit-content; position: sticky; top: 100px; }
        .summary-item { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        .summary-item img { width: 50px; height: 50px; background: white; border-radius: 8px; object-fit: contain; }
        .summary-item div { flex: 1; }
        .summary-item b { font-size: 0.9rem; }
        
        .total-row { display: flex; justify-content: space-between; margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .place-order-btn { width: 100%; margin-top: 30px; padding: 18px; background: var(--primary); color: white; border: none; border-radius: 15px; font-weight: bold; cursor: pointer; font-size: 1rem; transition: 0.3s; }
        .place-order-btn:hover { background: #4f46e5; transform: translateY(-3px); }
    </style>
</head>
<body>

<form action="place_order.php" method="POST">
    <div class="checkout-wrapper">
        <!-- FORM SECTION -->
        <div class="checkout-form-section">
            
            <h2><i class="fa fa-truck"></i> Shipping Address</h2>
            <div class="input-grid">
                <div class="full-width">
                    <label>Full Name</label>
                    <input type="text" name="full_name" required value="<?= $_SESSION['uname'] ?>">
                </div>
                <div>
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="+92 ..." required>
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@example.com" required>
                </div>
                <div class="full-width">
                    <label>Complete Address</label>
                    <input type="text" name="address" placeholder="House #, Street, Area..." required>
                </div>
                <div>
                    <label>City</label>
                    <input type="text" name="city" required>
                </div>
                <div>
                    <label>Zip Code</label>
                    <input type="text" name="zip" required>
                </div>
            </div>

            <h2><i class="fa fa-credit-card"></i> Payment Method</h2>
            <div class="payment-methods">
                <div class="method-card active" onclick="selectPayment('COD', this)">
                    <input type="radio" name="payment_method" value="COD" checked style="display:none;">
                    <i class="fa fa-hand-holding-dollar"></i>
                    Cash on Delivery
                </div>
                <div class="method-card" onclick="selectPayment('CARD', this)">
                    <input type="radio" name="payment_method" value="CARD" style="display:none;">
                    <i class="fa fa-credit-card"></i>
                    Credit Card
                </div>
            </div>
            
            <!-- Dynamic Card Fields (Hidden by default) -->
            <div id="card-fields" style="display:none; margin-top:20px;" class="input-grid">
                <div class="full-width">
                    <label>Card Number</label>
                    <input type="text" placeholder="xxxx xxxx xxxx xxxx">
                </div>
                <div><label>Expiry</label><input type="text" placeholder="MM/YY"></div>
                <div><label>CVC</label><input type="text" placeholder="***"></div>
            </div>

        </div>

        <!-- SUMMARY SECTION -->
        <div class="order-summary">
            <h3>Order Summary</h3>
            <hr border="1" color="rgba(255,255,255,0.05)">
            <br>
            
            <?php while($item = $cart_res->fetch_assoc()): 
                $sub = $item['p_price'] * $item['qty'];
                $grand_total += $sub;
            ?>
            <div class="summary-item">
                <img src="<?= $item['p_image'] ?>">
                <div>
                    <b><?= $item['p_name'] ?></b><br>
                    <small>Qty: <?= $item['qty'] ?></small>
                </div>
                <span>Rs <?= number_format($sub) ?></span>
            </div>
            <?php endwhile; ?>

            <div class="total-row">
                <span>Subtotal</span>
                <span>Rs <?= number_format($grand_total) ?></span>
            </div>
            <div class="total-row">
                <span>Shipping</span>
                <span style="color: #10b981;">FREE</span>
            </div>
            <div class="total-row" style="font-size: 1.4rem; font-weight: bold; color: var(--primary);">
                <span>Total</span>
                <span>Rs <?= number_format($grand_total) ?></span>
            </div>

            <input type="hidden" name="total_amount" value="<?= $grand_total ?>">
            <button type="submit" class="place-order-btn">PLACE ORDER NOW</button>
            <p style="text-align: center; font-size: 0.7rem; opacity: 0.5; margin-top: 15px;">
                <i class="fa fa-lock"></i> SSL Encrypted Secure Checkout
            </p>
        </div>
    </div>
</form>

<script>
    function selectPayment(type, el) {
        // Toggle Active Class
        document.querySelectorAll('.method-card').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        
        // Select Hidden Radio
        el.querySelector('input').checked = true;

        // Show/Hide Card Fields
        const cardFields = document.getElementById('card-fields');
        cardFields.style.display = (type === 'CARD') ? 'grid' : 'none';
    }
</script>

</body>
</html>