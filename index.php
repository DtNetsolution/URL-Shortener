<?php
define('BASE_DIR', dirname(__FILE__) . '/');
require_once BASE_DIR . 'src/url/GoAction.class.php';

$action = new GoAction();
$action->run();