<?php
declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/router.php';

$container = require __DIR__ . '/bootstrap.php';

// Zavolání funkce z router.php
routeRequest($container);
