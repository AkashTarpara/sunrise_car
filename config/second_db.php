<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . ($_ENV['SECOND_DB_HOST'] ?: '127.0.0.1') . ';port=' . ($_ENV['SECOND_DB_PORT'] ?: 3306) . ';dbname=' . ($_ENV['SECOND_DB_NAME'] ?: ($_ENV['DB_NAME'] ?: '')),
    'username' => $_ENV['SECOND_DB_USER'] ?: '',
    'password' => $_ENV['SECOND_DB_PASSWORD'] ?: '',
    'charset' => 'utf8mb4',
    'attributes' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));",
    ],
];
