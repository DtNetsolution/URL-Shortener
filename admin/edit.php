<?php
define('BASE_DIR', dirname(dirname(__FILE__)) . '/');
require_once BASE_DIR . 'src/url/UrlEditForm.class.php';

$form = new UrlEditForm();
$form->run();
