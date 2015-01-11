<?php
namespace Helpers;

class ParamsParserHelper extends \Phalcon\Test\UnitTestCase {
  public function setUp(\Phalcon\DiInterface $di = null, \Phalcon\Config $config = null) {
    parent::setUp();
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
}