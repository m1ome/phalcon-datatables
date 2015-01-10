<?php
namespace DataTables\Adapters;

use DataTables\DataTable;
use DataTables\ParamsParser;
use Phalcon\DI;
use Phalcon\Http\Response;
use Phalcon\Paginator\Adapter\QueryBuilder as PQueryBuilder;
use Phalcon\Mvc\Model\Query\Builder;

class QueryBuilder extends Builder implements DataTable {
  /** @var \DataTables\ParamsParser */
  protected $params;
  /** @var \Phalcon\DiInterface  */
  protected $di;

  public function __construct($params = null) {
    parent::__construct($params);

    $this->params = new ParamsParser();
    $this->di     = DI::getDefault();
  }

  public function getResponse() {
    $builder = new PQueryBuilder(['builder' => $this, 'page' => 1, 'limit' => 1]);
    $totalItems = $builder->getPaginate()->total_items;

    // Search bindings
    $availableColumns = array_map('trim', explode(',', $this->getColumns()));
    // Global search
    $search = $this->params->getSearchValue();

    if (strlen($search)) {
      foreach($this->params->getSearchableColumns() as $column) {
        if (in_array($column, $availableColumns)) {
          $this->orWhere("{$column} LIKE ?0", ["%{$search}%"]);
        }
      }
    }

    // Column-based search
    $columnSearch = $this->params->getColumnsSearch();
    if ($columnSearch) {
      foreach($columnSearch as $key => $column) {
        if (in_array($column['data'], $availableColumns)) {
          $this->andWhere("{$column['data']} LIKE :key_{$key}:", ["key_{$key}" => "%{$column['search']['value']}%"]);
        }
      }
    }

    // Ordering
    $order = $this->params->getOrder();

    if($order) {
      $orderArray = [];

      foreach($order as $orderBy) {
        $columnId = $orderBy['column'];
        $orderDir = $orderBy['dir'];

        $column = $this->params->getColumnById($columnId);
        if (!is_null($column) && in_array($column, $availableColumns)) {
          $orderArray[] = "{$column} {$orderDir}";
        }
      }

      $this->orderBy(implode(', ', $orderArray));
    }

    $builder = new PQueryBuilder([
      'builder' => $this,
      'page' => $this->params->getPage(),
      'limit' => $this->params->getLimit()
    ]);
    $filteredBuilder = $builder->getPaginate();

    $response = [];
    $response['draw']  = $this->params->getDraw();
    $response['recordsTotal'] = $totalItems;
    $response['recordsFiltered'] = $filteredBuilder->total_items;
    $response['data'] = $filteredBuilder->items->toArray();
    $response['query'] = $this->getPhql();

    $this->di->get('view')->disable();

    $responseJSON = new Response();
    $responseJSON->setContentType('application/json', 'utf8');
    $responseJSON->setJsonContent($response);

    return $responseJSON;
  }
}