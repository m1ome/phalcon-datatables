<?php
namespace DataTables\Adapters;

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
            $check = strpos($item->$column, $search) !== false;
            if (!$check) break;
          }
        }

        if ($check) {
          return $item;
        }
      });

      $filtered = count($filter);

      if ($offset > 0) {
        $filter = array_slice($filter, $offset);
      }

      if ($limit > 0) {
        $filter = array_slice($filter, 0, $limit);
      }

      $items = array_map(function($item) {
        return $item->toArray();
      }, $filter);
    } else {
      $filtered = $total;
      if ($offset > 0) {
        $this->resultSet->seek($offset-1);
      }

      $items = [];

      while($this->resultSet->valid() && count($items) < $limit) {
        $items[] = $this->resultSet->current();
        $this->resultSet->next();
      }

      $items = array_map(function($item) {
        return $item->toArray();
      }, $items);
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

    return $this->formResponse([
      'total'     => $total,
      'filtered'  => $filtered,
      'data'      => $items,
    ]);
  }

  public function setResultSet($resultSet) {
    $this->resultSet = $resultSet;
  }

}
