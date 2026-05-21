<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title mb-0">
        <i class="bi bi-box-seam"></i> Danh sách sản phẩm
    </div>
    <a href="<?php echo SITE_URL; ?>Product/add" class="btn btn-success px-4">
        <i class="bi bi-plus-lg me-1"></i> Thêm sản phẩm
    </a>
</div>

<div class="row g-4">
    <?php foreach ($products as $product): ?>
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="card product-card h-100">
            <?php if (!empty($product->image)): ?>
                <img src="<?php echo SITE_URL; ?>uploads/products/<?php echo htmlspecialchars($product->image); ?>"
                     class="card-img-top" alt="<?php echo htmlspecialchars($product->name); ?>">
            <?php else: ?>
                <div class="card-img-placeholder"><i class="bi bi-image"></i></div>
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
                <h6 class="card-title"><?php echo htmlspecialchars($product->name); ?></h6>
                <p class="text-muted small mb-2" style="flex:1">
                    <?php echo htmlspecialchars(substr($product->description ?? '', 0, 60)); ?>
                </p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="price-tag fs-6">
                        <?php echo number_format($product->price, 0, '.', '.'); ?>đ
                    </span>
                    <?php if (!empty($product->category_name)): ?>
                        <span class="badge-cat"><?php echo htmlspecialchars($product->category_name); ?></span>
                    <?php endif; ?>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo SITE_URL; ?>Product/show/<?php echo $product->id; ?>"
                       class="btn btn-outline-primary btn-sm flex-fill" title="Xem"><i class="bi bi-eye"></i></a>
                    <a href="<?php echo SITE_URL; ?>Product/edit/<?php echo $product->id; ?>"
                       class="btn btn-warning btn-sm flex-fill" title="Sửa"><i class="bi bi-pencil"></i></a>
                    <a href="<?php echo SITE_URL; ?>Product/delete/<?php echo $product->id; ?>"
                       class="btn btn-danger btn-sm flex-fill" title="Xóa"
                       onclick="return confirm('Xóa sản phẩm này?')"><i class="bi bi-trash"></i></a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if (empty($products)): ?>
    <div class="col-12 text-center py-5">
        <i class="bi bi-inbox text-muted" style="font-size:3rem"></i>
        <p class="text-muted mt-3">Chưa có sản phẩm nào.</p>
        <a href="<?php echo SITE_URL; ?>Product/add" class="btn btn-primary">Thêm sản phẩm đầu tiên</a>
    </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
