<?php
namespace DataTables\Adapters;

use Phalcon\Mvc\Model\Resultset as PhalconResultSet;

class ResultSet extends AdapterInterface {

  protected $resultSet;
  protected $filter = [];
  protected $order  = [];

  public function getResponse() {
    $limit  = $this->parser->getLimit();
    $offset = $this->parser->getOffset();
    $total  = $this->resultSet->count();

    $this->bind('global_search', function($column, $search) {
      $this->filter[$column][] = $search;
    });

    $this->bind('column_search', function($column, $search) {
      $this->filter[$column][] = $search;
    });

    $this->bind('order', function($order) {
      $this->order = $order;
    });

    if(count($this->filter)) {
      $filter = $this->resultSet->filter(function($item){
        $check = true;

        foreach($this->filter as $column=>$filters) {
          foreach($filters as $search) {
            $check = (strpos($item->$column, $search) !== false);
            if (!$check) break 2;
          }
        }

        if ($check) {
          return $item;
        }
      });

      $filtered = count($filter);
      $items = array_map(function($item) {
        return $item->toArray();
      }, $filter);
    } else {
      $filtered = $total;
      $this->resultSet->setHydrateMode(PhalconResultSet::HYDRATE_RECORDS);
      $items = array_map('array_unique', $this->resultSet->toArray());
    }

    if ($this->order) {
      $args = [];

      foreach($this->order as $order) {
        $tmp = [];
        list($column, $dir) = explode(' ', $order);

        foreach($items as $key=>$item) {
          $tmp[$key] = $item[$column];
        }

        $args[] = $tmp;
        $args[] = ($dir == 'desc') ? SORT_DESC : SORT_ASC;
      }

      $args[] = &$items;
      call_user_func_array('array_multisort', $args);
    }

    if ($offset > 1) {
      $items = array_slice($items, ($offset - 1));
    }

    if ($limit) {
      $items = array_slice($items, 0, $limit);
    }

    return $this->formResponse([
      'total'     => (int)$total,
      'filtered'  => (int)$filtered,
      'data'      => $items,
    ]);
  }

  public function setResultSet($resultSet) {
    $this->resultSet = $resultSet;
  }

}
