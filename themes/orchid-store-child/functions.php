<?php
//================ Thiết lập ban đầu ====================

// Kế thừa CSS từ theme gốc và thêm assets từ child theme
function orchid_store_child_enqueue_assets()
{
    // Nạp CSS từ theme gốc
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // Nạp CSS từ child theme, tránh cache bằng cách dùng time()
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', ['parent-style'], time());

    // Nạp JS từ child theme, đảm bảo jQuery load trước, tránh cache, nạp ở footer
    wp_enqueue_script(
        'child-js',
        get_stylesheet_directory_uri() . '/assets/js/custom.js',
        array('jquery'),
        time(),
        true
    );
}
add_action('wp_enqueue_scripts', 'orchid_store_child_enqueue_assets');

//================ Demo test ====================

// Thêm thẻ meta vào head
function custom_add_meta_tag()
{
    echo '<meta name="author" content="Nguyen Thanh Danh">';
}
add_action('wp_head', 'custom_add_meta_tag');

// Thêm script vào footer
function custom_add_footer_script()
{
    echo "<script>console.log('Hello World!')</script>";
}
add_action('wp_footer', 'custom_add_footer_script');

// Thêm đăng nhập vào menu nếu chưa đăng nhập
function add_login_logout_link($items, $args)
{
    if (!is_user_logged_in()) {
        $items .= '<li><a href="' . wp_login_url() . '">Đăng nhập</a></li>';
    } else {
        $items .= '<li><a href="' . wp_logout_url() . '">Đăng xuất</a></li>';
    }

    return $items;
}
add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2); // 10 -> mức độ ưu tiên, 2 -> tham số nhận vào function

//================ Điều hướng đăng nhập & đăng xuất ====================

// Khi đăng nhập điều hướng theo vai trò
function custom_login_redirect($redirect_to, $request, $user)
{
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('administrator', $user->roles)) {
            return admin_url();
        }
    }

    return home_url();
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3); // 10 -> mức độ ưu tiên, 3 -> tham số nhận vào function

// Khi đăng xuất điều hướng về trang chủ
function custom_logout_redirect($redirect_to, $requested_redirect_to, $user)
{
    $user_roles = $user->roles;
    $user_has_admin_role = in_array('administrator', $user_roles);

    if ($user_has_admin_role) {
        $redirect_to = admin_url();
    } else {
        $redirect_to = home_url();
    }

    return $redirect_to;
}
add_filter('logout_redirect', 'custom_logout_redirect', 10, 3); // 10 -> mức độ ưu tiên, 3 -> tham số nhận vào function


//================ Xử lý hiển thị thông báo đăng nhập ====================

// Lưu thông báo sau khi đăng nhập
function welcome_user_login($user_login, $user)
{
    // Kiểm tra nếu session chưa được mở thì gọi session_start()
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['welcome_message'] = 'Chào mừng ' . $user->display_name . ' đã đăng nhập!';

    session_write_close(); // Đóng session sớm để tránh session blocking
}
add_action('wp_login', 'welcome_user_login', 10, 2); // 10 -> mức độ ưu tiên, 2 -> tham số nhận vào function

// Hiển thị chào mừng đăng nhập
function show_welcome_message()
{
    get_template_part('template-parts/notifications/welcome-message');
}
add_action('wp_footer', 'show_welcome_message');
