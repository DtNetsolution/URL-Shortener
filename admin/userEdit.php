<?php
define('BASE_DIR', dirname(dirname(__FILE__)) . '/');
require_once BASE_DIR . 'src/user/UserEditForm.class.php';

$form = new UserEditForm();
$form->run();
