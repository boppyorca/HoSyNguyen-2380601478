<?php include 'app/views/shares/header.php'; ?>

<h1>Danh sách sản phẩm</h1>
<a href="/webbanhang/Product/add" class="btn btn-success mb-2">Thêm sản phẩm mới</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Mô tả</th>
            <th>Giá</th>
            <th>Hình ảnh</th>
            <th>Danh mục</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product->id; ?></td>
                <td><?php echo htmlspecialchars($product->name); ?></td>
                <td><?php echo htmlspecialchars(substr($product->description, 0, 50)); ?></td>
                <td><?php echo number_format($product->price, 2, '.', ','); ?></td>
                <td>
                    <?php if (!empty($product->image)): ?>
                        <img src="/webbanhang/uploads/products/<?php echo htmlspecialchars($product->image); ?>" width="50" height="50" alt="<?php echo htmlspecialchars($product->name); ?>" style="object-fit: cover; border-radius: 4px;">
                    <?php else: ?>
                        <span class="text-muted">Không có ảnh</span>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($product->category_name ?? 'N/A'); ?></td>
                <td>
                    <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="btn btn-info btn-sm">Xem</a>
                    <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'app/views/shares/footer.php'; ?>
