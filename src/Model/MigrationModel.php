<?php
namespace App\Model;

use PDO;

class MigrationModel
{
    public function __construct(
        private PDO $db
    ) {}

    public function runMigrations(string $migrationsDir): array
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $executed = [];
        $stmt = $this->db->query("SELECT name FROM migrations");
        foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $m) {
            $executed[] = $m;
        }

        $output = [];
        foreach (scandir($migrationsDir) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) !== 'sql') continue;
            if (in_array($file, $executed)) continue;

            $output[] = "Running migration: <strong>$file</strong>";
            $sql = file_get_contents("$migrationsDir/$file");

            try {
                $this->db->exec($sql);
                $stmt = $this->db->prepare("INSERT INTO migrations (name) VALUES (?)");
                $stmt->execute([$file]);
            } catch (\PDOException $e) {
                $output[] = "<span style='color:red;'>Error in $file: " . htmlspecialchars($e->getMessage()) . "</span>";
            }
        }

        if (empty($output)) {
            $output[] = "All migrations are already up to date.";
        } else {
            $output[] = "✅ Migrations completed.";
        }

        return $output;
    }
}
