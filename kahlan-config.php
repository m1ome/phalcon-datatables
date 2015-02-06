<?php
use filter\Filter;

Filter::register('phalcon.namespace', function($chain) {
  $this->_autoloader->addPsr4('Spec\\Models\\', __DIR__ . '/spec/models/');
});

Filter::apply($this, 'namespaces', 'phalcon.namespace');

$di = new \Phalcon\DI\FactoryDefault();
$di->setShared('db', function() {
  return new \Phalcon\Db\Adapter\Pdo\Sqlite([
    'dbname' => __DIR__ . '/spec/db.sqlite',
  ]);
});
\Phalcon\DI::setDefault($di);
