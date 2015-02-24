<?php

namespace Spec\Adapters;

use Phalcon\Mvc\Model\Query\Builder;
use DataTables\Adapters\QueryBuilder;
use DataTables\ParamsParser;

describe("QueryBuilder", function() {

  beforeEach(function() {

    $builder = new Builder();
    $builder->columns('id, name, email, balance')->from("Spec\Models\User");

    $this->builder = $builder;

  });

  it("should work withot any filter", function() {

    $dataTables = new QueryBuilder(20);
    $dataTables->setBuilder($this->builder);
    $dataTables->setParser(new ParamsParser(10));

    $response = $dataTables->getResponse();
    expect($dataTables->getParser())->toBeA('object');
    expect(array_keys($response))->toBe(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    expect($response['recordsTotal'])->toBe(100);
    expect($response['recordsFiltered'])->toBe(100);
    expect(count($response['data']))->toBe(10);

    foreach($response['data'] as $data) {
      expect(array_keys($data))->toBe(['id', 'name', 'email', 'balance', 'DT_RowId']);
      expect($data['DT_RowId'])->toBe($data['id']);
    }

  });

  describe("Limit&Offset", function() {

    beforeEach(function() {

      $_GET = ['start' => 2, 'length' => 1];

    });

    it("should work with start&length", function() {

      $dataTables = new QueryBuilder(20);
      $dataTables->setBuilder($this->builder);
      $dataTables->setParser(new ParamsParser(10));
      $response = $dataTables->getResponse();
      expect(count($response['data']))->toBe(1);

      $dataOne = $response['data'];

      $_GET['start'] = 3;
      $dataTables = new QueryBuilder(20);
      $dataTables->setBuilder($this->builder);
      $dataTables->setParser(new ParamsParser(10));
      $response = $dataTables->getResponse();
      expect(count($response['data']))->toBe(1);
      expect($response['data'])->not->toBe($dataOne);

    });

    it("should work with a filter", function() {

      $_GET['search'] = ['value' => 'kr'];
      $_GET['columns'] = [
        [
          'data' => 'name',
          'searchable' => "true"
        ]
      ];

      $dataTables = new QueryBuilder(20);
      $dataTables->setBuilder($this->builder);
      $dataTables->setParser(new ParamsParser(10));
      $dataTables->setColumns(['name']);
      $response = $dataTables->getResponse();
      expect(count($response['data']))->toBe(1);

    });

    afterEach(function() {

      unset($_GET);

    });

  });

  it("should work with a global search", function() {

    $_GET = [
      'search' => ['value' => 'kr'],
      'columns' => [
        [
          'data' => 'name',
          'searchable' => "true"
        ]
      ]
    ];

    $dataTables = new QueryBuilder(20);
    $dataTables->setBuilder($this->builder);
    $dataTables->setColumns(['name', 'email']);
    $dataTables->setParser(new ParamsParser(10));

    $response = $dataTables->getResponse();
    expect(count($response['data']))->toBe(3);
    $names = array_reduce($response['data'], function($carry, $item) {
      $carry[] = $item['name'];
      return $carry;
    });

    expect($names)->toBe(['krajcik.rylee', 'kraig.mann', 'kara.krajcik']);

  });

  it("should work with a column search", function() {

    $_GET = [
      'columns' => [
        [
          'data' => 'name',
          'searchable' => "true",
          'search' => [
            'value' => 'be'
          ]
        ]
      ]
    ];

    $dataTables = new QueryBuilder(20);
    $dataTables->setBuilder($this->builder);
    $dataTables->setColumns(['name', 'email']);
    $dataTables->setParser(new ParamsParser(10));

    $response = $dataTables->getResponse();
    expect(count($response['data']))->toBe(5);
    $names = array_reduce($response['data'], function($carry, $item) {
      $carry[] = $item['name'];
      return $carry;
    });
    expect($names)->toBe(['beahan.abbigail', 'gebert', 'breitenberg.ted', 'marvin.maybelle', 'zabernathy']);

  });

  it("should work with a column&global search", function() {

    $_GET = [
      'columns' => [
        [
          'data' => 'name',
          'searchable' => "true",
          'search' => [
            'value' => 'be'
          ]
        ],
        [
          'data' => 'email',
          'searchable' => "true",
          'search' => [
            'value' => "@gmail.com"
          ]
        ]
      ]
    ];

    $dataTables = new QueryBuilder(20);
    $dataTables->setBuilder($this->builder);
    $dataTables->setColumns(['name', 'email']);
    $dataTables->setParser(new ParamsParser(10));

    $response = $dataTables->getResponse();
    expect(count($response['data']))->toBe(3);
    $names = array_reduce($response['data'], function($carry, $item) {
      $carry[] = $item['name'];
      return $carry;
    });
    expect($names)->toBe(['gebert', 'marvin.maybelle', 'zabernathy']);

  });

  it("should order", function() {

    $_GET = [
      'columns' => [
        [
          'data' => 'name',
          'searchable' => "true",
        ]
      ],
      'order' => [
        [
          'column' => 0,
          'dir' => 'desc'
        ]
      ]
    ];

    $dataTables = new QueryBuilder(20);
    $dataTables->setBuilder($this->builder);
    $dataTables->setColumns(['name', 'email']);
    $dataTables->setParser(new ParamsParser(10));

    $response = $dataTables->getResponse();
    expect(count($response['data']))->toBe(10);
    expect($response['data'][0]['name'])->toBe('zcremin');

  });

  it("should order asc", function() {

    $_GET = [
      'columns' => [
        [
          'data' => 'name',
          'searchable' => "true",
        ]
      ],
      'order' => [
        [
          'column' => 0,
          'dir' => 'asc'
        ]
      ]
    ];

    $dataTables = new QueryBuilder(20);
    $dataTables->setBuilder($this->builder);
    $dataTables->setColumns(['name', 'email']);
    $dataTables->setParser(new ParamsParser(10));

    $response = $dataTables->getResponse();
    expect(count($response['data']))->toBe(10);
    expect($response['data'][0]['name'])->toBe('adelia13');

  });

  afterEach(function() {

    unset($_GET);
    unset($_POST);
    unset($_REQUEST);

  });

});
