<?php
require 'vendor/autoload.php';
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
$app = require 'app/Config/Paths.php';
require 'system/bootstrap.php';

$db = \Config\Database::connect();
$tables = $db->listTables();
foreach($tables as $t) {
    echo $t . PHP_EOL;
}
