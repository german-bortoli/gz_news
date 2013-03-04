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
$from_form = (bool) Params::getParam('from_form');

if ($from_form) {
	$redirect_url = GzNewsUtils::getAdminIndexUrl();
}

$success = $dao->deleteByPrimaryKey($gn_id);

if ($success) {
	$model->removeFiles($gn_id);
	osc_add_flash_ok_message(__('The item was deleted successfully', 'gz_news'), 'admin');
} else {
	osc_add_flash_error_message('There were a problem while deleting the item', 'admin');
}

$model->redirectTo($redirect_url);