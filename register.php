<?php
include "db.php";

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: auth.php?msg=empty_fields&type=error");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: auth.php?msg=invalid_email&type=error");
        exit();
    }

    // Check if exists
    $stmt = $conn->prepare("SELECT userid FROM users WHERE email=? OR username=?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: auth.php?msg=user_exists&type=error");
        exit();
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $username, $email, $hashed_password);

        if ($insert->execute()) {
            header("Location: auth.php?msg=registered&type=success");
            exit();
        }
    }
}
?>