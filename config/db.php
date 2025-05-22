<?php
declare(strict_types=1);

$config = require __DIR__ . '/env.php';

try {
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=utf8mb4',
        $config['DB_HOST'],
        $config['DB_NAME']
    );

    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,    // Vyhazovat výjimky při chybách
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Výchozí fetch jako asociativní pole
        PDO::ATTR_EMULATE_PREPARES => false,            // Použít native prepared statements
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    log_error('Databázové připojení selhalo.');
    exit;
}

return $pdo;
