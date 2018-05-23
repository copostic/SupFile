<?php
if (!defined('ENV'))
    define('ENV', getenv('SF_ENV'));
define('LIB', 'libraries/');
define('CACHE', 'cache/');
define('HELPERS', 'helpers/');
define('MODELS', 'models/');
define('VIEWS', PATH . 'views/');
define('CONTROLLERS', PATH . 'controllers/');

if (!defined('BASE_URL_PART'))
    define("BASE_URL_PART", 0);

if (!defined('DB_HOST'))
    define("DB_HOST", getenv('DB_HOST'));

if (!defined('DB_NAME'))
    define("DB_NAME", getenv('DB_NAME'));

if (!defined('DB_USER'))
    define("DB_USER", getenv('DB_USER'));

if (!defined('DB_PASSWORD'))
    define("DB_PASSWORD", getenv('DB_PASSWORD'));

