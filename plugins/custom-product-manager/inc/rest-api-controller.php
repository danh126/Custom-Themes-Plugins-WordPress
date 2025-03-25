<?php

/**
 * Ngăn chặn truy cập trực tiếp
 */
if (!defined('ABSPATH')) {
    exit;
}

// Load REST API khi WordPress khởi động
add_action('rest_api_init', function () {
    $controller = new CPM_REST_Controller();
    $controller->register_routes();
});

class CPM_REST_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        global $wpdb;
        if (!isset($wpdb)) return;

        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . 'custom_products';

        $this->namespace = 'cpm/v1';
        $this->resource_name = 'products';
    }

    /**
     * Đăng ký routes API
     */
    public function register_routes()
    {
        register_rest_route($this->namespace, '/' . $this->resource_name, [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_products'],
            'permission_callback' => '__return_true',
        ]);

        // (?P<id>\d+) -> regex lấy giá trị id trong URL API (bắt buộc id ở dạng số)
        // Có thể bắt theo slug: (?P<slug>[a-zA-Z0-9-]+)
        register_rest_route($this->namespace, '/' . $this->resource_name . '/(?P<id>\d+)', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_product'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, '/' . $this->resource_name, [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'add_product'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route($this->namespace, '/' . $this->resource_name . '/(?P<id>\d+)', [
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => [$this, 'update_product'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route($this->namespace, '/' . $this->resource_name . '/(?P<id>\d+)', [
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => [$this, 'delete_product'],
            'permission_callback' => '__return_true'
        ]);
    }

    /**
     * Lấy tất cả danh sách sản phẩm (API)
     */
    public function get_products(WP_REST_Request $request)
    {
        $products = $this->wpdb->get_results("SELECT * FROM {$this->table}");

        if (empty($products)) {
            return new WP_Error('no_products', __('Không có sản phẩm nào'), ['status' => 404]);
        }

        return rest_ensure_response($products);
    }

    /**
     * Lấy sản phẩm theo id (API)
     */
    public function get_product(WP_REST_Request $request)
    {
        $id = intval($request['id']);
        $product = $this->get_product_data($id);

        // Kiểm tra sản phẩm tồn tại
        $validate = $this->validate_product($id, $product);
        if (is_wp_error($validate)) {
            return $validate;
        }

        return rest_ensure_response($product);
    }

    /**
     * Thêm sản phẩm mới (API)
     */
    public function add_product(WP_REST_Request $request)
    {
        // Lấy data từ request API
        $name = $request->has_param('name') ? sanitize_text_field($request['name']) : null;
        $price = $request->has_param('price') ? floatval($request['price']) : null;

        // Validated
        $validated = $this->validated_data($name, $price, $request, 'created');

        if (is_wp_error($validated)) {
            return $validated;
        }

        // Thêm sản phẩm mới vào db
        $inserted =  $this->wpdb->insert($this->table, [
            'name' => $name,
            'price' => $price
        ]);

        // Thông báo lỗi nếu thêm vào db thất bại
        if ($inserted === false) {
            return new WP_Error('db_insert_error', __('Lỗi hệ thống!'), ['status' => 500]);
        }

        // Lấy id sản phẩm vừa thêm
        $product_id = $this->wpdb->insert_id;

        // Truy vấn lại sản phẩm vừa thêm
        $product = $this->get_product_data($product_id);

        return rest_ensure_response([
            'message' => __('Thêm sản phẩm mới thành công!'),
            'product' => $product
        ]);
    }

    /**
     * Cập nhật sản phẩm (API)
     */
    public function update_product(WP_REST_Request $request)
    {
        // Kiểm tra sản phẩm cập nhật
        $id = intval($request['id']);
        $product = $this->get_product_data($id);

        $validate = $this->validate_product($id, $product);
        if (is_wp_error($validate)) {
            return $validate;
        }

        // Lấy data từ request API
        $name = $request->has_param('name') ? sanitize_text_field($request['name']) : null;
        $price = $request->has_param('price') ? floatval($request['price']) : null;

        // Validated
        $validated = $this->validated_data($name, $price, $request, 'updated');

        if (is_wp_error($validated)) {
            return $validated;
        }

        // Chuẩn bị data cập nhật
        $update_data = [];
        if ($name !== null) $update_data['name'] = $name;
        if ($price !== null) $update_data['price'] = $price;

        // Cập nhật sản phẩm trong db
        $updated = $this->wpdb->update(
            $this->table,
            $update_data,
            ['id' => $id]
        );

        // Thông báo lỗi cập nhật vào db thất bại
        if ($updated === false) {
            return new WP_Error('db_update_error', __('Lỗi hệ thống!'), ['status' => 500]);
        }

        // Lấy sản phẩm vừa mới cập nhật
        $product = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id));

        return rest_ensure_response([
            'message' => __("Cập nhật sản phẩm có id là $id thành công!"),
            'product' => $product
        ]);
    }

    /**
     * Xoá sản phẩm (API)
     */
    public function delete_product(WP_REST_Request $request)
    {
        // Kiểm tra sản phẩm xóa
        $id = intval($request['id']);
        $product = $this->get_product_data($id);

        $validate = $this->validate_product($id, $product);
        if (is_wp_error($validate)) {
            return $validate;
        }

        // Xóa sản phẩm trong db
        $deleted = $this->wpdb->delete(
            $this->table,
            ['id' => $id]
        );

        // Thông báo nếu lỗi từ hệ thống
        if ($deleted === false) {
            return new WP_Error('db_delete_error', __('Lỗi hệ thống!'), ['status' => 500]);
        }

        return rest_ensure_response(['message' => __("Xóa sản phẩm có id là $id thành công!")]);
    }

    /**
     * Lấy sản phẩm theo id
     */
    public function get_product_data($id)
    {
        $id = (int) $id;
        $product = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id));

        return $product;
    }

    /**
     * Validated data
     */
    public function validated_data($name, $price, $request, $action)
    {
        // Kiểm tra nếu không có dữ liệu nào được gửi lên

        // Thêm sản phẩm mới
        if ($action === 'created') {
            if ($name === null || $price === null) {
                return new WP_Error('no_create_data', __('Dữ liệu thêm sản phẩm mới không hợp lệ'), ['status' => 400]);
            }
        }

        // Cập nhật sản phẩm
        if ($action === 'updated') {
            if ($name === null && $price === null) {
                return new WP_Error('no_update_data', __('Không có dữ liệu cập nhật nào tồn tại'), ['status' => 400]);
            }
        }

        // Kiểm tra dữ liệu hợp lệ
        if ($name !== null && empty($name)) {
            return new WP_Error('invalid_name', __('Tên sản phẩm không được bỏ trống'), ['status' => 400]);
        }

        if ($price !== null && (!is_numeric($request['price']) || $price <= 0)) {
            return new WP_Error('invalid_price', __('Giá sản phẩm phải là số hợp lệ và lớn hơn 0'), ['status' => 400]);
        }
    }

    /**
     * Validate product
     */
    public function validate_product($id, $product)
    {
        if (empty($product)) {
            return new WP_Error('no_product', __("Không tồn tại sản phẩm có id là $id"), ['status' => 404]);
        }
    }
}
