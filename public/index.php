<?php declare(strict_types=1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
if (substr($_SERVER["REQUEST_URI"], 0, 8) == '/assets/') {
    return false;
}

use SharedBookshelf\Factory;
use SharedBookshelf\File;

require __DIR__ . '/../vendor/autoload.php';

$config = new File(__DIR__ . '/../config.ini');

$factory = new Factory($config);
$factory->run();
