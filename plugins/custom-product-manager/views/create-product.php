<?php
if (isset($_GET['add_product']) && $_GET['add_product'] == true) :
?>
    <!-- Thông báo -->
    <div class="notice notice-success is-dismissible">
        <p><?= __('Thêm sản phẩm mới thành công!') ?></p>
    </div>
<?php endif ?>

<!-- Form thêm sản phẩm -->
<form action="" method="post" id="createForm" class="hidden">
    <?php
    /**
     * Bảo mật chống CSRF
     */
    wp_nonce_field('cpm_add_product_nonce_action', 'cpm_add_product_nonce');
    ?>

    <h2 class="text-center mt-2"><?= __('Thêm sản phẩm mới') ?></h2>
    <div class="form-group mt-2 mb-2">
        <label for=""><?= __('Tên sản phẩm') ?></label>
        <input type="text" name="name" class="form-control mt-2 input-create" required>
    </div>
    <div class="form-group mt-2 mb-2">
        <label for=""><?= __('Giá bán') ?></label>
        <input type="number" name="price" step="0.1" class="form-control mt-2 input-create" required>
    </div>
    <div class="form-group mt-2 mb-2 text-center">
        <button class="btn btn-primary" name="submit_add_product" id="submitBtnCreate" disabled><?= __('Thêm sản phẩm') ?></button>
        <button class="btn btn-danger" id="close-create"><?= __('Thoát') ?></button>
    </div>
</form>