<?php

namespace App\Core;

class Logger
{
    private string $logDir;

    public function __construct(array $env)
    {
        $this->logDir = rtrim($env['log_dir'] ?? __DIR__ . '/../../logs', '/');

        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0777, true);
        }
    }

    public function info(string $message): void
    {
        $this->writeLog('info.log', $message);
    }

    public function error(string $message): void
    {
        $this->writeLog('error.log', $message);
    }

    private function writeLog(string $file, string $message): void
    {
        $datetime = (new \DateTime())->format('Y-m-d H:i:s');
        $entry = "[$datetime] $message" . PHP_EOL;
        file_put_contents($this->logDir . '/' . $file, $entry, FILE_APPEND);
    }
}
