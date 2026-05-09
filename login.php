<?php
include "db.php";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header("Location: auth.php?msg=empty_fields&type=error");
        exit();
    }

    $stmt = $conn->prepare("SELECT userid, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['uid'] = $row['userid'];
            $_SESSION['uname'] = $row['username'];
            header("Location: shop.php");
            exit();
        } else {
            header("Location: auth.php?msg=wrong_pass&type=error");
            exit();
        }
    } else {
        header("Location: auth.php?msg=no_user&type=error");
        exit();
    }
}
?>