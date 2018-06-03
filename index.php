<?php
session_start();
$session = session_id();

define('PATH', getcwd() . '/');
define('CONFIG', 'config/');
require_once(CONFIG . 'conf.php');
require_once(LIB . 'vendor/autoload.php');
require_once(MODELS . 'User.php');
require_once(LIB . 'Router.php');
require_once(LIB . 'helpers.php');
require_once(LIB . 'Db.php');
require_once(CONFIG . 'conf.hybridauth.php');
$Router = new Router();
$url = $Router->getUrl();
$action = $Router->getAction();
$page = $Router->getPage();
$smarty = new Smarty();
$db = DB::getInstance();
$user = User::getInstance();

$isConnected = !empty($_SESSION['connected']) ? 1 : 0;

$smarty->assign('session', $_SESSION);
$smarty->assign('isConnected', $isConnected);

require_once CONTROLLERS . $Router->getController() . '.php';
