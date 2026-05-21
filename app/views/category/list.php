<?php include 'app/views/shares/header.php'; ?>

<h1>Danh sách danh mục</h1>
<a href="/webbanhang/Category/add" class="btn btn-success mb-2">Thêm danh mục mới</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo $category->id; ?></td>
                <td><?php echo htmlspecialchars($category->name); ?></td>
                <td><?php echo htmlspecialchars($category->description); ?></td>
                <td>
                    <a href="/webbanhang/Category/edit/<?php echo $category->id; ?>" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="/webbanhang/Category/delete/<?php echo $category->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'app/views/shares/footer.php'; ?>
