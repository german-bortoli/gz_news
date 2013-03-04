<?php

define('ABS_PATH', dirname(dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))))) . '/');
define('OC_ADMIN', true);

require_once ABS_PATH . 'oc-load.php';


if (osc_is_admin_user_logged_in() == FALSE) {
    die;
}

$model = new GzNewsModel();

$redirect_url = $_SERVER['HTTP_REFERER'];

$fields = Params::getParam('new_item');
$gn_id = (int) Params::getParam('gn_id');

$files = Params::getFiles('image');

if ($gn_id) {
	$fields['gn_id'] = $gn_id;
}

try {
    $success = $model->processEditForm($fields, $files);
} catch (Exception $exc) {
    osc_add_flash_error_message($exc->getMessage(), 'admin');
}

if ($success) {
	
    osc_add_flash_ok_message(__('The new was loaded successfully', 'gz_news'), 'admin');
    $redirect_url = GzNewsUtils::getAdminIndexUrl();
}


$model->redirectTo($redirect_url);