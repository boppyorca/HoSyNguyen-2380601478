<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title mb-0">
        <i class="bi bi-arrow-counterclockwise"></i> Danh sách yêu cầu trả hàng (Admin)
    </div>
</div>

<?php
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
if ($success):
?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($success); ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="table-wrapper">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Mã Yêu Cầu</th>
                <th>Mã Đơn Hàng</th>
                <th>Khách Hàng</th>
                <th>Ngày Gửi</th>
                <th>Trạng Thế</th>
                <th class="text-end">Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($returns as $item): ?>
                <tr>
                    <td class="fw-bold">#<?php echo $item->id; ?></td>
                    <td><strong class="text-white">#<?php echo $item->order_id; ?></strong></td>
                    <td>
                        <div><?php echo htmlspecialchars($item->customer_name); ?></div>
                        <div class="text-muted small" style="font-size: 0.8rem;"><?php echo htmlspecialchars($item->customer_phone); ?></div>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($item->created_at)); ?></td>
                    <td>
                        <?php 
                        $status_class = 'bg-secondary';
                        $status_text = $item->status;
                        if ($item->status === 'pending') {
                            $status_class = 'bg-warning text-dark';
                            $status_text = 'Chờ duyệt';
                        } elseif ($item->status === 'approved') {
                            $status_class = 'bg-info text-dark';
                            $status_text = 'Đã duyệt (Chờ thu hồi)';
                        } elseif ($item->status === 'rejected') {
                            $status_class = 'bg-danger';
                            $status_text = 'Bị từ chối';
                        } elseif ($item->status === 'completed') {
                            $status_class = 'bg-success';
                            $status_text = 'Đã hoàn tiền';
                        }
                        ?>
                        <span class="badge <?php echo $status_class; ?> px-3 py-2" style="font-size: 0.8rem; border-radius: 20px;">
                            <?php echo $status_text; ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="/webbanhang/Order/viewReturn/<?php echo $item->id; ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye"></i> Xem Chi Tiết
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($returns)): ?>
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-3"></i>
                        Không có yêu cầu trả hàng nào.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'app/views/shares/footer.php'; ?>
