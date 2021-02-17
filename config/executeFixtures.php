#!/usr/bin/php
<?php

namespace SharedBookshelf;

require __DIR__ . '/../vendor/autoload.php';

$configFile = new File(__DIR__ . '/config.ini');

$factory = new Factory($configFile);
$factory->createFixtureExecutor()->execute();
