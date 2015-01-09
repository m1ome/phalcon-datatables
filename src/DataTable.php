<?php
namespace DataTables;

interface DataTable {
  public function __construct();
  public function getContent();
}