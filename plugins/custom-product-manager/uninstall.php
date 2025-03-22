<?php

/**
 * Ngăn chặn truy cập trực tiếp
 */
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Xóa table custom products & các tùy chọn đã lưu
 */
global $wpdb;
$table_name = $wpdb->prefix . 'custom_products';

if (get_option('cpm_delete_data_on_uninstall') === 'yes') {
    $wpdb->query("DROP TABLE IF EXISTS $table_name");

    delete_option('cpm_delete_data_on_uninstall');
}
