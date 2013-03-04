<?php

/*
  Plugin Name: GZ NEWS
  Plugin URI: http://www.github.com/germanaz0
  Description: This plugin allows admins to upload them news like a blog post.
  Version: 1.0
  Author: Germanaz0
  Author URI: http://www.github.com/germanaz0
 */

if (!defined('ABS_PATH'))
	exit('ABS_PATH is not loaded. Direct access is not allowed.');

/**
 * This class handle the model of the system
 *
 * @author Bortoli German
 */
class GzNewsModel extends BaseModel {

	public function doModel() {
		
	}

	//hopefully generic...
	function doView($file) {
		osc_run_hook("before_html");
		osc_current_web_theme_path($file);
		Session::newInstance()->_clearVariables();
		osc_run_hook("after_html");
	}

	public function processEditForm($new_item = array(), $files = array()) {
		$param_name = 'new_item';

		Session::newInstance()->_setForm($param_name, $new_item);
		// keep values on session
		Session::newInstance()->_keepForm($param_name);

		$default_fields = array(
			'gn_id' => NULL,
			'gn_title' => '',
			'gn_description' => '',
			'gn_tags' => '',
			'gn_lang' => osc_current_admin_locale(),
		);

		$new_item = array_merge($default_fields, $new_item);

		if (!$new_item['gn_title']) {
			throw new Exception(__('The title must not be empty', 'gz_news'));
		}
		if (!$new_item['gn_description']) {
			throw new Exception(__('The description must not be empty', 'gz_news'));
		}

		$dao = GzNewsDao::newInstance();
		
		if ($new_item['gn_id']) {
			$item_exists = $dao->findByPrimaryKey($new_item['gn_id']);
			if ($item_exists == FALSE) {
				throw new Exception(__('The new does not exists anymore', 'gz_news'));
			}
		}
		
		$success = $dao->save($new_item);

		if ($success) {

			if (!empty($files)) {
				$this->uploadFiles($success, $files);
			}

			Session::newInstance()->_dropKeepForm($param_name);
		}

		return $success;
	}

	public function uploadFiles($gn_id, $files = array()) {

		if (empty($files)) {
			return TRUE;
		}

		$gn_id = (int) $gn_id;

		if (empty($gn_id)) {
			return FALSE;
		}

		$news_folder = osc_content_path() . 'uploads/news/';
		if (file_exists($news_folder) == FALSE) {
			mkdir($news_folder, 0777, TRUE);
		}

		$item_folder = "{$news_folder}{$gn_id}/";
		if (file_exists($item_folder) == FALSE) {
			mkdir($item_folder, 0777, TRUE);
		}

		$valid_images = array(
			'image/jpeg',
			'image/pjpeg',
			'image/png',
			'image/x-png',
			'image/gif'
		);

		if ($files['error'] != 0) {
			return FALSE;
		}


		if (!in_array($files['type'], $valid_images)) {
			return FALSE;
		}

		if (empty($files['tmp_name'])) {
			return FALSE;
		}

		ImageResizer::fromFile($files['tmp_name'])->saveToFile($item_folder . 'original.jpg');

		$size = explode('x', osc_normal_dimensions());
		ImageResizer::fromFile($files['tmp_name'])->resizeTo($size[0], $size[1])->saveToFile($item_folder . 'normal.jpg');

		$size = explode('x', osc_preview_dimensions());
		ImageResizer::fromFile($files['tmp_name'])->resizeTo($size[0], $size[1])->saveToFile($item_folder . 'preview.jpg');

		$size = explode('x', osc_thumbnail_dimensions());
		ImageResizer::fromFile($files['tmp_name'])->resizeTo($size[0], $size[1])->saveToFile($item_folder . 'thumb.jpg');
	}

	public function removeFiles($gn_id) {

		$gn_id = (int) $gn_id;

		if (empty($gn_id)) {
			return FALSE;
		}

		$news_folder = osc_content_path() . 'uploads/news/';
		if (file_exists($news_folder) == FALSE) {
			return TRUE;
		}

		$item_folder = "{$news_folder}{$gn_id}/";
		if (file_exists($item_folder) == FALSE) {
			return TRUE;
		}

		$files = array_diff(scandir($item_folder), array('.', '..'));
		foreach ($files as $file) {
			unlink("{$item_folder}/{$file}");
		}
		
		return rmdir($item_folder);
	}

}
