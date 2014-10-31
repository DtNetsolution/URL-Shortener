<?php
define('BASE_DIR', dirname(dirname(__FILE__)) . '/');
require_once BASE_DIR . 'src/url/UrlCreateForm.class.php';

$form = new UrlCreateForm();
$form->run();
