<?php
session_start();
$session = session_id();

define('PATH', getcwd() . '/');
define('CONFIG', 'config/');
require_once(CONFIG . 'conf.php');
require_once(LIB . 'vendor/autoload.php');
require_once (LIB . 'Router.php');
$Router = new Router();
$url = $Router->getUrl();
$action = $Router->getAction();
$smarty = new Smarty();

require_once CONTROLLERS . $Router->getController() . '.php';
