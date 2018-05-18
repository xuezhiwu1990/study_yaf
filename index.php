<?php

ini_set('display_errors','On');
error_reporting(E_ALL);

define('APPLICATION_PATH', dirname(__FILE__));

$app = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");

//echo APPLICATION_PATH . "/conf/application.ini";
//var_dump($application);
$a = $app->bootstrap()->run();
