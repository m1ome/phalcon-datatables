<?php
namespace Spec;

describe("ParamsParser", function() {

  describe("Malformed", function() {

    before(function() {
      $this->parser = new \DataTables\ParamsParser(10);
    });

    it("should instantiate when _GET and _POST is empty", function() {

      $this->parser = new \DataTables\ParamsParser(10);
      expect($this->parser)->toBeA('object');

      $params = $this->parser->getParams();
      expect($params['draw'])->toBe(null);
      expect($params['start'])->toBe(1);
      expect($params['length'])->toBe(10);
      expect($params['search'])->toBe([]);
      expect($params['columns'])->toBe([]);
      expect($params['order'])->toBe([]);

    });

    it("->getPage()", function() {
      expect($this->parser->getPage())->toBe(1);
    });

    it("->getColumnsSearch()", function() {
      expect($this->parser->getColumnsSearch())->toBe([]);
    });

    it("->getSearchableColumns()", function() {
      expect($this->parser->getSearchableColumns())->toBe([]);
    });

    it("->getDraw()", function() {
      expect($this->parser->getDraw())->toBe(null);
    });

    it("->getLimit()", function() {
      expect($this->parser->getLimit())->toBe(10);
    });

    it("->getOffset()", function() {
      expect($this->parser->getOffset())->toBe(1);
    });

    it("->getColumns()", function() {
      expect($this->parser->getColumns())->toBe([]);
    });

    it("->getColumnById()", function() {
      expect($this->parser->getColumnById(0))->toBe(null);
    });

    it("->getSearch()", function() {
      expect($this->parser->getSearch())->toBe([]);
    });

    it("->getOrder()", function() {
      expect($this->parser->getOrder())->toBe([]);
    });

    it("->getSearchValue()", function() {
      expect($this->parser->getSearchValue())->toBe('');
    });

  });

  describe("GET&POST", function() {

    it("should use POST", function() {

      $_GET['draw'] = 1;

      $parser = new \DataTables\ParamsParser(10);
      expect($parser)->toBeA('object');
      expect($parser->getDraw())->toBe(1);

    });

    it("should user GET", function() {

      $_SERVER['REQUEST_METHOD'] = 'POST';
      $_POST['draw'] = 2;

      $parser = new \DataTables\ParamsParser(10);
      expect($parser)->toBeA('object');
      expect($parser->getDraw())->toBe(2);

    });

  });

  describe("Pagination", function() {

    it('should correctly bind a pagination', function() {

      $_GET['start'] = 200;

      $parser = new \DataTables\ParamsParser(10);
      expect($parser->getPage())->toBe(21);

    });

    it('should correctly floor a page', function() {

      $_GET['start'] = 195;

      $parser = new \DataTables\ParamsParser(10);
      expect($parser->getPage())->toBe(20);

    });

  });

  describe("Search", function() {

    beforeEach(function() {

      $_GET = [
        'columns' => [
          [
            'data' => 'column1',
            'searchable' => "true",
            'search' => [
              'value' => "column_search"
            ]
          ],
          [
            'data' => 'column2',
            'searchable' => "false",
          ]
        ],
        'search'  => [
          'value' => 'global_search',
        ]
      ];

    });

    it("should set global and column search", function() {

      $parser = new \DataTables\ParamsParser(10);
      expect($parser->getSearchableColumns())->toBe(['column1']);
      expect(count($parser->getColumnsSearch()))->toBe(1);
      expect($parser->getSearchValue())->toBe('global_search');

    });

    it("should not fall on empty search in column", function() {

      unset($_GET['columns'][0]['search']['value']);
      $parser = new \DataTables\ParamsParser(10);
      expect(count($parser->getColumnsSearch()))->toBe(0);

    });

    it("should not fall on empty global search", function() {

      unset($_GET['search']['value']);
      $parser = new \DataTables\ParamsParser(10);
      expect($parser->getSearchValue())->toBe('');

    });

  });

  describe("Order", function() {

    beforeEach(function() {

      $_GET['order'] = [
        [
          'column' => "column1",
          'dir'    => "desc"
        ],
        [
          'column' => "column2",
          'dir'    => "asc"
        ]
      ];

    });

    it("should correctly parse a two-way order", function() {

      $parser = new \DataTables\ParamsParser(10);
      expect($parser->getOrder())->toBe([
        [
          'column' => "column1",
          'dir'    => "desc"
        ],
        [
          'column' => "column2",
          'dir'    => "asc"
        ]
      ]);

    });

  });


  afterEach(function() {

    unset($_GET);
    unset($_POST);
    unset($_SERVER);

  });

});
