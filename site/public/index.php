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
/** @noinspection PhpUndefinedMethodInspection */
$app->getRouter()->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);

$app->get('/', function() use($app) {
  $app['view']->render('index.volt', [
  ]);
});

$app->post('/example_querybuilder', function() use($app) {

  $builder = $app->getService('modelsManager')
                 ->createBuilder()
                 ->columns('id, name, email, balance')
                 ->from('Example\Models\User');

  $dataTables = new \DataTables\DataTable();
  $dataTables->fromBuilder($builder)->sendResponse();

});

$app->get('/example_resultset', function() use($app) {

  $resultset  = $app->getService('modelsManager')
                    ->createQuery("SELECT * FROM \Example\Models\User")
                    ->execute();

  $dataTables = new \DataTables\DataTable();
  $dataTables->fromResultSet($resultset)->sendResponse();

});

$app->handle();
