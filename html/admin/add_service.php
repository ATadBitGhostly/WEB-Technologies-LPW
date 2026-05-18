<?php
session_start();
require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Service.php';

// Tikai admin var piekļūt
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['user_id']) || $_SESSION['isAdmin'] != 1) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');

    // PHP validācija
    if (empty($title)) {
        $error = "Title is required.";
    } elseif (empty($description)) {
        $error = "Description is required.";
    } else {
        try {
            $db = new Database();
            $serviceManager = new Service($db->connect());
            $serviceManager->create($title, $description, $image);
            $success = "Service added successfully!";
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Add Service - Sports Page 101</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-slightyDarkBlue">
        <div class="container-fluid">
            <a href="../index.php" class="navbar-brand">Sports Page 101</a>
            <button type="button" class="btn text-white" id="themeToggler">
                <i class="bi bi-moon-stars" id="dark-mode-icon"></i>
            </button>
        </div>
    </nav>

    <main>
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <a href="../dashboard.php" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <h1 class="fw-normal mb-0">Add New Service</h1>
                        </div>

                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($success) ?>
                            </div>
                        <?php endif; ?>

                        <div class="card shadow-sm">
                            <div class="card-body p-4">
                                <form method="POST" id="addServiceForm">
                                    <div class="mb-3">
                                        <label for="title" class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title"
                                               class="form-control"
                                               placeholder="Service title"
                                               value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                                        <textarea name="description" id="description"
                                                  class="form-control"
                                                  placeholder="Service description"
                                                  rows="4"
                                                  required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="image" class="form-label fw-semibold">Image Path</label>
                                        <input type="text" name="image" id="image"
                                               class="form-control"
                                               placeholder="e.g. images/service.png"
                                               value="<?= htmlspecialchars($_POST['image'] ?? '') ?>">
                                        <div class="form-text">Leave empty if no image.</div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Add Service
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
