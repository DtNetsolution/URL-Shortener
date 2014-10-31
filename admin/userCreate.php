<?php
define('BASE_DIR', dirname(dirname(__FILE__)) . '/');
require_once BASE_DIR . 'src/user/UserCreateForm.class.php';

$form = new UserCreateForm();
$form->run();
