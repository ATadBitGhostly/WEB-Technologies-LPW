<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['isAdmin'] == 1) {
    header('Location: admin/admin_dashboard.php');
} else {
    header('Location: user_dashboard.php');
}
exit;
?>