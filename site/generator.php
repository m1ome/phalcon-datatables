<?php
require __DIR__ . '/vendor/autoload.php';

$db = new \Phalcon\Db\Adapter\Pdo\Sqlite([
  'dbname' => __DIR__ . '/db.sqlite',
]);

// Generating 1000 record in user table
$db->query("DELETE FROM user;");
$balances = [100, 200, 500];
for($i=0; $i<1000; $i++) {
  $faker = Faker\Factory::create();

  $name = $faker->userName;
  $email = $faker->email;

  $balance = $balances[array_rand($balances)];

  $db->query("INSERT INTO user VALUES({$i}, '{$name}', '{$email}', '{$balance}')");
  echo 'Insert record [' . $i . '/1000]' . PHP_EOL;
}

echo 'All done!' . PHP_EOL;
