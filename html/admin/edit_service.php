<?php
require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Service.php';

$db = new Database();
$conn = $db->connect();
$serviceManager = new Service($conn);

$id = $_GET['id'] ?? null;
if (!$id) header("Location: dashboard.php");

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $serviceManager->update($id, $_POST['title'], $_POST['description']);
        header("Location: ../dashboard.php");
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch existing data (Simple query for the form)
$stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$current = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head><title>Edit Service</title></head>
<body>
    <h2>Edit Service #<?= htmlspecialchars($id) ?></h2>
    <form method="POST">
        <input type="text" name="title" value="<?= htmlspecialchars($current['title']) ?>" required><br><br>
        <textarea name="description" required><?= htmlspecialchars($current['description']) ?></textarea><br><br>
        <button type="submit">Update Service</button>
    </form>
    <a href="../dashboard.php">Cancel</a>
</body>
</html>