<?php
namespace App\Model;

use PDO;

class TaskModel {
    public function __construct(private PDO $db) {}

    public function getAll(int $listId): array {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE list_id = ?");
        $stmt->execute([$listId]);
        return $stmt->fetchAll();
    }

    public function create(int $listId, string $description): void {
        $stmt = $this->db->prepare("INSERT INTO tasks (list_id, description) VALUES (?, ?)");
        $stmt->execute([$listId, $description]);
    }

    public function update(int $taskId, string $description, bool $completed): void {
        $stmt = $this->db->prepare("UPDATE tasks SET description = ?, completed = ? WHERE id = ?");
        $stmt->execute([$description, $completed ? 1 : 0 , $taskId]);
    }

    public function delete(int $taskId): void {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$taskId]);
    }
}