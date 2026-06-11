<?php include 'app/views/shares/header.php'; ?>

<div class="page-title">
    <i class="bi bi-pencil-square"></i> Sửa sản phẩm (API Dynamic)
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">

                <!-- Alert hiển thị lỗi động -->
                <div class="alert alert-danger border-0 rounded-3" id="error-alert" style="display: none;">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <ul class="mb-0 ps-3" id="error-list"></ul>
                </div>

                <form id="edit-product-form">
                    <input type="hidden" id="id" name="id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên sản phẩm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Giá <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" id="price" name="price" class="form-control" placeholder="0" step="1000" min="0" required>
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select id="category_id" name="category_id" class="form-select" required>
                                <option value="">-- Chọn danh mục --</option>
                                <!-- Được tải động qua API -->
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mô tả <span class="text-danger">*</span></label>
                            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Mô tả chi tiết sản phẩm..." required></textarea>
                        </div>
                        
                        <div class="col-12 d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-check-lg me-1"></i> Lưu thay đổi
                            </button>
                            <a href="<?php echo SITE_URL; ?>Product/" class="btn btn-outline-secondary px-4">Huỷ</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const productId = <?php echo $id; ?>;
    
    // Tải danh mục trước
    fetch('/webbanhang/api/category')
    .then(response => response.json())
    .then(categories => {
        const categorySelect = document.getElementById('category_id');
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
        
        // Sau đó tải dữ liệu sản phẩm để điền vào form (bao gồm cả danh mục chính xác)
        return fetch(`/webbanhang/api/product/${productId}`);
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Không tìm thấy sản phẩm');
        }
        return response.json();
    })
    .then(product => {
        document.getElementById('id').value = product.id;
        document.getElementById('name').value = product.name;
        document.getElementById('price').value = product.price;
        document.getElementById('category_id').value = product.category_id;
        document.getElementById('description').value = product.description || '';
    })
    .catch(error => {
        console.error('Lỗi khởi tạo form sửa sản phẩm:', error);
        alert('Lỗi: ' + error.message);
        location.href = '/webbanhang/Product';
    });
});

document.getElementById('edit-product-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const errorAlert = document.getElementById('error-alert');
    const errorList = document.getElementById('error-list');
    errorAlert.style.display = 'none';
    errorList.innerHTML = '';
    
    const formData = new FormData(this);
    const jsonData = {};
    formData.forEach((value, key) => {
        jsonData[key] = value;
    });
    
    fetch(`/webbanhang/api/product/${jsonData.id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.message === 'Product updated successfully') {
            location.href = '/webbanhang/Product';
        } else if (data.errors) {
            errorAlert.style.display = 'block';
            for (const field in data.errors) {
                const li = document.createElement('li');
                li.textContent = data.errors[field];
                errorList.appendChild(li);
            }
        } else {
            errorAlert.style.display = 'block';
            const li = document.createElement('li');
            li.textContent = data.message || 'Cập nhật sản phẩm thất bại';
            errorList.appendChild(li);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorAlert.style.display = 'block';
        const li = document.createElement('li');
        li.textContent = 'Lỗi hệ thống khi gửi dữ liệu cập nhật.';
        errorList.appendChild(li);
    });
});
</script>
