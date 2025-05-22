<?php
namespace App\Model;

use PDO;

class UserTodoListModel
{
    public function __construct(private PDO $pdo) {}

    /**
     * Vrátí hash prvního todo listu, ke kterému má uživatel přístup.
     * Seřazeno podle is_owner DESC, pak podle todo_list_id ASC.
     */
    public function getFirstTodoListHashForUser(int $userId): ?string
    {
        $stmt = $this->pdo->prepare("
            SELECT tl.hash
            FROM user_todo_list utl
            JOIN todo_lists tl ON utl.todo_list_id = tl.id
            WHERE utl.user_id = :user_id
            ORDER BY utl.is_owner DESC, utl.todo_list_id ASC
            LIMIT 1
        ");
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['hash'] : null;
    }

    public function addUserToTodoList(int $userId, int $todoListId, bool $isOwner): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO user_todo_list (user_id, todo_list_id, is_owner) 
            VALUES (:user_id, :todo_list_id, :is_owner)
        ");
        $stmt->execute([
            'user_id' => $userId,
            'todo_list_id' => $todoListId,
            'is_owner' => $isOwner ? 1 : 0,
        ]);
    }
}
