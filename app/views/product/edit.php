<?php include 'app/views/shares/header.php'; ?>

<h1>Sửa sản phẩm</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="/webbanhang/Product/update" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $product->id; ?>">

    <div class="form-group">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product->name); ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($product->description); ?></textarea>
    </div>

    <div class="form-group">
        <label for="price">Giá:</label>
        <input type="number" id="price" name="price" class="form-control" step="0.01" value="<?php echo htmlspecialchars($product->price); ?>" required>
    </div>

    <div class="form-group">
        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" class="form-control" required>
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category->id; ?>" <?php echo ($category->id == $product->category_id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="image">Hình ảnh:</label>
        <?php if (!empty($product->image)): ?>
            <div class="mb-2">
                <img src="/webbanhang/uploads/products/<?php echo $product->image; ?>" width="100" height="100" alt="<?php echo htmlspecialchars($product->name); ?>">
            </div>
        <?php endif; ?>
        <input type="file" id="image" name="image" class="form-control" accept="image/*">
        <small class="form-text text-muted">Định dạng: JPG, JPEG, PNG, GIF. Kích thước tối đa: 10MB. Để trống để giữ ảnh hiện tại</small>
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
    <a href="/webbanhang/Product/" class="btn btn-secondary">Quay lại danh sách</a>
</form>

<?php include 'app/views/shares/footer.php'; ?>
