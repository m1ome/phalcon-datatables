<?php
namespace Spec;

use DataTables\DataTable;
use kahlan\plugin\Stub;

describe("DataTable", function() {

  before(function() {

    $this->di = \Phalcon\DI::getDefault();

  });

  it("should work with empty ResultSet", function() {

    $resultset  = $this->di->get('modelsManager')
                  ->createQuery("SELECT * FROM \Spec\Models\User WHERE balance < 0")
                  ->execute();

    $dataTables = new DataTable();
    $response = $dataTables->fromResultSet($resultset)->getResponse();

    expect($response)->toBe([
      'draw' => null,
      'recordsTotal' => 0,
      'recordsFiltered' => 0,
      'data' => []
    ]);

  });

  it("should create a ResultSet", function() {

    $resultset  = $this->di->get('modelsManager')
                      ->createQuery("SELECT * FROM \Spec\Models\User")
                      ->execute();

    $dataTables = new DataTable();
    $response = $dataTables->fromResultSet($resultset)->getResponse();
    expect($dataTables->getParams())->toBe([
      "draw" => null,
      "start" => 1,
      "length" => 20,
      "columns" => [],
      "search" => [],
      "order" => []
    ]);
    expect(count($response['data']))->toBe(20);
    expect(array_keys($response))->toBe(['draw', 'recordsTotal', 'recordsFiltered', 'data']);

    foreach($response['data'] as $data) {
      expect(array_keys($data))->toBe(['id', 'name', 'email', 'balance', 'DT_RowId']);
      expect($data['DT_RowId'])->toBe($data['id']);
    }

  });

  it("should create an empty ArrayAdapter", function() {

    $array  = $this->di->get('modelsManager')
                      ->createQuery("SELECT * FROM \Spec\Models\User WHERE balance < 0")
                      ->execute()->toArray();

    $dataTables = new DataTable();
    $response = $dataTables->fromArray($array)->getResponse();

    expect($response)->toBe([
      'draw' => null,
      'recordsTotal' => 0,
      'recordsFiltered' => 0,
      'data' => []
    ]);

  });

  it("should create a ArrayAdapter", function() {

    $array  = $this->di->get('modelsManager')
                      ->createQuery("SELECT * FROM \Spec\Models\User")
                      ->execute()->toArray();

    $dataTables = new DataTable();
    $response = $dataTables->fromArray($array)->getResponse();
    expect($dataTables->getParams())->toBe([
      "draw" => null,
      "start" => 1,
      "length" => 20,
      "columns" => [],
      "search" => [],
      "order" => []
    ]);
    expect(count($response['data']))->toBe(20);
    expect(array_keys($response))->toBe(['draw', 'recordsTotal', 'recordsFiltered', 'data']);

    foreach($response['data'] as $data) {
      expect(array_keys($data))->toBe(['id', 'name', 'email', 'balance', 'DT_RowId']);
      expect($data['DT_RowId'])->toBe($data['id']);
    }

  });

  it("should create a from a empty QueryBuilder", function() {

    $builder = $this->di->get('modelsManager')
                   ->createBuilder()
                   ->columns('id, name, email, balance')
                   ->from('Spec\Models\User')
                   ->where('balance < 0');

    $dataTables = new DataTable();
    $response = $dataTables->fromBuilder($builder)->getResponse();

    expect($response)->toBe([
      'draw' => null,
      'recordsTotal' => 0,
      'recordsFiltered' => 0,
      'data' => []
    ]);

  });

  it("should create a QueryBuilder", function() {

    $builder = $this->di->get('modelsManager')
                   ->createBuilder()
                   ->columns('id, name, email, balance')
                   ->from('Spec\Models\User');

    $dataTables = new DataTable();
    $response = $dataTables->fromBuilder($builder)->getResponse();
    expect(count($response['data']))->toBe(20);
    expect(array_keys($response))->toBe(['draw', 'recordsTotal', 'recordsFiltered', 'data']);

    foreach($response['data'] as $data) {
      expect(array_keys($data))->toBe(['id', 'name', 'email', 'balance', 'DT_RowId']);
      expect($data['DT_RowId'])->toBe($data['id']);
    }

  });

  it("should disable view & send response", function() {

    $this->di->set('view', function() {
      $view = Stub::create();

      return $view;
    });
    \Phalcon\DI::setDefault($this->di);

    $builder = $this->di->get('modelsManager')
                   ->createBuilder()
                   ->columns('id, name, email, balance')
                   ->from('Spec\Models\User');

    $dataTables = new DataTable();
    expect(function() use($dataTables, $builder){
      $dataTables->fromBuilder($builder)->sendResponse();
    })->toEcho("{\"draw\":null,\"recordsTotal\":100,\"recordsFiltered\":100,\"data\":[{\"id\":\"0\",\"name\":\"wolff.jamel\",\"email\":\"rkuhn@dicki.com\",\"balance\":\"500\",\"DT_RowId\":\"0\"},{\"id\":\"1\",\"name\":\"violet36\",\"email\":\"imedhurst@paucek.com\",\"balance\":\"100\",\"DT_RowId\":\"1\"},{\"id\":\"2\",\"name\":\"beahan.abbigail\",\"email\":\"kenna23@mertz.biz\",\"balance\":\"200\",\"DT_RowId\":\"2\"},{\"id\":\"3\",\"name\":\"rickie05\",\"email\":\"echamplin@terry.com\",\"balance\":\"200\",\"DT_RowId\":\"3\"},{\"id\":\"4\",\"name\":\"runolfsdottir.evert\",\"email\":\"vern.goldner@rau.org\",\"balance\":\"200\",\"DT_RowId\":\"4\"},{\"id\":\"5\",\"name\":\"judson99\",\"email\":\"homenick.angeline@greenfelder.com\",\"balance\":\"200\",\"DT_RowId\":\"5\"},{\"id\":\"6\",\"name\":\"walter.eliseo\",\"email\":\"eleanore.gusikowski@yahoo.com\",\"balance\":\"100\",\"DT_RowId\":\"6\"},{\"id\":\"7\",\"name\":\"monserrate.lebsack\",\"email\":\"abraham.hane@gmail.com\",\"balance\":\"500\",\"DT_RowId\":\"7\"},{\"id\":\"8\",\"name\":\"llangworth\",\"email\":\"anderson.delphia@gmail.com\",\"balance\":\"100\",\"DT_RowId\":\"8\"},{\"id\":\"9\",\"name\":\"krajcik.rylee\",\"email\":\"franecki.conor@gmail.com\",\"balance\":\"500\",\"DT_RowId\":\"9\"},{\"id\":\"10\",\"name\":\"vsauer\",\"email\":\"maia14@conroy.com\",\"balance\":\"100\",\"DT_RowId\":\"10\"},{\"id\":\"11\",\"name\":\"green.german\",\"email\":\"hegmann.jane@hotmail.com\",\"balance\":\"200\",\"DT_RowId\":\"11\"},{\"id\":\"12\",\"name\":\"gebert\",\"email\":\"sdicki@gmail.com\",\"balance\":\"200\",\"DT_RowId\":\"12\"},{\"id\":\"13\",\"name\":\"michale68\",\"email\":\"qblock@yahoo.com\",\"balance\":\"200\",\"DT_RowId\":\"13\"},{\"id\":\"14\",\"name\":\"ismitham\",\"email\":\"hirthe.marjory@hotmail.com\",\"balance\":\"200\",\"DT_RowId\":\"14\"},{\"id\":\"15\",\"name\":\"jarrell09\",\"email\":\"cedrick04@gmail.com\",\"balance\":\"100\",\"DT_RowId\":\"15\"},{\"id\":\"16\",\"name\":\"iharvey\",\"email\":\"jude.christiansen@hotmail.com\",\"balance\":\"100\",\"DT_RowId\":\"16\"},{\"id\":\"17\",\"name\":\"bode.maxine\",\"email\":\"tmosciski@yahoo.com\",\"balance\":\"100\",\"DT_RowId\":\"17\"},{\"id\":\"18\",\"name\":\"murray.jeff\",\"email\":\"fjacobs@strosin.com\",\"balance\":\"200\",\"DT_RowId\":\"18\"},{\"id\":\"19\",\"name\":\"gmertz\",\"email\":\"marquardt.nicolas@williamson.com\",\"balance\":\"100\",\"DT_RowId\":\"19\"}]}");

  });

});
