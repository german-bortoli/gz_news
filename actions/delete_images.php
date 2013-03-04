<?php

define('ABS_PATH', dirname(dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))))) . '/');
define('OC_ADMIN', true);

require_once ABS_PATH . 'oc-load.php';


if (osc_is_admin_user_logged_in() == FALSE) {
    die;
}
$model = new GzNewsModel();
$dao = GzNewsDao::newInstance();

$redirect_url = $_SERVER['HTTP_REFERER'];

$gn_id = (int) Params::getParam('gn_id');

$success = $model->removeFiles($gn_id);

if ($success) {
	osc_add_flash_ok_message(__('The image was cleared successfully', 'gz_news'), 'admin');
} else {
	osc_add_flash_error_message('There were a problem while clearing images', 'admin');
}

$model->redirectTo($redirect_url);