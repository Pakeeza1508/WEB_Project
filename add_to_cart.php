<?php
include "db.php";
include "auth_check.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userid = $_SESSION['uid'];
    
    // 1. Identify source and ID
    if (isset($_POST['pid'])) {
        $product_id = $_POST['pid'];
        $type = 'featured'; 
    } else if (isset($_POST['spid'])) {
        $product_id = $_POST['spid'];
        $type = 'catalog';
    } else {
        header("Location: shop.php");
        exit();
    }

    // 2. Check if this exact item type and ID already exists in the cart
    // Note: We use 'spid' column in the cart table to store both types of IDs
    $check = $conn->prepare("SELECT cart_id FROM cart WHERE userid = ? AND spid = ? AND item_type = ?");
    $check->bind_param("iis", $userid, $product_id, $type);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        // 3. Update quantity if exists
        $row = $res->fetch_assoc();
        $cid = $row['cart_id'];
        $conn->query("UPDATE cart SET qty = qty + 1 WHERE cart_id = $cid");
    } else {
        // 4. Insert new row
        $insert = $conn->prepare("INSERT INTO cart (userid, spid, qty, item_type) VALUES (?, ?, 1, ?)");
        $insert->bind_param("iis", $userid, $product_id, $type);
        $insert->execute();
    }

    header("Location: cart.php");
    exit();
}
?>