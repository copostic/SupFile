<?php
session_start();
$session = session_id();

define('PATH', getcwd() . '/');
define('CONFIG', 'config/');
require_once(CONFIG . 'conf.php');
require_once(LIB . 'vendor/autoload.php');
require_once(LIB . 'Router.php');
require_once(LIB . 'Db.php');
require_once(CONFIG . 'conf.hybridauth.php');
$Router = new Router();
$url = $Router->getUrl();
$action = $Router->getAction();
$page = $Router->getPage();
$smarty = new Smarty();
$db = new DB();
require_once CONTROLLERS . $Router->getController() . '.php';
