<?php

class Message {
    private PDO $conn;
    private string $table = "messages";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function validate(string $name, string $email, string $message): ?string {
        if (empty($name) || empty($email) || empty($message)) {
            return "All fields are required.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }

        return null;
    }

    public function create(string $name, string $email, string $message): bool {
        $sql = "INSERT INTO {$this->table} (name, email, message) VALUES (:name, :email, :message)";
        
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':message' => $message
        ]);
    }
}

?>