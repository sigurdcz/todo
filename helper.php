<?php
    function dd(mixed ...$values): never
    {
        echo "<pre style='background:#222;color:#0f0;padding:1rem;'>\n";
        foreach ($values as $value) {
            print_r($value);
            echo "\n";
        }
        echo "</pre>\n";
        exit;
    }


    function dump(mixed ...$values): void
    {
        echo "<pre style='background:#222;color:#0f0;padding:1rem;'>\n";
        foreach ($values as $value) {
            print_r($value);
            echo "\n";
        }
        echo "</pre>\n";
    }