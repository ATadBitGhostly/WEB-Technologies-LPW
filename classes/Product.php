<?php

class Product {
    private ?PDO $conn = null;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function create($title, $desc, $img, $price, $stock) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO products (title, description, image, price, stock) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $desc, $img, $price, $stock]);
        } catch (PDOException $e) {
            throw new Exception("Error creating product: " . $e->getMessage());
        }
    }

    public function readAll() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM products");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error reading products: " . $e->getMessage());
        }
    }

    public function readOne($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error reading product: " . $e->getMessage());
        }
    }

    public function update($id, $title, $desc, $price, $stock) {
        try {
            $stmt = $this->conn->prepare("UPDATE products SET title = ?, description = ?, price = ?, stock = ? WHERE id = ?");
            $stmt->execute([$title, $desc, $price, $stock, $id]);
        } catch (PDOException $e) {
            throw new Exception("Error updating product: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Error deleting product: " . $e->getMessage());
        }
    }

    public function search($term) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM products WHERE title LIKE ? OR description LIKE ?");
            $wildcard = "%$term%";
            $stmt->execute([$wildcard, $wildcard]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error searching products: " . $e->getMessage());
        }
    }
}
?>