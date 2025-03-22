<?php
add_action('my_plugin_custom_action', function () {
    error_log('Custom Hook đã được gọi!'); // Ghi log vào file error_log
});
