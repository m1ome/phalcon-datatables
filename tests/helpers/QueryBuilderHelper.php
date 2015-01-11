<?php
namespace Helper;

class QueryBuilderHelper extends \Phalcon\Test\ModelTestCase {
  public function setUp(\Phalcon\DiInterface $di = null, \Phalcon\Config $config = null) {
    \Phalcon\DI::reset();
    $di = new \Phalcon\DI\FactoryDefault();
    $this->di = $di;

    $this->config = [
      'db' => [
        'sqlite' => [
          'dbname' => __DIR__ . '/../db.sqlite'
        ]
      ]
    ];

    // Set Models manager
    $this->di->set(
      'modelsManager',
      function () {
        return new \Phalcon\Mvc\Model\Manager();
      }
    );

    // Set Models metadata
    $this->di->set(
      'modelsMetadata',
      function () {
        return new \Phalcon\Mvc\Model\MetaData\Memory();
      }
    );

    $this->setDb('sqlite');
    $this->populateTable('user');

    \Phalcon\DI::setDefault($di);
  }

  public function mockRequest($array) {
    $mock = $this->getMock('\\Phalcon\\Http\\Request', array("isPost", "getPost"));
    $mock->expects($this->any())
         ->method("isPost")
         ->will($this->returnValue(true));
    $mock->expects($this->any())
         ->method("getPost")
         ->will($this->returnValue($array));

    $this->di->set('request', $mock);
    \Phalcon\DI::setDefault($this->di);
  }

  public function tearDown() {
    $connection = $this->di->get('db');
    $connection->execute("DELETE FROM user");
  }
}