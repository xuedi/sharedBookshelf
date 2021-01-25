<?php declare(strict_types=1);

namespace SharedBookshelf;

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
if (substr($_SERVER["REQUEST_URI"], 0, 8) == '/assets/') {
    return false;
}

$slim = AppFactory::create();
$config = new Configuration(new FsWrapper());

$factory = new Factory($slim, $config);
$factory->run();
