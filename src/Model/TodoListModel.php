<?php
namespace App\Model;

use PDO;

class TodoListModel {
    public function __construct(private PDO $db) {}

    public function findByHash(string $hash): ?array {
        $stmt = $this->db->prepare("SELECT * FROM todo_lists WHERE hash = ?");
        $stmt->execute([$hash]);
        return $stmt->fetch() ?: null;
    }
}