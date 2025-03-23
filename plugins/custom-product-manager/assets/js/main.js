/**
 * Xóa query sau khi redirect
 */
if (window.history.replaceState) {
  let url = new URL(window.location.href);
  url.searchParams.delete("delete_product");
  url.searchParams.delete("add_product");
  url.searchParams.delete("edit_product");
  window.history.replaceState(null, null, url.toString());
}

/**
 * Xử lý UI thao tác với form
 */
document.addEventListener("DOMContentLoaded", function () {
  // Button show form create
  const showFormCreate = document.getElementById("showFormCreate");

  // Form
  const createForm = document.getElementById("createForm");
  const updateForm = document.getElementById("updateForm");

  // Close form
  const closeFormCreate = document.getElementById("close-create");
  const closeFormEdit = document.getElementById("close-update");

  // Inputs
  const inputCreate = document.querySelectorAll(".input-create");
  const inputUpdate = document.querySelectorAll(".input-update");

  // Submit form
  const submitBtnCreate = document.getElementById("submitBtnCreate");
  const submitBtnUpdate = document.getElementById("submitBtnUpdate");

  /**
   * classList.toggle('class_name') -> thêm vào class nếu chưa có, nếu có class thì xóa class đi
   */
  showFormCreate.addEventListener("click", function () {
    createForm.classList.toggle("hidden"); // Hiển thị form thêm sản phẩm mới
    this.classList.toggle("hidden"); // Ẩn button thêm sản phẩm
  });

  /**
   * Hàm kiểm tra giá trị các trường input
   * every() → Cần nhập đủ tất cả input mới bật nút
   * some() → Chỉ cần nhập một input là bật nút
   */
  function checkInputs(inputs) {
    if (!inputs || inputs.length === 0) return;

    let allField = [...inputs].every((input) => input.value.trim() !== "");

    // Button thêm sản phẩm mới
    if (typeof submitBtnCreate !== "undefined" && submitBtnCreate) {
      submitBtnCreate.disabled = !allField;
    }

    // Button cập nhật sản phẩm
    if (typeof submitBtnUpdate !== "undefined" && submitBtnUpdate) {
      submitBtnUpdate.disabled = !allField;
    }
  }

  /**
   * Duyệt qua từng trường input và gọi hàm kiểm tra
   */
  inputCreate.forEach((input) => {
    input.addEventListener("input", () => checkInputs(inputCreate));
  });

  inputUpdate.forEach((input) => {
    input.addEventListener("input", () => checkInputs(inputUpdate));
  });

  /**
   * Xử lý sự kiện hủy form thêm sản phẩm
   */
  closeFormCreate.addEventListener("click", function () {
    createForm.classList.toggle("hidden");
    showFormCreate.classList.toggle("hidden");

    // Clear input
    inputCreate.forEach((input) => {
      input.value = "";
    });
  });

  /**
   * Xử lý sự kiện cập nhật sản phẩm
   */
  document.querySelectorAll(".update-btn").forEach((button) => {
    button.addEventListener("click", function () {
      // Lấy data
      let row = this.closest("tr"); // lấy hàng chứa button
      let id = row.querySelector(".product-id").textContent;
      let name = row.querySelector(".product-name").textContent;
      let price = row.querySelector(".product-price").textContent;

      // URL cập nhật sản phẩm
      let url = `admin.php?page=cpm-product-list&action=update&id=${id}`;

      // Đổ dữ liệu vào form
      document.getElementById("name").value = name;
      document.getElementById("price").value = price;
      document.getElementById("product-id").value = id;

      // Cập nhật action form
      updateForm.action = url;

      // Hiển thị form cập nhật
      updateForm.classList.toggle("hidden");
      showFormCreate.classList.toggle("hidden");

      checkInputs(inputUpdate); // Check input null
    });
  });

  /**
   * Xử lý sự kiện hủy form cập nhật sản phẩm
   */
  closeFormEdit.addEventListener("click", function () {
    updateForm.classList.toggle("hidden");
    showFormCreate.classList.toggle("hidden");

    // Clear input
    inputUpdate.forEach((input) => {
      input.value = "";
    });
  });
});
