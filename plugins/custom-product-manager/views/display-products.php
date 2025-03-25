<?php

/**
 * Get list products
 */
global $wpdb;
$table_name = $wpdb->prefix . 'custom_products';
$products = $wpdb->get_results("SELECT * FROM $table_name");

/**
 * URL Redirect
 */
$url_delete_product = admin_url('admin.php?page=cpm-product-list&action=delete');

if (isset($_GET['delete_product']) && $_GET['delete_product'] == true) :
?>

    <!-- Thông báo -->
    <div class="notice notice-success is-dismissible">
        <p><?= __('Xóa sản phẩm thành công!') ?></p>
    </div>
<?php endif ?>

<div>
    <!-- Create product -->
    <?php require_once 'create-product.php' ?>

    <!-- Update product -->
    <?php require_once 'update-product.php' ?>
</div>

<div class="wrap">
    <!-- List products -->
    <h2><?= __('Danh sách sản phẩm') ?></h2>

    <button class="btn btn-success" id="showFormCreate">Thêm sản phẩm mới</button>

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
                    <td class="product-id"><?= __($v->id) ?></td>
                    <td class="product-name"><?= __($v->name) ?></td>
                    <td class="product-price"><?= __($v->price) ?></td>
                    <td><?= __($v->created_at) ?></td>
                    <td>
                        <button class="btn btn-primary update-btn"><?= __('Cập nhật') ?></button>
                        <a href="<?= esc_url($url_delete_product . "&id=$v->id") ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm?')"><?= __('Xóa') ?></a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>