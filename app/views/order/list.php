<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title mb-0">
        <i class="bi bi-receipt"></i> Quản lý đơn hàng (Admin)
    </div>
</div>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>Mã ĐH</th>
                <th>Khách hàng</th>
                <th>Số điện thoại</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td class="fw-bold">#<?php echo $order->id; ?></td>
                    <td><?php echo htmlspecialchars($order->name); ?></td>
                    <td><?php echo htmlspecialchars($order->phone); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                    <td>
                        <?php 
                        $status_class = 'bg-secondary';
                        $status_text = $order->status;
                        if ($order->status === 'pending') {
                            $status_class = 'bg-warning text-dark';
                            $status_text = 'Chờ xử lý';
                        } elseif ($order->status === 'processing') {
                            $status_class = 'bg-info text-dark';
                            $status_text = 'Đang xử lý';
                        } elseif ($order->status === 'completed') {
                            $status_class = 'bg-success';
                            $status_text = 'Hoàn thành';
                        } elseif ($order->status === 'cancelled') {
                            $status_class = 'bg-danger';
                            $status_text = 'Đã hủy';
                        }
                        ?>
                        <span class="badge <?php echo $status_class; ?> px-3 py-2" style="font-size: 0.8rem; border-radius: 20px;">
                            <?php echo $status_text; ?>
                        </span>
                    </td>
                    <td>
                        <a href="/webbanhang/Order/show/<?php echo $order->id; ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye"></i> Chi tiết
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-3"></i>
                        Chưa có đơn hàng nào được đặt.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'app/views/shares/footer.php'; ?>
