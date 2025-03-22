<div class="wrap">
    <h2>Cài đặt Plugin</h2>
    <form action="options.php" method="post">
        <?php
        if (isset($_GET['updated-settings']) && $_GET['updated-settings'] == true) :
        ?>
            <div class="updated notice is-dismissible">
                <p><?= __('Đã cập nhật cài đặt thành công!') ?></p>
            </div>
        <?php
        endif;
        /**
         * options.php -> trang xử lý các tùy chọn (settings) của WordPress
         * settings_fields('cpm_settings_group') -> Tạo các trường bảo mật (nonce, hidden fields) cho nhóm cài đặt cpm_settings_group
         * do_settings_sections('cpm-settings') -> Hiển thị các mục cài đặt của trang có slug là cpm-settings
         * submit_button() -> Tạo nút Lưu thay đổi mặc định của WordPress
         */

        wp_nonce_field('cpm_settings_nonce_action', 'cpm_settings_nonce');

        settings_fields('cpm_settings_group');
        do_settings_sections('cpm-settings');

        submit_button();
        ?>
    </form>
</div>

<script>
    /**
     * Xóa query sau khi updated
     */
    if (window.history.replaceState) {
        let url = new URL(window.location.href);
        url.searchParams.delete("updated-settings"); // Xóa chỉ `updated=true`
        window.history.replaceState(null, null, url.toString());
    }
</script>