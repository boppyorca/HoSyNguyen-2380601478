<?php include 'app/views/shares/header.php'; ?>

<div class="page-title">
    <i class="bi bi-folder2-open"></i> Sửa danh mục
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
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

                <form method="POST" action="<?php echo SITE_URL; ?>Category/update">
                    <input type="hidden" name="id" value="<?php echo $category->id; ?>">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   value="<?php echo htmlspecialchars($category->name); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($category->description ?? ''); ?></textarea>
                        </div>
                        <div class="col-12 d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-check-lg me-1"></i> Cập nhật
                            </button>
                            <a href="<?php echo SITE_URL; ?>Category" class="btn btn-outline-secondary px-4">Huỷ</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
