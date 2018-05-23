<?php
session_start();
$session = session_id();

define('PATH', getcwd() . '/');
define('CONFIG', 'config/');
require_once(CONFIG . 'conf.php');
require_once(LIB . 'vendor/autoload.php');

$smarty = new Smarty();