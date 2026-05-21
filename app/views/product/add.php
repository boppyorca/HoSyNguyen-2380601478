<?php include 'app/views/shares/header.php'; ?>

<div class="page-title">
    <i class="bi bi-plus-circle"></i> Thêm sản phẩm mới
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">

                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger border-0 rounded-3">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo SITE_URL; ?>Product/save" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Nhập tên sản phẩm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Giá <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="price" class="form-control" placeholder="0" step="1000" min="0" required>
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>"><?php echo htmlspecialchars($category->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Mô tả chi tiết sản phẩm..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Hình ảnh</label>
                            <div class="upload-zone" onclick="document.getElementById('imageInput').click()">
                                <i class="bi bi-cloud-upload fs-2 text-primary mb-2 d-block"></i>
                                <p class="mb-1 fw-500">Nhấn để chọn ảnh</p>
                                <small class="text-muted">JPG, PNG, GIF — tối đa 10MB</small>
                                <input type="file" id="imageInput" name="image" accept="image/*" class="d-none">
                                <img id="imgPreview" class="img-preview mx-auto d-block">
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-check-lg me-1"></i> Thêm sản phẩm
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
