<?php include 'app/views/shares/header.php'; ?>

<h1>Thêm danh mục mới</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="/webbanhang/Category/save">
    <div class="form-group">
        <label for="name">Tên danh mục:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Thêm danh mục</button>
    <a href="/webbanhang/Category" class="btn btn-secondary">Quay lại danh sách</a>
</form>

<?php include 'app/views/shares/footer.php'; ?>
