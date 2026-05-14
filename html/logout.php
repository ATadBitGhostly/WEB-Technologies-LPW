<?php
session_start();
session_destroy();

setcookie('remember_user', '', time() - 3600, '/');
setcookie('last_login', '', time() - 3600, '/');

header('Location: login.php');
exit;
?>
