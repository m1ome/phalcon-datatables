<?php
$loader = new \Phalcon\Loader();
$loader->registerNamespaces([
  'Example\Models' => __DIR__ . '/../app/models',
  'DataTables' => __DIR__ . '/../../src/',
]);
$loader->register();

$di = new \Phalcon\DI\FactoryDefault();

$di->setShared('db', function() {
  return new \Phalcon\Db\Adapter\Pdo\Sqlite([
    'dbname' => __DIR__ . '/../db.sqlite',
  ]);
});

$di->setShared('view', function() {
  $view = new \Phalcon\Mvc\View();

  $view->setViewsDir(__DIR__ . '/../app/views/');
  $view->registerEngines([
    '.volt' => 'Phalcon\Mvc\View\Engine\Volt',
  ]);

  return $view;
});

$app = new \Phalcon\Mvc\Micro($di);
$app->getRouter()->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);

$app->get('/', function() use($app) {
  $app['view']->render('index.volt', [
  ]);
});

$app->post('/example_basic', function() use($app) {
  $builder = new \DataTables\Adapters\QueryBuilder();
  $builder->columns('id, name, email')
          ->from('Example\Models\User');
          
  echo $builder->getResponse()->getContent();
});

$app->handle();
