<?php
namespace DataTables;

use Phalcon\Mvc\User\Component;

class ParamsParser extends Component{
  protected $draw;
  protected $start;
  protected $length;
  protected $columns;
  protected $search;
  protected $order;
  protected $page;

  public function __construct($limit = 20) {
    $requestParams = $this->request->isPost() ? $this->request->getPost() : $this->request->get();

    // Parsing starting params
    $this->draw   = isset($requestParams['draw']) ? $requestParams['draw'] : null;
    $this->start  = isset($requestParams['start']) ? $requestParams['start'] : 1;
    $this->length = isset($requestParams['length']) ? $requestParams['length'] : $limit;

    // Parsing columns and e.t.c
    $this->columns = isset($requestParams['columns']) ? $requestParams['columns'] : [];
    $this->search  = isset($requestParams['search']) ? $requestParams['search'] : [];
    $this->order   = isset($requestParams['order']) ? $requestParams['order'] : [];

    // Columns for pagination
    $this->page = ceil($this->start / $this->length) + 1;
  }

  public function getSearchableColumns() {
    return array_filter(array_map(function($item) {
      return ($item['searchable'] === "true") ? $item['data'] : null;
    }, $this->columns));
  }

  public function getPage() {
    return $this->page;
  }

  public function getDraw() {
    return $this->draw;
  }

  public function getLimit() {
    return $this->length;
  }

  public function getOffset() {
    return $this->start;
  }

  public function getColumns() {
    return $this->columns;
  }

  public function getColumnById($id) {
    return isset($this->columns[$id]['data']) ? $this->columns[$id]['data'] : null;
  }

  public function getSearch() {
    return $this->search;
  }

  public function getOrder() {
    return current($this->order);
  }

  public function getSearchValue() {
    return isset($this->search['value']) ? $this->search['value'] : '';
  }
}