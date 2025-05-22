<?php
use App\Core\Logger;

/**
 * Globální přístup k DI kontejneru – očekáváme, že $container je k dispozici
 * nebo si jej můžeš vytvořit v `bootstrap.php` a zpřístupnit globálně.
 */
function getContainer(): App\Core\Container
{
    static $container;
    if (!$container) {
        $container = require __DIR__ . '/bootstrap.php';
    }
    return $container;
}

/**
 * Vypíše hodnoty a ukončí skript.
 */
function dd(mixed ...$values): never
{
    header('Content-Type: text/html; charset=utf-8');
    echo "<pre style='background:#222;color:#0f0;padding:1rem;'>\n";
    foreach ($values as $value) {
        print_r($value);
        echo "\n";
    }
    echo "</pre>\n";
    exit;
}

/**
 * Vypíše hodnoty, ale neukončí skript.
 */
function dump(mixed ...$values): void
{
    header('Content-Type: text/html; charset=utf-8');
    echo "<pre style='background:#222;color:#0f0;padding:1rem;'>\n";
    foreach ($values as $value) {
        print_r($value);
        echo "\n";
    }
    echo "</pre>\n";
}

/**
 * Zapíše zprávu do logu (info nebo error).
 */
function log_info(string $message): void
{
    getContainer()->get(Logger::class)->info($message);
}

function log_error(string $message): void
{
    getContainer()->get(Logger::class)->error($message);
}
