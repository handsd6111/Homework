<?php
define("CurrentPath", __DIR__);
$sqlInfo = json_decode(file_get_contents(CurrentPath . "/DBconfig.json"), true)[0];
define('dbHost', $sqlInfo['host']);
define('dbUser', $sqlInfo['user']);
define('dbPassword', $sqlInfo['password']);
define('dbPort', $sqlInfo['port']);
define('dbName', $sqlInfo['db'][0]['name']);