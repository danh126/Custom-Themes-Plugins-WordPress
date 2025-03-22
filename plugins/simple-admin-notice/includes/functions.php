<?php

// Hiển thị thông báo trong admin dashboard
function simple_admin_notice()
{
    if (get_option('simple_plugin_activated') === 'yes') {

        // __DIR__ sẽ lấy thư mục hiện tại của file functions.php, tức là includes/.
        // plugin_dir_path(__DIR__) sẽ đưa lên một cấp, tức về thư mục plugin.

        include plugin_dir_path(__DIR__) . 'views/notifications/admin-notice.php';
    }
}
add_action('admin_notices', 'simple_admin_notice');

// Hàm xử lý shortcode
function custom_title_post_shortcode($atts)
{
    $atts = shortcode_atts([
        'message' => 'Chào mừng bạn đến với WordPress!'
    ], $atts);

    return "<div class='san-shortcode'>{$atts['message']}</div>";
}
add_shortcode('title_post_shortcode', 'custom_title_post_shortcode');
