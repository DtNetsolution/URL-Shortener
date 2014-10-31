<?php
define('BASE_DIR', dirname(dirname(__FILE__)) . '/');
require_once BASE_DIR . 'src/url/UrlListPage.class.php';

$page = new UrlListPage();
$page->run();
