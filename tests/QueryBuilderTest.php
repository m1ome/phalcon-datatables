<?php
namespace Test;

use Helper\QueryBuilderHelper;

class QueryBuilderTest extends QueryBuilderHelper {
  public function testWithoutRequestParams() {
    $builder  = new \DataTables\Adapters\QueryBuilder();
    $limit    = 20;
    $response = $builder->from('User')->getResponse();

    $this->assertEquals('Phalcon\Http\Response', get_class($response), 'Returned response is not instance of \Phalcon\Http\Reseponse');
    $json = $response->getContent();
    $this->assertInternalType('string', $json, 'JSON content is not a string');
    $decodedJSON = json_decode($json);
    $this->assertInternalType('object', $decodedJSON, 'Response returned not valid JSON');

    $this->assertObjectHasAttribute('draw', $decodedJSON, 'Response not have a "draw" attribute');
    $this->assertObjectHasAttribute('recordsTotal', $decodedJSON, 'Response not have a "recordsTotal" attribute');
    $this->assertObjectHasAttribute('recordsFiltered', $decodedJSON, 'Response not have a "recordsFiltered" attribute');
    $this->assertObjectHasAttribute('data', $decodedJSON, 'Response not have a "data" attribute');

    $this->assertInternalType('array', $decodedJSON->data, 'Response data array is not type of array');
    $this->assertEquals($decodedJSON->recordsTotal, 50, 'Response have wrong records total record');
    $this->assertEquals($decodedJSON->recordsFiltered, $decodedJSON->recordsTotal, 'Response filtered and unfiltered fields should be identical');
    $this->assertCount($limit, $decodedJSON->data, 'Response array have wrong count');
  }

  public function testWithCustomLength() {
    $this->mockRequest([
      'draw'   => 100,
      'start'  => 1,
      'length' => 50,
    ]);

    $builder  = new \DataTables\Adapters\QueryBuilder();
    $response = json_decode($builder->from('User')->getResponse()->getContent());

    $this->assertCount(50, $response->data, 'Reseponse array count is wrong');
  }

  public function testWithGLobalSearch() {
    $this->mockRequest([
      'draw'   => 100,
      'columns' => [
        [
          'data' => 'name',
          'searchable' => "true",
          'search' => [
            'value' => '',
            'regex' => ''
          ]
        ],
        [
          'data' => 'email',
          'searchable' => "true",
          'search' => [
            'value' => '',
            'regex' => ''
          ]
        ]
      ],
      'search' => [
        'value' => 'example1',
        'regex' => false
      ]
    ]);

    $builder = new \DataTables\Adapters\QueryBuilder();
    $response = json_decode($builder->columns('id, name, email')->from('User')->getResponse()->getContent());

    $this->assertCount(11, $response->data, 'Response array count is wrong');
    foreach($response->data as $data) {
      $this->assertEquals(true, (strpos($data->email, 'example1') === 0), 'Wrong response obtained after filtering');
    }
  }

  public function testWithOrdering() {
    $this->mockRequest([
      'columns' => [
        [
          'data' => 'id',
          'searchable' => 'false',
          'search' => [
            'value' => '',
            'regex' => ''
          ]
        ],
        [
          'data' => 'name',
          'searchable' => "true",
          'search' => [
            'value' => '',
            'regex' => ''
          ]
        ],
        [
          'data' => 'email',
          'searchable' => "true",
          'search' => [
            'value' => '',
            'regex' => ''
          ]
        ]
      ],
      'order' => [
        [
          'column' => 0,
          'dir' => 'desc'
        ]
      ]
    ]);

    $builder = new \DataTables\Adapters\QueryBuilder();
    $response = json_decode($builder->columns('id, name, email')->from('User')->getResponse()->getContent());

    $lastUser = current($response->data);
    $this->assertEquals(49, (int)$lastUser->id, 'Response is not properly ordered');
  }

  public function testMulipleSearch() {
    $this->mockRequest([
      'columns' => [
        [
          'data' => 'id',
          'searchable' => 'false',
          'search' => [
            'value' => '',
            'regex' => ''
          ]
        ],
        [
          'data' => 'name',
          'searchable' => "true",
          'search' => [
            'value' => 'user#10',
            'regex' => ''
          ]
        ],
        [
          'data' => 'email',
          'searchable' => "true",
          'search' => [
            'value' => '',
            'regex' => ''
          ]
        ]
      ],
      'search' => [
        'value' => 'example1',
        'regex' => ''
      ]
    ]);

    $builder = new \DataTables\Adapters\QueryBuilder();
    $response = json_decode($builder->columns('id, name, email')->from('User')->getResponse()->getContent());

    $this->assertCount(1, $response->data, 'Response contains wrong rows');
  }
}