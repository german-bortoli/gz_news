<?php

/**
 * Various functions used in common into the system
 *
 * @author Bortoli German
 */
if (!defined('ABS_PATH'))
	exit('ABS_PATH is not loaded. Direct access is not allowed.');

class GzNewsUtils {
	
	const MAX_ITEMS_PER_PAGE = 5;
	const ENABLE_MOD_REWRITE = TRUE;

	static public function camelizeString($string) {
		$string = str_replace('-', '_', $string);
		$string = implode('', explode(' ', ucwords(implode(' ', explode('_', $string)))));
		return $string;
	}

	static public function getAdminIndexUrl() {
		return osc_admin_render_plugin_url(GZ_NEWS_PLUGIN_FOLDER . 'admin/index.php');
	}
	static public function getIndexUrl() {
		if (self::isModRewriteEnabled()) {
			return osc_base_url().'news';
		} else {
			return  GZ_NEWS_URL . 'pages/index.php';
		}
	}

	static public function getAdminAddUrl() {
		return osc_admin_render_plugin_url(GZ_NEWS_PLUGIN_FOLDER . 'admin/add.php');
	}

	static public function getAdminEditUrl($gn_id) {
		return self::getAdminAddUrl() . '&gn_id=' . $gn_id;
	}

	static public function getAdminDeleteUrl($gn_id = 0) {
		return osc_admin_render_plugin_url(GZ_NEWS_PLUGIN_FOLDER . 'admin/add.php');
	}

	static public function getActionAddNewUrl($gn_id = 0) {
		$url = GZ_NEWS_URL . 'actions/edit_new_item.php';

		$gn_id = (int) $gn_id;
		if ($gn_id > 0) {
			$url = $url . "?gn_id={$gn_id}";
		}


		return $url;
	}

	static public function getActionDeleteUrl($gn_id = 0) {
		$url = GZ_NEWS_URL . 'actions/delete_item.php';

		$gn_id = (int) $gn_id;
		if ($gn_id > 0) {
			$url = $url . "?gn_id={$gn_id}";
		}


		return $url;
	}

	static public function getActionDeleteImageUrl($gn_id = 0) {
		$url = GZ_NEWS_URL . 'actions/delete_images.php';

		$gn_id = (int) $gn_id;
		if ($gn_id > 0) {
			$url = $url . "?gn_id={$gn_id}";
		}


		return $url;
	}

	static public function getItemUrl(& $item) {
		$gn_id = $item['gn_id'];
		$gn_title = $item['gn_title'];

		$friendly_title = osc_sanitizeString(osc_apply_filter('slug', $gn_title));

		$item_url =  GZ_NEWS_URL . 'pages/item.php';
		
		if (self::isModRewriteEnabled()) {
			$item_url = osc_base_url()."news/read/{$gn_id}/{$friendly_title}.html";
		} else {
			$item_url = $item_url . "?i={$gn_id}&title={$friendly_title}";
		}

		return $item_url;
	}

	static public function getImagesUrl($gn_id) {
		$news_folder = osc_content_path() . 'uploads/news/';
		$item_folder = "{$news_folder}{$gn_id}/";

		if (!file_exists($item_folder)) {
			return FALSE;
		}

		$item_url_path = osc_base_url() . "oc-content/uploads/news/{$gn_id}/";

		$sizes = array(
			'original',
			'normal',
			'preview',
			'thumb',
		);

		$images = array();
		foreach ($sizes as $size) {
			$image = "{$item_folder}{$size}.jpg";
			if (file_exists($image)) {
				$images[$size] = "{$item_url_path}{$size}.jpg";
			}
		}

		return $images;
	}

	static public function getItemExcerpt(& $item, $lenght = 400) {
		
		$text = $item['gn_description'];
		
		if (strlen($text) > $lenght) {
			$text = strip_tags($text);
			$text = substr($text, 0, $lenght);
			$text = substr($text, 0, strrpos($text, " "));
			$etc = " ...";
			$text = $text . $etc;
		}
		return $text;
	}
	
	static public function getItemTitleExcerpt(& $item, $lenght = 40) {
		
		$text = $item['gn_title'];
		
		if (strlen($text) > $lenght) {
			$text = strip_tags($text);
			$text = substr($text, 0, $lenght);
			$text = substr($text, 0, strrpos($text, " "));
			$etc = " ...";
			$text = $text . $etc;
		}
		return $text;
	}
	
	static public function getMaxItemsPerPage() {
		return self::MAX_ITEMS_PER_PAGE;
	}
	
	static public function isModRewriteEnabled() {
		return self::ENABLE_MOD_REWRITE;
	}

}
