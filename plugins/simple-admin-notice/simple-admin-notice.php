<?php
/*
Plugin Name: Simple Admin Notice
Description: Plugin hiển thị thông báo trên trang admin và hỗ trợ shortcode.
Version: 1.0
Author: Nguyen Thanh Danh
License: GPL2
Text Domain: simple-admin-notice
Domain Path: /languages
*/

// Ngăn chặn truy cập trực tiếp
if (!defined('ABSPATH')) {
    exit;
}

// Định nghĩa hằng số quan trọng
define('SIMPLE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SIMPLE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Nạp các file cấu hình cần thiết
require_once SIMPLE_PLUGIN_DIR . 'includes/functions.php';

// Kích hoạt plugin
register_activation_hook(__FILE__, 'simple_activate');
function simple_activate()
{
    add_option('simple_plugin_activated', 'yes');
}

// Hủy kích hoạt plugin
register_deactivation_hook(__FILE__, 'simple_deactivate');
function simple_deactivate()
{
    delete_option('simple_plugin_activated');
}

// Xóa dữ liệu khi gỡ plugin
register_uninstall_hook(__FILE__, 'simple_uninstall');
function simple_uninstall()
{
    delete_option('simple_plugin_activated');
}

// Nạp CSS & JS
function simple_assets()
{
    wp_enqueue_style('simple-admin-style', SIMPLE_PLUGIN_URL . 'assets/css/style.css');
    wp_enqueue_script('simple-admin-script', SIMPLE_PLUGIN_URL . 'assets/js/script.js', ['jquery'], false, true);
}
add_action('wp_enqueue_scripts', 'simple_assets');
