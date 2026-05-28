<?php include 'app/views/shares/header.php'; ?>

<style>
.stepper {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin-bottom: 10px;
}
.stepper-step {
    text-align: center;
    z-index: 2;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.step-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #172a45;
    border: 2px solid #233554;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    font-size: 1.1rem;
    margin-bottom: 8px;
    transition: all 0.3s;
}
.stepper-step.active .step-icon {
    background-color: #0a192f;
    border-color: var(--accent-color);
    color: var(--accent-color);
    box-shadow: 0 0 10px rgba(245, 166, 35, 0.4);
}
.stepper-step.completed .step-icon {
    background-color: var(--accent-color);
    border-color: var(--accent-color);
    color: #0a192f;
}
.step-label {
    font-size: 0.85rem;
    color: var(--text-muted);
    font-weight: 500;
}
.stepper-step.active .step-label,
.stepper-step.completed .step-label {
    color: #fff;
    font-weight: 600;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title mb-0">
        <i class="bi bi-receipt-cutoff"></i> Chi tiết đơn hàng #<?php echo $order->id; ?>
    </div>
    <?php if (SessionHelper::isAdmin()): ?>
        <a href="/webbanhang/Order" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    <?php else: ?>
        <a href="/webbanhang/Order/myOrders" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    <?php endif; ?>
</div>

<?php
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
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

<?php if (!SessionHelper::isAdmin()): ?>
    <!-- Stepper Tiến trình đơn hàng dành cho User -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4 text-white"><i class="bi bi-truck-flatbed text-warning"></i> Trạng thái đơn hàng</h5>
            
            <?php if ($order->status === 'cancelled'): ?>
                <div class="alert alert-danger mb-0" role="alert">
                    <i class="bi bi-x-circle-fill me-2"></i> Đơn hàng này đã bị hủy.
                </div>
            <?php else: ?>
                <?php
                $step1 = 'completed'; // Đặt hàng
                $step2 = '';
                $step3 = '';
                $step4 = '';
                $progress_width = '0%';
                
                if ($order->status === 'processing') {
                    $step2 = 'completed';
                    $step3 = 'active';
                    $progress_width = '25%';
                } elseif ($order->status === 'shipping') {
                    $step2 = 'completed';
                    $step3 = 'completed';
                    $step4 = 'active';
                    $progress_width = '50%';
                } elseif ($order->status === 'completed') {
                    $step2 = 'completed';
                    $step3 = 'completed';
                    $step4 = 'completed';
                    $progress_width = '75%';
                } else {
                    $step2 = 'active'; // pending
                }
                ?>
                <div class="position-relative py-2">
                    <div class="stepper">
                        <!-- Line background -->
                        <div style="position: absolute; top: 19px; left: 12.5%; right: 12.5%; height: 3px; background-color: #233554; z-index: 1;"></div>
                        <!-- Line active -->
                        <div style="position: absolute; top: 19px; left: 12.5%; width: <?php echo $progress_width; ?>; height: 3px; background-color: var(--accent-color); z-index: 1; transition: width 0.4s ease;"></div>
                        
                        <div class="stepper-step completed">
                            <div class="step-icon"><i class="bi bi-cart-check"></i></div>
                            <div class="step-label">Đã đặt hàng</div>
                        </div>
                        <div class="stepper-step <?php echo $step2; ?>">
                            <div class="step-icon"><i class="bi bi-gear"></i></div>
                            <div class="step-label">Đang xử lý</div>
                        </div>
                        <div class="stepper-step <?php echo $step3; ?>">
                            <div class="step-icon"><i class="bi bi-truck"></i></div>
                            <div class="step-label">Đang giao hàng</div>
                        </div>
                        <div class="stepper-step <?php echo $step4; ?>">
                            <div class="step-icon"><i class="bi bi-box-seam"></i></div>
                            <div class="step-label">Đã hoàn thành</div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Thông tin khách hàng & cập nhật trạng thái -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-person"></i> Thông tin giao hàng</h5>
                <p class="mb-2"><strong>Họ tên:</strong> <?php echo htmlspecialchars($order->name); ?></p>
                <p class="mb-2"><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order->phone); ?></p>
                <p class="mb-2"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order->address); ?></p>
                <p class="mb-2"><strong>Phương thức TT:</strong> 
                    <?php 
                    if ($order->payment_method === 'cod') {
                        echo '<span class="badge bg-secondary">COD (Khi nhận hàng)</span>';
                    } elseif ($order->payment_method === 'bank_transfer') {
                        echo '<span class="badge bg-primary">Chuyển khoản</span>';
                    } elseif ($order->payment_method === 'e_wallet') {
                        echo '<span class="badge bg-warning text-dark">Ví điện tử</span>';
                    } else {
                        echo htmlspecialchars($order->payment_method);
                    }
                    ?>
                </p>
                <p class="mb-2"><strong>Thanh toán:</strong> 
                    <?php 
                    if ($order->payment_status === 'paid') {
                        echo '<span class="badge bg-success">Đã thanh toán</span>';
                    } elseif ($order->payment_status === 'refunded') {
                        echo '<span class="badge bg-info text-dark">Đã hoàn tiền</span>';
                    } else {
                        echo '<span class="badge bg-danger">Chưa thanh toán</span>';
                    }
                    ?>
                </p>
                <?php if (!empty($order->transaction_id)): ?>
                    <p class="mb-2"><strong>Mã giao dịch:</strong> <code class="text-warning"><?php echo htmlspecialchars($order->transaction_id); ?></code></p>
                <?php endif; ?>
                <p class="mb-0"><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></p>
            </div>
        </div>

        <?php if (SessionHelper::isAdmin()): ?>
            <!-- Phần dành riêng cho Admin -->
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-pencil-square"></i> Trạng thái đơn hàng</h5>
                    <form method="POST" action="/webbanhang/Order/updateStatus">
                        <input type="hidden" name="id" value="<?php echo $order->id; ?>">
                        <div class="mb-3">
                            <label for="status" class="form-label">Chọn trạng thái đơn</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" <?php echo ($order->status === 'pending') ? 'selected' : ''; ?>>Chờ xử lý</option>
                                <option value="processing" <?php echo ($order->status === 'processing') ? 'selected' : ''; ?>>Đang xử lý</option>
                                <option value="shipping" <?php echo ($order->status === 'shipping') ? 'selected' : ''; ?>>Đang giao hàng</option>
                                <option value="completed" <?php echo ($order->status === 'completed') ? 'selected' : ''; ?>>Hoàn thành</option>
                                <option value="cancelled" <?php echo ($order->status === 'cancelled') ? 'selected' : ''; ?> <?php echo ($order->status === 'shipping' || $order->status === 'completed') ? 'disabled' : ''; ?>>Đã hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-credit-card"></i> Trạng thái thanh toán</h5>
                    <form method="POST" action="/webbanhang/Order/updatePaymentStatus">
                        <input type="hidden" name="id" value="<?php echo $order->id; ?>">
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Chọn trạng thái TT</label>
                            <select name="payment_status" id="payment_status" class="form-select">
                                <option value="unpaid" <?php echo ($order->payment_status === 'unpaid') ? 'selected' : ''; ?>>Chưa thanh toán</option>
                                <option value="paid" <?php echo ($order->payment_status === 'paid') ? 'selected' : ''; ?>>Đã thanh toán</option>
                                <option value="refunded" <?php echo ($order->payment_status === 'refunded') ? 'selected' : ''; ?>>Đã hoàn tiền</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100 text-white">
                            <i class="bi bi-check-circle"></i> Cập nhật thanh toán
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- Phần dành riêng cho User -->
            <?php if ($order->status === 'pending'): ?>
                <div class="card mb-4">
                    <div class="card-body p-4 text-center">
                        <h5 class="fw-bold mb-2 text-warning"><i class="bi bi-exclamation-circle"></i> Hủy đơn hàng</h5>
                        <p class="small text-muted mb-3">Bạn có thể hủy đơn hàng này bất kỳ lúc nào nếu đơn hàng chưa được xử lý.</p>
                        <form action="/webbanhang/Order/cancel/<?php echo $order->id; ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-x-circle"></i> Hủy đơn hàng ngay
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($order->payment_status === 'unpaid' && ($order->payment_method === 'bank_transfer' || $order->payment_method === 'e_wallet')): ?>
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-qr-code-scan"></i> Thanh toán đơn hàng</h5>
                        <hr class="border-secondary mb-3">
                        
                        <?php 
                        $total_bill = 0;
                        foreach ($details as $item) {
                            $total_bill += $item->price * $item->quantity;
                        }
                        ?>

                        <?php if ($order->payment_method === 'bank_transfer'): ?>
                            <div class="mb-2 small"><strong>Ngân hàng:</strong> MB Bank</div>
                            <div class="mb-2 small"><strong>Số tài khoản:</strong> <span class="text-white fw-bold">2380601478</span></div>
                            <div class="mb-2 small"><strong>Chủ tài khoản:</strong> <span class="text-white fw-bold">HỒ SỸ NGUYỄN</span></div>
                            <div class="mb-2 small"><strong>Số tiền:</strong> <span class="text-warning fw-bold"><?php echo number_format($total_bill, 0, '.', '.'); ?>đ</span></div>
                            <div class="mb-3 small"><strong>Nội dung CK:</strong> <span class="text-warning fw-bold">DH<?php echo $order->id; ?></span></div>
                            
                            <div class="text-center bg-white p-2 rounded d-block mx-auto mb-2" style="width: 140px;">
                                <?php 
                                $qr_url = "https://api.vietqr.io/image/970422-2380601478-qr-mQrjB4b.jpg?accountName=HO%20SY%20NGUYEN&amount=" . $total_bill . "&addInfo=DH" . $order->id;
                                ?>
                                <img src="<?php echo $qr_url; ?>" alt="VietQR" class="img-fluid">
                            </div>
                            <div class="text-muted small text-center" style="font-size: 0.75rem;">Quét QR để chuyển khoản</div>
                        
                        <?php elseif ($order->payment_method === 'e_wallet'): ?>
                            <div class="mb-2 small"><strong>Ví điện tử:</strong> MoMo</div>
                            <div class="mb-2 small"><strong>Số điện thoại:</strong> <span class="text-white fw-bold">0987654321</span></div>
                            <div class="mb-2 small"><strong>Chủ tài khoản:</strong> <span class="text-white fw-bold">HỒ SỸ NGUYỄN</span></div>
                            <div class="mb-2 small"><strong>Số tiền:</strong> <span class="text-warning fw-bold"><?php echo number_format($total_bill, 0, '.', '.'); ?>đ</span></div>
                            <div class="mb-3 small"><strong>Nội dung:</strong> <span class="text-warning fw-bold">DH<?php echo $order->id; ?></span></div>
                            
                            <div class="text-center bg-white p-3 rounded d-block mx-auto mb-2" style="width: 120px; border: 2px solid #a50064;">
                                <div class="d-flex flex-column align-items-center justify-content-center" style="width: 80px; height: 80px; color: #a50064;">
                                    <i class="bi bi-qr-code-scan fs-2"></i>
                                    <span class="fw-bold mt-1" style="font-size: 0.65rem;">MOMO QR</span>
                                </div>
                            </div>
                            <div class="text-muted small text-center" style="font-size: 0.75rem;">Quét mã để chuyển MoMo</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Danh sách sản phẩm đã mua -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-box-seam"></i> Sản phẩm trong đơn</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Hình ảnh</th>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $grand_total = 0;
                            foreach ($details as $item): 
                                $subtotal = $item->price * $item->quantity;
                                $grand_total += $subtotal;
                            ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($item->product_image)): ?>
                                            <img src="/webbanhang/uploads/products/<?php echo htmlspecialchars($item->product_image); ?>" 
                                                 alt="<?php echo htmlspecialchars($item->product_name); ?>" 
                                                 class="img-thumbnail" style="max-width: 60px;">
                                        <?php else: ?>
                                            <div class="bg-light text-center text-muted" style="width: 60px; height: 60px; line-height: 60px; border-radius: 6px;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-white"><?php echo htmlspecialchars($item->product_name); ?></div>
                                    </td>
                                    <td><?php echo number_format($item->price, 0, '.', '.'); ?>đ</td>
                                    <td><?php echo htmlspecialchars($item->quantity); ?></td>
                                    <td class="text-end fw-bold price-tag"><?php echo number_format($subtotal, 0, '.', '.'); ?>đ</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold fs-5 text-white-50">Tổng tiền hóa đơn:</td>
                                <td class="text-end fw-bold fs-5 price-tag"><?php echo number_format($grand_total, 0, '.', '.'); ?>đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

