<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in via Session
if (!isset($_SESSION['uid'])) {
    // If no session, check if a "Remember Me" cookie exists
    if (isset($_COOKIE['user_login'])) {
        $_SESSION['uid'] = $_COOKIE['user_login'];
        $_SESSION['uname'] = $_COOKIE['user_name'];
    } else {
        // No session and no cookie? Back to login page
        header("Location: auth.php");
        exit();
    }
}
?>