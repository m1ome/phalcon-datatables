<?php

namespace DataTables\Adapters;

use DataTables\ParamsParser;

abstract class AdapterInterface {

  protected $parser  = null;
  protected $columns = [];
  protected $lentgh  = 30;

  public function __construct($length) {
    $this->length = $length;
  }

  abstract public function getResponse();

  public function setParser(ParamsParser $parser) {
    $this->parser = $parser;
  }

  public function setColumns(array $columns) {
    $this->columns = $columns;
  }

  public function getColumns() {
    return $this->columns;
  }

  public function columnExists($column) {
    return in_array($column, $this->columns);
  }

  public function getParser() {
    return $this->parser;
  }

  public function formResponse($options) {
    $defaults = [
      'total'     => 0,
      'filtered'  => 0,
      'data'      => []
    ];
    $options += $defaults;

    $response = [];
    $response['draw'] = $this->parser->getDraw();
    $response['recordsTotal'] = $options['total'];
    $response['recordsFiltered'] = $options['filtered'];

    if (count($options['data'])) {
      foreach($options['data'] as $item) {
        if (isset($item['id'])) {
          $item['DT_RowId'] = $item['id'];
        }

        $response['data'][] = $item;
      }
    } else {
      $response['data'] = [];
    }

    return $response;
  }

  public function sanitaze($string) {
    return mb_substr($string, 0, $this->length);
  }

  public function bind($case, $closure) {
    switch($case) {
      case "global_search":
        $search = $this->parser->getSearchValue();
        if (!mb_strlen($search)) return;

        foreach($this->parser->getSearchableColumns() as $column) {
          if (!$this->columnExists($column)) continue;
          $closure($column, $this->sanitaze($search));
        }
        break;
      case "column_search":
        $columnSearch = $this->parser->getColumnsSearch();
        if (!$columnSearch) return;

        foreach($columnSearch as $key => $column) {
          if (!$this->columnExists($column['data'])) continue;
          $closure($column['data'], $this->sanitaze($column['search']['value']));
        }
        break;
      case "order":
        $order = $this->parser->getOrder();
        if (!$order) return;

        $orderArray = [];

        foreach($order as $orderBy) {
          if (!isset($orderBy['dir']) || !isset($orderBy['column'])) continue;
          $orderDir = $orderBy['dir'];

          $column = $this->parser->getColumnById($orderBy['column']);
          if (is_null($column) || !$this->columnExists($column)) continue;

          $orderArray[] = "{$column} {$orderDir}";
        }

        $closure($orderArray);
        break;
      default:
        throw new \Exception('Unknown bind type');
    }

  }

}
