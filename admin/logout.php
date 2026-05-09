<?php
include_once __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_unset();
session_destroy();
header('Location: login.php');
exit();
