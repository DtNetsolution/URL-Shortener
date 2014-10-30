<?php
define('BASE_DIR', dirname(dirname(__FILE__)) . '/');
require_once BASE_DIR . 'src/DeleteAction.class.php';

$action = new DeleteAction();
$action->run();