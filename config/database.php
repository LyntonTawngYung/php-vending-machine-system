<?php

return [
    'host' => getenv('MYSQLHOST') ?: '127.0.0.1',
    'db' => getenv('MYSQLDATABASE') ?: 'vending_machine',
    'user' => getenv('MYSQLUSER') ?: 'root',
    'pass' => getenv('MYSQLPASSWORD') ?: 'root',
    'charset' => 'utf8mb4'
];
