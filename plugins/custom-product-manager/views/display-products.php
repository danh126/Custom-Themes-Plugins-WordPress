<?php

/**
 * Get list products
 */
global $wpdb;
$table_name = $wpdb->prefix . 'custom_products';
$products = $wpdb->get_results("SELECT * FROM $table_name");

/**
 * URL Redicret
 */
$url_add_product = admin_url('admin.php?page=cpm-product-list&action=add');
?>

<div class="wrap">
    <h2><?= __('Danh sách sản phẩm') ?></h2>
    <a href="<?= esc_url($url_add_product) ?>" class="btn btn-success mt-2 mb-2"><?= __('Thêm sản phẩm mới') ?></a>

    <!-- List products -->
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?= __('ID') ?></th>
                <th><?= __('Tên sản phẩm') ?></th>
                <th><?= __('Giá bán') ?></th>
                <th><?= __('Ngày tạo') ?></th>
                <th><?= __('Tác vụ') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $v) : ?>
                <tr>
                    <td><?= __($v->id) ?></td>
                    <td><?= __($v->name) ?></td>
                    <td><?= __($v->price) ?></td>
                    <td><?= __($v->created_at->format('d-m-Y')) ?></td>
                    <td>
                        <a href="" class="btn btn-primary"><?= __('Cập nhật') ?></a>
                        <a href="" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm?')"><?= __('Xóa') ?></a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>