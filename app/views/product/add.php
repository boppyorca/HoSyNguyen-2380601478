<?php include 'app/views/shares/header.php'; ?>

<h1>Thêm sản phẩm mới</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="/webbanhang/Product/save" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control" required></textarea>
    </div>

    <div class="form-group">
        <label for="price">Giá:</label>
        <input type="number" id="price" name="price" class="form-control" step="0.01" required>
    </div>

    <div class="form-group">
        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" class="form-control" required>
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category->id; ?>"><?php echo htmlspecialchars($category->name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="image">Hình ảnh:</label>
        <input type="file" id="image" name="image" class="form-control" accept="image/*">
        <small class="form-text text-muted">Định dạng: JPG, JPEG, PNG, GIF. Kích thước tối đa: 10MB</small>
    </div>

    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
    <a href="/webbanhang/Product/" class="btn btn-secondary">Quay lại danh sách</a>
</form>

<?php include 'app/views/shares/footer.php'; ?>
