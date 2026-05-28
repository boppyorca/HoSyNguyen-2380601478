<?php include 'app/views/shares/header.php'; ?>

<div class="page-title">
    <i class="bi bi-eye"></i> Chi tiết sản phẩm
</div>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card">
            <?php if (!empty($product->image)): ?>
                <img src="<?php echo SITE_URL; ?>uploads/products/<?php echo htmlspecialchars($product->image); ?>"
                     class="card-img-top" style="border-radius:14px; object-fit:cover; max-height:360px;" alt="<?php echo htmlspecialchars($product->name); ?>">
            <?php else: ?>
                <div style="height:280px; background:linear-gradient(135deg,#172a45,#1f3554); border-radius:14px; display:flex; align-items:center; justify-content:center; color:#8892b0; font-size:5rem;">
                    <i class="bi bi-image"></i>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-body p-4">
                <h3 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($product->name); ?></h3>
                <?php if (!empty($product->category_name)): ?>
                    <span class="badge-cat mb-3 d-inline-block"><?php echo htmlspecialchars($product->category_name); ?></span>
                <?php endif; ?>
                <div class="price-tag fs-3 mb-3"><?php echo number_format($product->price, 0, '.', '.'); ?>đ</div>
                <hr>
                <p class="text-muted" style="line-height:1.7"><?php echo nl2br(htmlspecialchars($product->description)); ?></p>
                <div class="d-flex gap-2 mt-4">
                    <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-primary px-4">
                        <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ hàng
                    </a>
                    <?php if (SessionHelper::isAdmin()): ?>
                        <a href="<?php echo SITE_URL; ?>Product/edit/<?php echo $product->id; ?>" class="btn btn-warning px-4">
                            <i class="bi bi-pencil me-1"></i> Sửa
                        </a>
                        <a href="<?php echo SITE_URL; ?>Product/delete/<?php echo $product->id; ?>" class="btn btn-danger px-4"
                           onclick="return confirm('Xóa sản phẩm này?')">
                            <i class="bi bi-trash me-1"></i> Xóa
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo SITE_URL; ?>Product/" class="btn btn-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
