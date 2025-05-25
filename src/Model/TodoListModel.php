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

    public function createTodoList(int $userId, string $hash): int
    {
        $stmt = $this->db->prepare("INSERT INTO todo_lists (user_id, hash) VALUES (:user_id, :hash)");
        $stmt->execute(['user_id' => $userId, 'hash' => $hash]);
        return (int)$this->db->lastInsertId();
    }

    public function getAllByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT l.* 
            FROM todo_lists l
            JOIN user_todo_list u ON u.todo_list_id = l.id
            WHERE u.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC); // ✅ správně na $stmt
    }
}