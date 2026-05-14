<?php

class Service {
    private ?PDO $conn = null;
    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }
    public function create($title, $desc, $img){
        try {
            $stmt = $this->conn->prepare("INSERT INTO services (title, description, image) VALUES (?, ?, ?)");
            $stmt->execute([$title, $desc, $img]);
        } catch (PDOException $e) {
            throw new Exception("Error creating service: " . $e->getMessage());
        }
    }
    public function readAll(){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM services");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error reading services: " . $e->getMessage());
        }
    }
    public function update($id, $title, $desc){
        try {
            $stmt = $this->conn->prepare("UPDATE services SET title = ?, description = ? WHERE id = ?");
            $stmt->execute([$title, $desc, $id]);
        } catch (PDOException $e) {
            throw new Exception("Error updating service: " . $e->getMessage());
        }
    }
    public function delete($id){
        try {
            $stmt = $this->conn->prepare("DELETE FROM services WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Error deleting service: " . $e->getMessage());
        }
    }
    public function search($term){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM services WHERE title LIKE ? OR description LIKE ?");
            $wildcard = "%$term%";
            $stmt->execute([$wildcard, $wildcard]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error searching services: " . $e->getMessage());
        }
    }
}
?>
