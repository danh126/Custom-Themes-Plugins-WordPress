<?php

/**
 * Lấy giá trị của option
 */
$value = get_option('cpm_delete_data_on_uninstall', 'no');
?>

<input type="checkbox" name="cpm_delete_data_on_uninstall" value="yes" <?= checked($value, 'yes', false) ?>>
<?= __('Có, xóa dữ liệu khi gỡ Plugin') ?>