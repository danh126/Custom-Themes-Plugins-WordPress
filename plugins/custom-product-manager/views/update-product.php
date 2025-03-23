<?php
if (isset($_GET['edit_product']) && $_GET['edit_product'] == true) :
?>
    <!-- Thông báo -->
    <div class="notice notice-success is-dismissible">
        <p><?= __('Cập nhật sản phẩm thành công!') ?></p>
    </div>
<?php endif ?>

<!-- Form thêm sản phẩm -->
<form method="post" id="updateForm" class="hidden">
    <?php
    /**
     * Bảo mật chống CSRF
     */
    wp_nonce_field('cpm_edit_product_nonce_action', 'cpm_edit_product_nonce');
    ?>

    <h2 class="text-center mt-2"><?= __('Cập nhật sản phẩm') ?></h2>
    <input type="text" name="id" id="product-id" hidden>
    <div class="form-group mt-2 mb-2">
        <label for=""><?= __('Tên sản phẩm') ?></label>
        <input type="text" name="name" id="name" class="form-control mt-2 input-update" required>
    </div>
    <div class="form-group mt-2 mb-2">
        <label for=""><?= __('Giá bán') ?></label>
        <input type="number" name="price" id="price" step="0.1" class="form-control mt-2 input-update" required>
    </div>
    <div class="form-group mt-2 mb-2 text-center">
        <button class="btn btn-primary" name="submit_edit_product" id="submitBtnUpdate" disabled><?= __('Cập nhật') ?></button>
        <button class="btn btn-danger" id="close-update"><?= __('Thoát') ?></button>
    </div>
</form>