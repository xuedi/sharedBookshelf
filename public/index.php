<?php declare(strict_types=1);

namespace SharedBookshelf;

require __DIR__ . '/../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
if (substr($_SERVER["REQUEST_URI"], 0, 8) == '/assets/' || $_SERVER["REQUEST_URI"] == '/favicon.ico') {
    return false;
}

session_start();

$factory = new Factory(new File(__DIR__ . '/../config/config.ini'));
$factory->setup();
$factory->run();

