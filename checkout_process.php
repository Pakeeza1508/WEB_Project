<?php
include "db.php";
include "auth_check.php";

if (isset($_POST['total'])) {
    $userid = $_SESSION['uid'];
    $total = $_POST['total'];

    // 1. Insert into Orders Table
    $stmt = $conn->prepare("INSERT INTO orders (userid, total_amount, status) VALUES (?, ?, 'Pending')");
    $stmt->bind_param("id", $userid, $total);
    $stmt->execute();

    $order_id = $conn->insert_id;

    // 2. Move cart items to order_items and deduct stock
    $cstmt = $conn->prepare('SELECT c.spid, c.qty, sp.name, sp.price FROM cart c LEFT JOIN shop_products sp ON c.spid = sp.spid WHERE c.userid = ?');
    $cstmt->bind_param('i', $userid);
    $cstmt->execute();
    $cres = $cstmt->get_result();
    if ($cres) {
        $ins = $conn->prepare('INSERT INTO order_items (order_id, spid, name, price, qty, total) VALUES (?, ?, ?, ?, ?, ?)');
        $stock_update = $conn->prepare('UPDATE shop_products SET stock = stock - ? WHERE spid = ?');
        
        while ($ci = $cres->fetch_assoc()) {
            $spid = isset($ci['spid']) ? (int)$ci['spid'] : null;
            $qty = (int)($ci['qty'] ?? 1);
            $price = (float)($ci['price'] ?? 0.00);
            $name = $ci['name'] ?? 'Item';
            $total_item = $price * $qty;
            
            // Insert into order_items
            $ins->bind_param('iisdid', $order_id, $spid, $name, $price, $qty, $total_item);
            $ins->execute();
            
            // Deduct stock from shop_products
            if ($spid && $qty > 0) {
                $stock_update->bind_param('ii', $qty, $spid);
                $stock_update->execute();
            }
        }
        if (isset($ins) && $ins) $ins->close();
        if (isset($stock_update) && $stock_update) $stock_update->close();
    }

    // 3. Clear user's cart
    $conn->query("DELETE FROM cart WHERE userid = $userid");

    echo "<script>alert('Order Placed Successfully!'); window.location='shop.php';</script>";
}
?>