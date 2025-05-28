<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private PDO $connection;

    public function __construct(array $config)
    {
        try {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                $config['DB_HOST'],
                $config['DB_NAME']
            );

            $this->connection = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            error_log('Databázové připojení selhalo: ' . $e->getMessage());
            exit('Chyba připojení k databázi.');
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
