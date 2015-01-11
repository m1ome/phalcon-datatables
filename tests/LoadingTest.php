<?php
namespace Test;

use \Phalcon\Test\UnitTestCase;

class LoadingTest extends UnitTestCase {
  public function testLoading() {
    $this->assertEquals(true, class_exists('DataTables\Adapters\QueryBuilder'), 'DataTables QueryBuilder not exists');
    $this->assertEquals(true, class_exists('DataTables\ParamsParser'), 'DataTables ParamsParser not exists');
  }
}