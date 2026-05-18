<?php
session_start();
session_destroy();

// Clear all cookies
setcookie("remember_user", "", time() - 3600, "/");
setcookie("last_login", "", time() - 3600, "/");
setcookie("last_visit", "", time() - 3600, "/");
setcookie("welcome", "", time() - 3600, "/");

header('Location: index.php');
exit;
?>
