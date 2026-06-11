<?php include 'app/views/shares/header.php'; ?>

<!-- Alert phản hồi -->
<div class="alert alert-dismissible fade show mb-4" id="alert-message" style="display: none;" role="alert">
    <span id="alert-text"></span>
    <button type="button" class="btn-close btn-close-white" onclick="document.getElementById('alert-message').style.display='none'"></button>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title mb-0">
        <i class="bi bi-box-seam"></i> Quản lý sản phẩm (SPA - 1 Tab)
    </div>
    <?php if (SessionHelper::isAdmin()): ?>
    <button class="btn btn-success px-4" id="btn-add-product">
        <i class="bi bi-plus-lg me-1"></i> Thêm sản phẩm
    </button>
    <?php endif; ?>
</div>

<div class="row g-4" id="product-grid">
    <!-- Nạp sản phẩm động bằng Javascript Fetch API -->
    <div class="col-12 text-center py-5" id="loading-spinner">
        <div class="spinner-border text-warning" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted mt-2">Đang tải danh sách sản phẩm...</p>
    </div>
</div>

<!-- Modal Form (Add / Edit) -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Thêm sản phẩm</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="productForm">
                <input type="hidden" id="productId" name="id">
                <div class="modal-body">
                    <!-- Alert Lỗi Validation -->
                    <div class="alert alert-danger mb-3" id="form-error" style="display: none;">
                        <ul class="mb-0 ps-3" id="form-error-list"></ul>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên sản phẩm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="price">Giá sản phẩm <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" id="price" name="price" class="form-control" placeholder="0" min="0" required>
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="category_id">Danh mục <span class="text-danger">*</span></label>
                            <select id="category_id" name="category_id" class="form-select" required>
                                <option value="">-- Chọn danh mục --</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="description">Mô tả sản phẩm <span class="text-danger">*</span></label>
                            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Mô tả sản phẩm" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="btn-save">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const isAdmin = <?php echo SessionHelper::isAdmin() ? 'true' : 'false'; ?>;
    const siteUrl = "<?php echo SITE_URL; ?>";
    const apiBaseUrl = '/webbanhang/api';
    
    // Khởi tạo Bootstrap Modal
    const productModalElement = document.getElementById('productModal');
    let productModal = null;
    if (productModalElement) {
        productModal = new bootstrap.Modal(productModalElement);
    }

    // Tải danh mục vào form select
    function loadCategories() {
        const categorySelect = document.getElementById('category_id');
        if (!categorySelect) return;
        
        fetch(`${apiBaseUrl}/category`)
        .then(response => response.json())
        .then(categories => {
            // Xóa các option cũ trừ option đầu tiên
            categorySelect.innerHTML = '<option value="">-- Chọn danh mục --</option>';
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });
        })
        .catch(error => console.error('Lỗi khi tải danh mục:', error));
    }

    // Tải danh sách sản phẩm bằng Fetch API
    function loadProducts() {
        const productGrid = document.getElementById('product-grid');
        
        fetch(`${apiBaseUrl}/product`)
        .then(response => response.json())
        .then(data => {
            productGrid.innerHTML = ''; // Clear loading
            
            if (data.length === 0) {
                productGrid.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size:3rem"></i>
                        <p class="text-muted mt-3">Chưa có sản phẩm nào.</p>
                        ${isAdmin ? `<button class="btn btn-primary" id="btn-add-first">Thêm sản phẩm đầu tiên</button>` : ''}
                    </div>
                `;
                
                const btnAddFirst = document.getElementById('btn-add-first');
                if (btnAddFirst) {
                    btnAddFirst.addEventListener('click', openAddModal);
                }
                return;
            }
            
            data.forEach(product => {
                const col = document.createElement('div');
                col.className = 'col-sm-6 col-lg-4 col-xl-3';
                
                const truncatedDesc = product.description ? (product.description.substring(0, 60) + (product.description.length > 60 ? '...' : '')) : '';
                const formattedPrice = new Intl.NumberFormat('vi-VN').format(product.price) + 'đ';
                
                let adminButtons = '';
                if (isAdmin) {
                    adminButtons = `
                        <button class="btn btn-warning btn-sm btn-edit" title="Sửa" data-id="${product.id}"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-danger btn-sm btn-delete" title="Xóa" data-id="${product.id}"><i class="bi bi-trash"></i></button>
                    `;
                }
                
                const categoryBadge = product.category_name ? `<span class="badge-cat">${product.category_name}</span>` : '';
                
                col.innerHTML = `
                    <div class="card product-card h-100">
                        <div class="card-img-placeholder"><i class="bi bi-image"></i></div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">${escapeHtml(product.name)}</h6>
                            <p class="text-muted small mb-2" style="flex:1">
                                ${escapeHtml(truncatedDesc)}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="price-tag fs-6">${formattedPrice}</span>
                                ${categoryBadge}
                            </div>
                            <div class="d-flex gap-2">
                                <a href="${siteUrl}Product/show/${product.id}" class="btn btn-outline-primary btn-sm flex-fill" title="Xem Chi Tiết"><i class="bi bi-eye"></i> Chi tiết</a>
                                ${adminButtons}
                            </div>
                            <a href="/webbanhang/Product/addToCart/${product.id}" class="btn btn-primary btn-sm w-100 mt-2"><i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ hàng</a>
                        </div>
                    </div>
                `;
                productGrid.appendChild(col);
            });
            
            // Gắn sự kiện cho các nút Sửa & Xóa động
            if (isAdmin) {
                document.querySelectorAll('.btn-edit').forEach(btn => {
                    btn.addEventListener('click', function() {
                        openEditModal(this.dataset.id);
                    });
                });
                
                document.querySelectorAll('.btn-delete').forEach(btn => {
                    btn.addEventListener('click', function() {
                        deleteProduct(this.dataset.id);
                    });
                });
            }
        })
        .catch(error => {
            console.error('Lỗi tải sản phẩm:', error);
            productGrid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:3rem"></i>
                    <p class="text-danger mt-3">Lỗi tải dữ liệu sản phẩm.</p>
                </div>
            `;
        });
    }

    // Khởi chạy
    loadCategories();
    loadProducts();

    // Hiển thị thông báo phản hồi
    function showAlert(type, message) {
        const alertBox = document.getElementById('alert-message');
        const alertText = document.getElementById('alert-text');
        alertBox.className = `alert alert-${type} alert-dismissible fade show mb-4`;
        alertText.innerHTML = message;
        alertBox.style.display = 'block';
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 5000);
    }

    // Hàm mở modal Thêm mới
    function openAddModal() {
        if (!productModal) return;
        document.getElementById('modalTitle').textContent = 'Thêm sản phẩm mới';
        document.getElementById('productForm').reset();
        document.getElementById('productId').value = '';
        document.getElementById('form-error').style.display = 'none';
        productModal.show();
    }

    // Hàm mở modal Chỉnh sửa
    function openEditModal(id) {
        if (!productModal) return;
        document.getElementById('modalTitle').textContent = `Chỉnh sửa sản phẩm #${id}`;
        document.getElementById('form-error').style.display = 'none';
        
        fetch(`${apiBaseUrl}/product/${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Không lấy được thông tin chi tiết sản phẩm');
            return response.json();
        })
        .then(product => {
            document.getElementById('productId').value = product.id;
            document.getElementById('name').value = product.name;
            document.getElementById('price').value = product.price;
            document.getElementById('category_id').value = product.category_id;
            document.getElementById('description').value = product.description || '';
            productModal.show();
        })
        .catch(error => {
            showAlert('danger', `<i class="bi bi-x-circle-fill me-1"></i> Lỗi: ${error.message}`);
        });
    }

    // Sự kiện Click nút Thêm sản phẩm trên thanh công cụ
    const btnAddProduct = document.getElementById('btn-add-product');
    if (btnAddProduct) {
        btnAddProduct.addEventListener('click', openAddModal);
    }

    // Xử lý gửi Form (Thêm mới / Cập nhật)
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formError = document.getElementById('form-error');
            const formErrorList = document.getElementById('form-error-list');
            formError.style.display = 'none';
            formErrorList.innerHTML = '';
            
            const id = document.getElementById('productId').value;
            const productData = {
                name: document.getElementById('name').value,
                price: parseFloat(document.getElementById('price').value),
                category_id: parseInt(document.getElementById('category_id').value),
                description: document.getElementById('description').value
            };
            
            const isEdit = id !== '';
            const url = isEdit ? `${apiBaseUrl}/product/${id}` : `${apiBaseUrl}/product`;
            const method = isEdit ? 'PUT' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(productData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Product created successfully' || data.message === 'Product updated successfully') {
                    productModal.hide();
                    showAlert('success', `<i class="bi bi-check-circle-fill me-1"></i> ${isEdit ? 'Cập nhật' : 'Thêm'} sản phẩm thành công!`);
                    loadProducts();
                } else if (data.errors) {
                    formError.style.display = 'block';
                    for (const field in data.errors) {
                        const li = document.createElement('li');
                        li.textContent = data.errors[field];
                        formErrorList.appendChild(li);
                    }
                } else {
                    formError.style.display = 'block';
                    const li = document.createElement('li');
                    li.textContent = data.message || 'Thực hiện thất bại';
                    formErrorList.appendChild(li);
                }
            })
            .catch(error => {
                console.error(error);
                formError.style.display = 'block';
                const li = document.createElement('li');
                li.textContent = 'Lỗi hệ thống khi gửi dữ liệu.';
                formErrorList.appendChild(li);
            });
        });
    }

    // Xử lý Xóa sản phẩm
    function deleteProduct(id) {
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
            fetch(`${apiBaseUrl}/product/${id}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Product deleted successfully') {
                    showAlert('success', '<i class="bi bi-check-circle-fill me-1"></i> Xóa sản phẩm thành công!');
                    loadProducts();
                } else {
                    showAlert('danger', `<i class="bi bi-x-circle-fill me-1"></i> Xóa sản phẩm thất bại: ${data.message}`);
                }
            })
            .catch(error => {
                console.error(error);
                showAlert('danger', '<i class="bi bi-x-circle-fill me-1"></i> Lỗi hệ thống khi thực hiện xóa.');
            });
        }
    }

    // Helper escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
});
</script>
