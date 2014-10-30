<?php
define('BASE_DIR', dirname(dirname(__FILE__)) . '/');
require_once BASE_DIR . 'src/CreateForm.class.php';

$form = new CreateForm();
$form->run();