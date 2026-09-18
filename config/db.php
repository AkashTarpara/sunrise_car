<?php

$dbHost = $_ENV['DB_HOST'] ?: '127.0.0.1';
$dbName = $_ENV['DB_NAME'] ?: 'sunrise_car';
$dbUser = $_ENV['DB_USER'] ?: 'root';
$dbPassword = $_ENV['DB_PASSWORD'] ?: 'V9!qR7#tL2@xP8$kM5&zN4';

echo '<pre>';
echo "DB Host: " . $dbHost . PHP_EOL;
echo "DB Name: " . $dbName . PHP_EOL;
echo "DB User: " . $dbUser . PHP_EOL;
echo "DB Password: " . $dbPassword . PHP_EOL;
// echo '</pre>'; exit;

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . $dbHost . ';dbname=' . $dbName,
    'username' => $dbUser,
    'password' => $dbPassword,
    'charset' => 'utf8mb4',
    'attributes' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));",
    ],

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
