<?php
// Ngăn chặn truy cập trực tiếp
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Xóa các tùy chọn đã lưu
delete_option('simple_plugin_activated');
