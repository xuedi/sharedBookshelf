<?php declare(strict_types=1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
if (substr($_SERVER["REQUEST_URI"], 0, 8) == '/assets/') {
    return false;
}

use SharedBookshelf\Factory;

require __DIR__ . '/../vendor/autoload.php';

$factory = new Factory();
$factory->run();
