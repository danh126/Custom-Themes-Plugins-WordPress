<?php

/**
 * Ngăn chặn truy cập trực tiếp
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Hàm khởi tạo CPT
 */
function create_project_post_type()
{
    $labels = [
        'name' => __('Dự án'),
        'singular_name' => __('Dự án'),
        'menu_name' => __('Dự án'),
        'name_admin_bar' => __('Dự án'),
        'add_new' => __('Thêm mới'),
        'add_new_item' => __('Thêm dự án mới'),
        'new_item' => __('Dự án mới'),
        'edit_item' => __('Chỉnh sửa dự án'),
        'view_item' => __('Xem dự án'),
        'all_items' => __('Tất cả dự án'),
        'search_item' => __('Tìm kiếm dự án'),
        'not_found' => __('Không tìm thấy dự án nào.'),
        'not_found_in_trash' => __('Không có dự án nào trong thùng rác.'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-media-document',
        'supports' => [
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'custom-fields'
        ],
        'rewrite' => ['slug' => 'du-an'],
        'show_in_rest' => true,
    ];

    register_post_type('project', $args);
}
add_action('init', 'create_project_post_type');

/**
 * Hàm tạo phân loại CPT
 */
function create_project_taxonomy()
{
    $labels = [
        'name' => __('Loại dự án'),
        'singular_name' => __('Loại dự án'),
        'search_items'      => __('Tìm kiếm loại dự án'),
        'all_items'         => __('Tất cả loại dự án'),
        'edit_item'         => __('Chỉnh sửa loại dự án'),
        'update_item'       => __('Cập nhật loại dự án'),
        'add_new_item'      => __('Thêm loại dự án mới'),
        'new_item_name'     => __('Tên loại dự án mới'),
        'menu_name'         => __('Loại dự án'),
    ];

    $args = [
        'labels' => $labels,
        'hierarchical'      => true, // Giống "Chuyên mục", false nếu muốn giống "Thẻ"
        'show_ui'           => true, // Hiển thị giao diện quản lý taxonomy trong Admin Dashboard
        'show_admin_column' => true, //Thêm cột taxonomy này vào danh sách bài viết trong Admin
        'query_var'         => true, // Cho phép sử dụng query variable để lọc nội dung bằng taxonomy
        'rewrite'           => ['slug' => 'loai-du-an'],
    ];

    register_taxonomy('loai-du-an', ['project'], $args);
}
add_action('init', 'create_project_taxonomy');
