<?php
declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/helper.php';
require __DIR__ . '/router.php';

$container = require __DIR__ . '/bootstrap.php';

routeRequest($container);
