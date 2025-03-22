<?php
/*
Plugin Name: Custom Product Manager
Description: Plugin quản lý sản phẩm với Custom Table trong WordPress.
Version: 1.0
Author: Nguyen Thanh Danh
License: GPL2
Text Domain: custom-product-manager
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
define('CPM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CPM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Nạp các file cấu hình cần thiết
// require_once CPM_PLUGIN_DIR . 'inc/functions.php';

/**
 * Nhúng CDN vào Plugin
 */
function cpm_enqueue_cdn()
{
    // Bootstrap CSS 
    wp_enqueue_style('bootstrap-css', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css', array(), '5.3.3');

    // Bootstrap JS
    wp_enqueue_script('bootstrap-js', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js', array('jquery'), '5.3.3', true);
}
add_action('admin_enqueue_scripts', 'cpm_enqueue_cdn'); // Load trên admin dashboard

/**
 * Kích hoạt plugin & tạo table custom products
 */
register_activation_hook(__FILE__, 'cpm_activate');
function cpm_activate()
{
    // Thêm option xóa dữ liệu khi gỡ plugin
    add_option('cpm_delete_data_on_uninstall', 'no');

    // Tạo table custom products
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_products';

    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        price decimal(10,2) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Thêm file upgrade.php để sử dụng dbDelta()
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

/**
 * Hủy kích hoạt plugin
 */
register_deactivation_hook(__FILE__, 'cpm_deactivate');
function cpm_deactivate()
{
    delete_option('cpm_delete_data_on_uninstall');
}

/**
 * Thêm vào menu admin
 */
function cpm_add_admin_menu()
{
    // Tạo menu trong trang dashboard
    add_menu_page('Quản lý sản phẩm', 'Sản phẩm Custom', 'manage_options', 'cpm-product-list', 'cpm_display_products');
    add_menu_page('Cài đặt Plugin', 'Cài đặt CPM', 'manage_options', 'cpm-settings', 'cpm_settings_page');
}
add_action('admin_menu', 'cpm_add_admin_menu');

/**
 * Hiển thị danh sách sản phẩm
 */
function cpm_display_products()
{
    require_once CPM_PLUGIN_DIR . 'views/display-products.php';
}

/**
 * Hiển thị giao diện trang cài đặt
 */
function cpm_settings_page()
{
    require_once CPM_PLUGIN_DIR . 'views/display-settings.php';
}

/**
 * Đăng ký cài đặt
 */
function cpm_register_settings()
{
    register_setting(
        'cpm_settings_group', // Thuộc nhóm cài đặt cpm_settings_group
        'cpm_delete_data_on_uninstall' // Đăng ký một tùy chọn có tên cpm_delete_data_on_uninstall
    );

    add_settings_section(
        'cpm_main_section', // Tạo một phần (section) cài đặt có ID là cpm_main_section
        'Tùy chọn xóa dữ liệu', // Tiêu đề hiển thị
        null,
        'cpm-settings' // Trang cài đặt thuộc về cpm-settings
    );

    // add_settings_field() được dùng để thêm một trường cài đặt vào khu vực cpm_main_section
    add_settings_field(
        'cpm_delete_data_on_uninstall',
        'Xóa dữ liệu khi gỡ cài đặt?',
        'cpm_delete_data_field', // Hàm callback để hiển thị input
        'cpm-settings',
        'cpm_main_section' // Section chứa trường này.
    );
}
add_action('admin_init', 'cpm_register_settings');

/**
 * Giao diện checkbox
 */
function cpm_delete_data_field()
{
    require_once CPM_PLUGIN_DIR . 'views/checkbox-delete-data.php';
}

/**
 * Xử lý các tác vụ
 */
function cpm_handle_actions()
{
    $admin_cpm_settings_url = admin_url('admin.php?page=cpm-settings');

    if (isset($_POST['submit'])) {

        // Kiểm tra bảo mật với nonce
        if (!isset($_POST['cpm_settings_nonce']) || !wp_verify_nonce($_POST['cpm_settings_nonce'], 'cpm_settings_nonce_action')) {
            wp_die('Lỗi bảo mật!');
        }

        wp_redirect(add_query_arg('updated-settings', 'true', $admin_cpm_settings_url));
        exit;
    }
}
add_action('admin_init', 'cpm_handle_actions');
