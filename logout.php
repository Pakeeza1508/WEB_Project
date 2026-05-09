<?php
session_start();
session_destroy();

// Delete the cookies by setting expiration to the past
setcookie("user_login", "", time() - 3600, "/");
setcookie("user_name", "", time() - 3600, "/");

header("Location: auth.php");
exit();
?>