<?php
/*
Plugin Name: CPT Project Manager
Description: Plugin quản lý dự án với Custom Post Type trong WordPress.
Version: 1.0
Author: Nguyen Thanh Danh
License: GPL2
Text Domain: cpt-project-manager
Domain Path: /languages
*/

/**
 * Ngăn chặn truy cập trực tiếp
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Định nghĩa hằng số quan trọng
 */
define('CPT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CPT_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Nạp các cấu hình cần thiết
 */
require_once CPT_PLUGIN_DIR . 'inc/functions.php';

/**
 * Kích hoạt plugin
 */
function cpt_activate()
{
    //
}
register_activation_hook(__FILE__, 'cpt_activate');

/**
 * Hủy kích hoạt plugin
 */
function cpt_deactivate()
{
    //
}
register_deactivation_hook(__FILE__, 'cpt_deactivate');
