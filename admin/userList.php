<?php
define('BASE_DIR', dirname(dirname(__FILE__)) . '/');
require_once BASE_DIR . 'src/user/UserListPage.class.php';

$page = new UserListPage();
$page->run();
