<?php
define('TESTING', true);
defined('ROOT') or define('ROOT', dirname(pathinfo(__FILE__, PATHINFO_DIRNAME)) . DIRECTORY_SEPARATOR);
define('ENVIRONMENT', 'testing');
require(ROOT . 'index.php');

