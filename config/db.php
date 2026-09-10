<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . ($_ENV['DB_HOST'] ?: '127.0.0.1') . ';dbname=' . ($_ENV['DB_NAME'] ?: ''),
    'username' => $_ENV['DB_USER'] ?: '',
    'password' => $_ENV['DB_PASSWORD'] ?: '',
    'charset' => 'utf8mb4',
    'attributes' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));",
    ],

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
