<?php
define('BASE_DIR', dirname(dirname(__FILE__)) . '/');
require_once BASE_DIR . 'src/user/UserDeleteAction.class.php';

$action = new UserDeleteAction();
$action->run();
