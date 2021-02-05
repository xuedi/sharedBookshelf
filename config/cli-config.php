#!/usr/bin/php
<?php

namespace SharedBookshelf;

require __DIR__ . '/../vendor/autoload.php';

$factory = new Factory(new File(__DIR__ . '/config.ini'));
return $factory->getDoctrineCliHelperSet();
