<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title mb-0">
        <i class="bi bi-tags"></i> Danh sách danh mục
    </div>
    <a href="<?php echo SITE_URL; ?>Category/add" class="btn btn-success px-4">
        <i class="bi bi-plus-lg me-1"></i> Thêm danh mục
    </a>
</div>

<div class="table-wrapper">
    <table class="table table-hover">
        <thead>
            <tr>
                <th style="width:60px">#</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th style="width:160px" class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td class="text-muted"><?php echo $category->id; ?></td>
                <td>
                    <span class="fw-600 d-flex align-items-center gap-2">
                        <i class="bi bi-folder2 text-primary"></i>
                        <?php echo htmlspecialchars($category->name); ?>
                    </span>
                </td>
                <td class="text-muted"><?php echo htmlspecialchars($category->description ?? ''); ?></td>
                <td class="text-center">
                    <a href="<?php echo SITE_URL; ?>Category/edit/<?php echo $category->id; ?>"
                       class="btn btn-warning btn-sm me-1" title="Sửa">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?php echo SITE_URL; ?>Category/delete/<?php echo $category->id; ?>"
                       class="btn btn-danger btn-sm" title="Xóa"
                       onclick="return confirm('Xóa danh mục này?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($categories)): ?>
            <tr>
                <td colspan="4" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox me-2"></i>Chưa có danh mục nào.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'app/views/shares/footer.php'; ?>
