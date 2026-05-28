<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title mb-0">
        <i class="bi bi-clock-history"></i> Lịch sử đặt hàng
    </div>
    <a href="/webbanhang/Product" class="btn btn-outline-primary">
        <i class="bi bi-shop"></i> Tiếp tục mua sắm
    </a>
</div>

<?php 
$success = SessionHelper::flash('success');
$error = SessionHelper::flash('error');
if ($success): 
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($success); ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="table-wrapper">
    <?php if (empty($orders)): ?>
        <div class="text-center py-5">
            <div class="fs-1 text-muted mb-3"><i class="bi bi-bag-x"></i></div>
            <h5 class="text-white-50">Bạn chưa có đơn đặt hàng nào.</h5>
            <p class="text-muted small">Hãy chọn sản phẩm bạn yêu thích và thực hiện đặt hàng ngay nhé!</p>
            <a href="/webbanhang/Product" class="btn btn-primary mt-2">Mua sắm ngay</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle text-nowrap">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th>Khách hàng</th>
                        <th>Phương thức thanh toán</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái đơn</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong class="text-white">#<?php echo $order->id; ?></strong></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                            <td>
                                <div><?php echo htmlspecialchars($order->name); ?></div>
                                <div class="text-muted small" style="font-size: 0.8rem;"><?php echo htmlspecialchars($order->phone); ?></div>
                            </td>
                            <td>
                                <?php 
                                if ($order->payment_method === 'cod') {
                                    echo '<i class="bi bi-truck text-muted me-1"></i> COD';
                                } elseif ($order->payment_method === 'bank_transfer') {
                                    echo '<i class="bi bi-bank text-primary me-1"></i> Chuyển khoản';
                                } elseif ($order->payment_method === 'e_wallet') {
                                    echo '<i class="bi bi-wallet2 text-warning me-1"></i> Ví MoMo';
                                } else {
                                    echo htmlspecialchars($order->payment_method);
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($order->payment_status === 'paid'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded">Đã thanh toán</span>
                                <?php elseif ($order->payment_status === 'refunded'): ?>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded">Đã hoàn tiền</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded">Chưa thanh toán</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                if ($order->status === 'pending') {
                                    echo '<span class="badge bg-warning text-dark px-2 py-1 rounded">Chờ xử lý</span>';
                                } elseif ($order->status === 'processing') {
                                    echo '<span class="badge bg-info text-dark px-2 py-1 rounded">Đang xử lý</span>';
                                } elseif ($order->status === 'shipping') {
                                    echo '<span class="badge bg-primary px-2 py-1 rounded text-white">Đang giao hàng</span>';
                                } elseif ($order->status === 'completed') {
                                    echo '<span class="badge bg-success px-2 py-1 rounded text-white">Hoàn thành</span>';
                                } elseif ($order->status === 'cancelled') {
                                    echo '<span class="badge bg-danger px-2 py-1 rounded text-white">Đã hủy</span>';
                                } else {
                                    echo '<span class="badge bg-secondary px-2 py-1 rounded text-white">' . htmlspecialchars($order->status) . '</span>';
                                }
                                ?>
                            </td>
                            <td class="text-end">
                                <a href="/webbanhang/Order/show/<?php echo $order->id; ?>" class="btn btn-sm btn-secondary me-1">
                                    <i class="bi bi-eye"></i> Xem chi tiết
                                </a>
                                <?php if ($order->status === 'pending'): ?>
                                    <form action="/webbanhang/Order/cancel/<?php echo $order->id; ?>" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-circle"></i> Hủy đơn
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-x-circle"></i> Hủy đơn
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
