<?php
require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Service.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $serviceManager = new Service($db->connect());
    
    try {
        $serviceManager->create($_POST['title'], $_POST['description'], $_POST['image']);
        header("Location: ../dashboard.php");
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Service</title></head>
<body>
    <h2>Add New Service</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Service Title" required><br><br>
        <textarea name="description" placeholder="Description" required></textarea><br><br>
        <input type="text" name="image" placeholder="Image URL/Path"><br><br>
        <button type="submit">Create Service</button>
    </form>
    <a href="../dashboard.php">Back to Dashboard</a>
</body>
</html>