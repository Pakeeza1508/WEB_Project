<?php
include "db.php";
include "auth_check.php";

// 1. HANDLE QUANTITY UPDATE
if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $new_qty = (int)$_POST['qty']; // Convert to number for safety

    if ($new_qty > 0) {
        // Update the quantity in the database
        $stmt = $conn->prepare("UPDATE cart SET qty = ? WHERE cart_id = ?");
        $stmt->bind_param("ii", $new_qty, $cart_id);
        $stmt->execute();
    } else {
        // If qty is 0 or less, just remove the item
        $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
    }
    
    // Always redirect back to cart.php
    header("Location: cart.php?msg=updated");
    exit();
}

// 2. HANDLE DELETE (Via URL Link)
if (isset($_GET['delete_id'])) {
    $cart_id = $_GET['delete_id'];
    $userid = $_SESSION['uid'];

    // Delete only if it belongs to the logged-in user
    $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND userid = ?");
    $stmt->bind_param("ii", $cart_id, $userid);
    $stmt->execute();

    header("Location: cart.php?msg=removed");
    exit();
}

// 3. FALLBACK (If someone visits the page directly without clicking a button)
header("Location: cart.php");
exit();
?>