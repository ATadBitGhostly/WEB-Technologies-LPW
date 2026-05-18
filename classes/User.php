<?php

class User {
    private ?PDO $conn = null;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function readAll() {
        try {
            $stmt = $this->conn->prepare("SELECT id, username, email, isAdmin FROM users");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error reading users: " . $e->getMessage());
        }
    }

    public function readOne($id) {
        try {
            $stmt = $this->conn->prepare("SELECT id, username, email, isAdmin FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error reading user: " . $e->getMessage());
        }
    }

    public function updateProfile($id, $username, $email) {
        try {
            // Check if email is taken by another user
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $id]);
            if ($stmt->fetch()) {
                throw new Exception("Email is already in use by another account.");
            }

            $stmt = $this->conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$username, $email, $id]);
        } catch (PDOException $e) {
            throw new Exception("Error updating profile: " . $e->getMessage());
        }
    }

    public function updatePassword($id, $currentPassword, $newPassword) {
        try {
            // Verify current password first
            $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($currentPassword, $user['password'])) {
                throw new Exception("Current password is incorrect.");
            }

            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed, $id]);
        } catch (PDOException $e) {
            throw new Exception("Error updating password: " . $e->getMessage());
        }
    }

    public function toggleAdmin($id) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET isAdmin = IF(isAdmin = 1, 0, 1) WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Error toggling admin: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Error deleting user: " . $e->getMessage());
        }
    }
}
?>