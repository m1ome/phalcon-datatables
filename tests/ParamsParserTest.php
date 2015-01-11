<?php
namespace Test;

use \Helpers\ParamsParserHelper;

class ParamsParserTest extends ParamsParserHelper {
  public function testParsing() {
    $request = [
      'draw'  => 100,
      'start' => 50,
      'length' => 10,
      'columns' => [
        [
          'data' => 'id',
          'searchable' => "false",
          'search' => [
            'value' => '',
            'regex' => ''
          ]
        ],
        [
          'data' => 'name',
          'searchable' => "true",
          'search' => [
            'value' => 'some_other_value',
            'regex' => ''
          ]
        ],
      ],
      'order' => [
        [
          'column' => 0,
          'dir' => 'asc'
        ]
      ],
      'search' => [
        'value' => 'some_value',
        'regex' => false
      ]
    ];

    $this->mockRequest($request);
    $parser = new \DataTables\ParamsParser();

    $this->assertEquals(100, $parser->getDraw(), 'Wrong draw pasrsed from request');
    $this->assertEquals(10, $parser->getLimit(), 'Wrong limit parser from request');
    $this->assertEquals(6, $parser->getPage(), 'Wrong page number from request');
    $this->assertEquals(50, $parser->getOffset(), 'Wrong offset parsed from request');

    $this->assertInternalType('array', $parser->getOrder(), 'Wrong order type parsed');
    foreach($parser->getOrder() as $order) {
      $this->assertArrayHasKey('column', $order, 'Order array not having column key');
      $this->assertArrayHasKey('dir', $order, 'Order array not having dir key');
    }

    $this->assertInternalType('array', $parser->getSearchableColumns(), 'Searchable columns is not array');
    $this->assertCount(1, $parser->getSearchableColumns(), 'Searchable coulms have wrong count');

    $this->assertEquals('some_value', $parser->getSearchValue(), 'Search value is wrong');

    $this->assertInternalType('array', $parser->getColumnsSearch(), 'Column search is not array');
    $this->assertCount(1, $parser->getColumnsSearch(), 'Wrong count of column search array');

    $search = current($parser->getColumnsSearch());
    $this->assertArrayHasKey('search', $search, 'Column do not have a search key');

    $search = $search['search'];
    $this->assertArrayHasKey('value', $search, 'Search sub-array not have a "value" key');
    $this->assertEquals('some_other_value', $search['value'], 'Parsed wrong value from search by column');
  }
}