<?php

/**
 * Ngăn chặn truy cập trực tiếp
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Đăng ký route API
 */
add_action('rest_api_init', function () {
    register_rest_route('cpm/v1', '/products', [
        'methods' => 'GET',
        'callback' => 'get_custom_data',
        'permission_callback' => '__return_true'
    ]);

    register_rest_route('cpm/v1', '/products', [
        'methods' => 'POST',
        'callback' => 'add_custom_data',
        'permission_callback' => function () {
            return current_user_can('edit_posts'); // chỉ có user có quyền chỉnh sửa mới được thêm
        }
    ]);
});

/**
 * Lấy danh sách sản phảm thông qua API
 */
function get_custom_data(WP_REST_Request $request)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_products';

    $results = $wpdb->get_results("SELECT * FROM $table_name");
    return rest_ensure_response($results);
}

/**
 * Thêm sản phẩm mới thông qua API
 */
function add_custom_data(WP_REST_Request $request)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_products';

    // Lấy data từ request api
    $name = sanitize_text_field($request->get_param('name'));
    $price = floatval($request->get_param('price'));

    // Validated
    if (empty($name)) {
        return new WP_Error('missing_name', 'Tên sản phẩm không được để trống', ['status' => 400]);
    }

    if (empty($price)) {
        return new WP_Error('missing_price', 'Giá sản phẩm không được để trống', ['status' => 400]);
    }

    if (!is_float($price)) {
        return new WP_Error('not_float', 'Giá sản phẩm không hợp lệ', ['status' => 400]);
    }

    // Thêm vào database
    $wpdb->insert($table_name, [
        'name' => $name,
        'price' => $price
    ]);

    return rest_ensure_response(['message' => 'Thêm sản phẩm mới thành công!']);
}
