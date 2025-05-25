<?php
namespace App\Model;

use PDO;

class UserModel {
    public function __construct(private PDO $db) {}

    public function findByToken(string $token): ?array {
        $stmt = $this->db->prepare("SELECT u.* FROM users u JOIN login_tokens t ON t.user_id = u.id WHERE t.token = ? AND t.expires_at > NOW()");
        $stmt->execute([$token]);
        return $stmt->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function createUser(string $email, string $hashedPassword): bool
    {
        $stmt = $this->db->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        try {
            return $stmt->execute([$email, $hashedPassword]);
        } catch (\PDOException $e) {
            log_error("Chyba pri registraci ($email, $hashedPassword)");
            return false;
        }
    }
}