<?php
namespace Phalcon\Test\Fixtures;

class User {
  public static function get() {
    $fixtures = [];
    for ($i=0; $i<50; $i++) {
      $fixtures[] = "({$i}, 'user#{$i}', 'example{$i}.example.com')";
    }

    return $fixtures;
  }
}