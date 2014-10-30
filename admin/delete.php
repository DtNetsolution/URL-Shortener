<?php
define('BASE_DIR', dirname(dirname(__FILE__)) . '/');
require_once BASE_DIR . 'src/url/UrlDeleteAction.class.php';

$action = new UrlDeleteAction();
$action->run();