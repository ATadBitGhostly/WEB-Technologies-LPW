<?php
require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Service.php';

$db = new Database();
$conn = $db->connect();
$serviceManager = new Service($conn);

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    try {
        $serviceManager->delete($_GET['delete_id']);
        header("Location: dashboard.php?msg=Deleted");
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$services = $serviceManager->readAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Dashboard</title>
</head>
<body>
    <h2>Service Management</h2>
    <a href="add_service.php">Add New Service</a>
    <hr>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Image Path</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['id']) ?></td>
                <td><?= htmlspecialchars($s['title']) ?></td>
                <td><?= htmlspecialchars($s['description']) ?></td>
                <td>
                    <?php 
                    $imagePath = $s['image'] ?? ''; 

                    if (strlen(trim($imagePath)) > 0): 
                        // Standardize slashes: turn any backslashes into forward slashes
                        $cleanPath = str_replace('\\', '/', $imagePath);
                        
                        // Path construction
                        // Current: /html/admin/dashboard.php
                        // Target:  /images/news.png
                        // Need to go up two levels: ../../
                        $src = "../../" . ltrim($cleanPath, '/');
                    ?>
                        <img src="<?= htmlspecialchars($src) ?>" 
                            alt="Service Image" 
                            style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                    <?php else: ?>
                        <span style="color: #999;">No Image Path</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_service.php?id=<?= $s['id'] ?>">Edit</a> | 
                    <a href="dashboard.php?delete_id=<?= $s['id'] ?>" onclick="return confirm('Delete this service?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>