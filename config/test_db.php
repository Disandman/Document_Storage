<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = $_ENV['TEST_DB_DSN'];
$db['username'] = $_ENV['TEST_DB_USERNAME'];
$db['password'] = $_ENV['TEST_DB_PASSWORD'];

return $db;
