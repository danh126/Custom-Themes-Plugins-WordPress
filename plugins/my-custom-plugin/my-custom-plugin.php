<?php
/*
Plugin Name: My Custom Plugin
Description: Plugin thử nghiệm custom hook.
Version: 1.0
Author: Nguyen Thanh Danh
License: GPL2
Text Domain: my-custom-plugin
Domain Path: /languages
*/

// Ngăn chặn truy cập trực tiếp
if (!defined('ABSPATH')) {
    exit;
}

// Định nghĩa hằng số quan trọng
define('MY_CUSTOM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MY_CUSTOM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Nạp các file cấu hình cần thiết
require_once MY_CUSTOM_PLUGIN_DIR . 'inc/functions.php';

// Kích hoạt plugin
register_activation_hook(__FILE__, 'my_custom_activate');
function my_custom_activate()
{
    add_option('my_custom_plugin_activated', 'yes');
}

// Hủy kích hoạt plugin
register_deactivation_hook(__FILE__, 'my_custom_deactivate');
function my_custom_deactivate()
{
    delete_option('my_custom_plugin_activated');
}

// Xóa dữ liệu khi gỡ plugin
register_uninstall_hook(__FILE__, 'my_custom_uninstall');
function my_custom_uninstall()
{
    delete_option('my_custom_plugin_activated');
}


// Tạo custom hook
function my_plugin_custom_function()
{
    do_action('my_plugin_custom_action'); // Gọi custom hook khi action xảy ra
}

// Tạo trang admin để kích hoạt hook
function custom_admin_page()
{
    add_menu_page('Custom Hook', 'Custom Hook', 'manage_options', 'custom-hook', function () {
        $hook_url = admin_url('admin.php?page=custom-hook&run_custom_hook=1');

        echo '<h1>Click vào đây để chạy Custom Hook</h1>';
        echo '<a href="' . esc_url($hook_url) . '" class="button button-primary">Chạy Hook</a>';

        if (isset($_GET['run_custom_hook'])) {
            do_action('my_plugin_custom_action');
            echo '<p><strong>Hook đã được thực thi!</strong></p>';
        }
    });
}
add_action('admin_menu', 'custom_admin_page');
