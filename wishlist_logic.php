<?php
include "db.php";
include "auth_check.php";

if (isset($_GET['add_wish'])) {
    $spid = $_GET['add_wish'];
    $type = $_GET['type']; // 'featured' or 'catalog'
    $uid = $_SESSION['uid'];

    // Check if already in wishlist
    $check = $conn->query("SELECT wid FROM wishlist WHERE userid = $uid AND spid = $spid AND item_type = '$type'");
    
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO wishlist (userid, spid, item_type) VALUES ($uid, $spid, '$type')");
    }
    header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect back to wherever user was
}

if (isset($_GET['remove_wish'])) {
    $wid = $_GET['remove_wish'];
    $conn->query("DELETE FROM wishlist WHERE wid = $wid");
    header("Location: wishlist.php");
}
?>