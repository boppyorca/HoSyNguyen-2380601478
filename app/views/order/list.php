<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title mb-0">
        <i class="bi bi-receipt"></i> Quản lý đơn hàng (Admin)
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Doanh thu -->
    <div class="col-md-6 col-lg">
        <div class="card p-3 h-100" style="border-left: 4px solid var(--accent-color);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-bold text-uppercase mb-1">Doanh thu</div>
                    <div class="fs-4 fw-bold" style="color: var(--accent-color);"><?php echo number_format($stats->revenue, 0, ',', '.'); ?>đ</div>
                </div>
                <div class="rounded p-2 fs-3" style="background: rgba(245, 166, 35, 0.1); color: var(--accent-color);">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Tổng đơn hàng -->
    <div class="col-md-6 col-lg">
        <div class="card p-3 h-100" style="border-left: 4px solid #3b82f6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-bold text-uppercase mb-1">Tổng đơn</div>
                    <div class="fs-4 fw-bold" style="color: #3b82f6;"><?php echo $stats->total; ?></div>
                </div>
                <div class="rounded p-2 fs-3" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Đã thanh toán -->
    <div class="col-md-4 col-lg">
        <div class="card p-3 h-100" style="border-left: 4px solid #10b981;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-bold text-uppercase mb-1">Đã thanh toán</div>
                    <div class="fs-4 fw-bold" style="color: #10b981;"><?php echo $stats->paid; ?></div>
                </div>
                <div class="rounded p-2 fs-3" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="bi bi-credit-card"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Đang giao -->
    <div class="col-md-4 col-lg">
        <div class="card p-3 h-100" style="border-left: 4px solid #06b6d4;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-bold text-uppercase mb-1">Đang giao</div>
                    <div class="fs-4 fw-bold" style="color: #06b6d4;"><?php echo $stats->shipping; ?></div>
                </div>
                <div class="rounded p-2 fs-3" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                    <i class="bi bi-truck"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Đã giao -->
    <div class="col-md-4 col-lg">
        <div class="card p-3 h-100" style="border-left: 4px solid #a855f7;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-bold text-uppercase mb-1">Đã giao</div>
                    <div class="fs-4 fw-bold" style="color: #a855f7;"><?php echo $stats->completed; ?></div>
                </div>
                <div class="rounded p-2 fs-3" style="background: rgba(168, 85, 247, 0.1); color: #a855f7;">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
        </div>
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
