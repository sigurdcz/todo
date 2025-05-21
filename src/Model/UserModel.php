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
}