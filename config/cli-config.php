#!/usr/bin/php
<?php

namespace SharedBookshelf;

require __DIR__ . '/../vendor/autoload.php';

$configFile = new File(__DIR__ . '/config.ini');

$factory = new Factory($configFile);
$helper = $factory->getDoctrineCliHelperSet();
//$helper->setCommand($factory->createFixtureCommand()); //TODO: Fuse fixtures into doctrine

return $helper;
