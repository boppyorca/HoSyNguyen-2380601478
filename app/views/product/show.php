<?php include 'app/views/shares/header.php'; ?>

<h1>Chi tiết sản phẩm</h1>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?php echo $product->name; ?></h5>

        <?php if (!empty($product->image)): ?>
            <div class="mb-3">
                <img src="/webbanhang/uploads/products/<?php echo $product->image; ?>" class="img-fluid" alt="<?php echo $product->name; ?>" style="max-width: 300px;">
            </div>
        <?php endif; ?>

        <p class="card-text"><strong>Mô tả:</strong> <?php echo $product->description; ?></p>
        <p class="card-text"><strong>Giá:</strong> <?php echo $product->price; ?></p>
        <p class="card-text"><strong>Danh mục:</strong> <?php echo $product->category_id; ?></p>

        <a href="/webbanhang/Product/" class="btn btn-secondary">Quay lại danh sách</a>
        <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning">Sửa</a>
        <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Xóa</a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
