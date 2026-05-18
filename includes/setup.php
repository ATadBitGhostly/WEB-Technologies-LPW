<?php

$host = 'localhost';
$dbname = 'webdev_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");

    // Create 'services' table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS services (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255)
        )
    ");

    // Create 'users' table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )
    ");

    // Create 'messages' table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Create "products" table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255),
            price DECIMAL(10,2) NOT NULL,
            stock INT NOT NULL DEFAULT 0
        )
    ");

    // Create "orders" table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            total DECIMAL(10,2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ");

    // Create "order_items" table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(id),
            FOREIGN KEY (product_id) REFERENCES products(id)
        )
    ");

    // Add stock column if it doesn't exist (for existing databases)
    $pdo->exec("
        ALTER TABLE products 
        ADD COLUMN IF NOT EXISTS stock INT NOT NULL DEFAULT 0
    ");

    // Adds status column to orders table if it doesn't exist
    $pdo->exec("
        ALTER TABLE orders 
        ADD COLUMN IF NOT EXISTS status VARCHAR(50) NOT NULL DEFAULT 'pending'
    ");

    // Adds isAdmin column to users table if it doesn't exist
    $pdo->exec("
        ALTER TABLE users
        ADD COLUMN IF NOT EXISTS isAdmin TINYINT(1) NOT NULL DEFAULT 0
    ");

    // Creates a default admin user, if one does not exist
    $adminEmail = 'admin@admin.com';
    $adminUsername = 'admin';
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$adminEmail]);

    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, isAdmin) VALUES (?, ?, ?, 1)");
        $stmt->execute([$adminUsername, $adminEmail, $adminPassword]);
        echo " Admin user created!";
    }  

    echo "Tables created successfully!";

} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}
?>