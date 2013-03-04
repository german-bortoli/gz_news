<?php

/*
  Plugin Name: GZ News
  Plugin URI: http://www.github.com/germanaz0
  Description: This plugin allows admins to upload them news like a blog post.
  Version: 1.0
  Author: Germanaz0
  Author URI: http://www.github.com/germanaz0
 */

define('GZ_NEWS_PLUGIN_FOLDER', osc_plugin_folder(__FILE__));
define('GZ_NEWS_PATH', dirname(__FILE__) . '/');
define('GZ_NEWS_URL', osc_plugin_url(__FILE__));

gz_news_autoload();

/**
 * Autoload function to automatical includes files and classes
 */
function gz_news_autoload() {
    $classes = array(
		'GzNewsDao',
        'GzNewsUtils',
        'GzNewsModel',
        'GzNewsForm',
    );
    
    $class_path = GZ_NEWS_PATH.'classes/';
    
    foreach($classes as $class) {
        $filename = $class_path.$class.'.php';
        
        if (file_exists($filename)) {
            include_once($filename);
        }
    }
}


/**
 * Create news table after module install
 */
function gz_news_call_after_install() {
    $conn = getConnection();
    $conn->autocommit(FALSE);
    try {
        $path = osc_plugin_resource('gz_news/schema/news.sql');
        $sql = file_get_contents($path);
        $conn->osc_dbImportSQL($sql);
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        echo $e->getMessage();
    }
    $conn->autocommit(TRUE);
}


/**
 * Remove news table after module uninstall, deprecated because we need to keep data
 * @deprecated since version 1.0
 */
function gz_news_call_after_uninstall() {
    $conn = getConnection();
    $conn->autocommit(FALSE);
    try {
        $conn->osc_dbExec('DROP TABLE %st_news', DB_TABLE_PREFIX);
    } catch (Exception $e) {
        $conn->rollback();
        echo $e->getMessage();
    }
    $conn->autocommit(TRUE);
}

/**
 * Includes stylesheets and javascript files to templates
 */
function gz_news_add_header() {
    osc_render_file(GZ_NEWS_PLUGIN_FOLDER.'views/news_header.php');
}




/**
 * Generate admin menu page to list news
 */
osc_add_admin_menu_page(
        __('News', 'gz_news'), GzNewsUtils::getAdminIndexUrl(), 'gz_news'
);

/**
 * Register a submenu into admin page, to add news
 */
osc_add_admin_submenu_page('gz_news', __('Add News', 'gz_news'), GzNewsUtils::getAdminAddUrl(), 'gz_news_add');

osc_add_hook('admin_header', 'gz_news_add_header');
osc_add_hook('header', 'gz_news_add_header');

/** Remove the uninstall hook to do not lose data
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'gz_news_call_after_uninstall');
 */
osc_register_plugin(osc_plugin_path(__FILE__), 'gz_news_call_after_install');