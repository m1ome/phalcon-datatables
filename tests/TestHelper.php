<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
$loader = new \Phalcon\Loader();

$loader->registerDirs([
  __DIR__,
  __DIR__ . '/models',
]);
$loader->registerNamespaces([
  'DataTables' => __DIR__ . '/../src/',
  'Helper'     => __DIR__ . '/helpers',
  'Phalcon\Test\Fixtures' => __DIR__ . '/fixtures',
]);
$loader->register();

$di = new \Phalcon\DI\FactoryDefault();
\Phalcon\DI::reset();
\Phalcon\DI::setDefault($di);
