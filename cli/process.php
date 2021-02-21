#!/usr/bin/php
<?php

namespace SharedBookshelf;

require __DIR__ . '/../vendor/autoload.php';

$configFile = new File(__DIR__ . '/../config/config.ini');

$factory = new Factory($configFile);
$factory->process();
