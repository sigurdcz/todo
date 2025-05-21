<?php
$db = require __DIR__ . '/config/db.php';

$migrationsDir = __DIR__ . '/migrations';
$executed = [];

$db->exec("CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$stmt = $db->query("SELECT name FROM migrations");
foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $m) {
    $executed[] = $m;
}

foreach (scandir($migrationsDir) as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'sql') continue;
    if (in_array($file, $executed)) continue;

    echo "Spouštím migraci: $file\n";
    $sql = file_get_contents("$migrationsDir/$file");
    $db->exec($sql);

    $stmt = $db->prepare("INSERT INTO migrations (name) VALUES (?)");
    $stmt->execute([$file]);
}

echo "Migrace hotovy.\n";