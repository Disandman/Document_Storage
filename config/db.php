<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => $_ENV['DB_DNS'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => $_ENV['DB_CHARSET'],
];