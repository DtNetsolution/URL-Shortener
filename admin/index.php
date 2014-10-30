<?php
define('BASE_DIR', dirname(dirname(__FILE__)) . '/');
require_once BASE_DIR . 'src/ListPage.class.php';

$page = new ListPage();
$page->run();