<?php
include "db.php";
include "auth_check.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['total_amount'])) {

    $uid = $_SESSION['uid'];
    $total = $_POST['total_amount'];

    // --- STEP 1: CREATE ORDER ---
    $stmt = $conn->prepare(
        "INSERT INTO orders (userid, total_amount, status) 
         VALUES (?, ?, 'Pending')"
    );

    $stmt->bind_param("id", $uid, $total);

    if ($stmt->execute()) {

        // Get new order id
        $order_id = $conn->insert_id;

        // --- STEP 2: STORE ORDER ITEMS FROM CART ---
        $cstmt = $conn->prepare('SELECT c.spid, c.qty, sp.name, sp.price FROM cart c LEFT JOIN shop_products sp ON c.spid = sp.spid WHERE c.userid = ?');
        $cstmt->bind_param('i', $uid);
        $cstmt->execute();
        $cres = $cstmt->get_result();
        if ($cres) {
            $ins = $conn->prepare('INSERT INTO order_items (order_id, spid, name, price, qty, total) VALUES (?, ?, ?, ?, ?, ?)');
            while ($ci = $cres->fetch_assoc()) {
                $spid = isset($ci['spid']) ? (int)$ci['spid'] : null;
                $qty = (int)($ci['qty'] ?? 1);
                $price = (float)($ci['price'] ?? 0.00);
                $name = $ci['name'] ?? 'Item';
                $total_item = $price * $qty;
                $ins->bind_param('iisdid', $order_id, $spid, $name, $price, $qty, $total_item);
                $ins->execute();
            }
            if (isset($ins) && $ins) $ins->close();
        }

        // --- STEP 3: CLEAR USER CART ---
        $deleteCart = $conn->prepare(
            "DELETE FROM cart WHERE userid=?"
        );
        $deleteCart->bind_param("i", $uid);
        $deleteCart->execute();

        // --- STEP 4: SUCCESS MESSAGE ---
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <link href='https://fonts.googleapis.com/css2?family=Poppins&display=swap' rel='stylesheet'>
            <style>
                body{
                    font-family:'Poppins';
                    background:#0f172a;
                }
            </style>
        </head>

        <body>
        <script>
            Swal.fire({
                title: 'Order Placed!',
                text: 'Order #$order_id has been received successfully.',
                icon: 'success',
                background: '#1e293b',
                color: '#fff',
                confirmButtonColor: '#6366f1',
                confirmButtonText: 'View My Orders'
            }).then(() => {
                window.location.href='profile.php';
            });
        </script>
        </body>
        </html>";

    } else {
        echo "Error placing order.";
    }

} else {
    header("Location: checkout.php");
}
?>