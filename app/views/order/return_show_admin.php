<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title mb-0">
        <i class="bi bi-arrow-counterclockwise"></i> Chi tiết yêu cầu trả hàng #<?php echo $return->id; ?>
    </div>
    <a href="/webbanhang/Order/returns" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách
    </a>
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

<div class="row g-4">
    <!-- Cột trái: Chi tiết yêu cầu & Xử lý -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-info-circle"></i> Thông tin yêu cầu</h5>
                <hr class="border-secondary mb-3">
                <p class="mb-2"><strong>Mã đơn hàng:</strong> #<?php echo $return->order_id; ?></p>
                <p class="mb-2"><strong>Khách hàng:</strong> <?php echo htmlspecialchars($return->customer_name); ?></p>
                <p class="mb-2"><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($return->customer_phone); ?></p>
                <p class="mb-2"><strong>Địa chỉ nhận hàng:</strong> <?php echo htmlspecialchars($return->customer_address); ?></p>
                <p class="mb-2"><strong>Ngày đặt đơn:</strong> <?php echo date('d/m/Y H:i', strtotime($return->order_date)); ?></p>
                <p class="mb-2"><strong>Ngày yêu cầu trả:</strong> <?php echo date('d/m/Y H:i', strtotime($return->created_at)); ?></p>
                <p class="mb-3 text-warning"><strong>Lý do trả hàng:</strong></p>
                <div class="p-3 rounded mb-3" style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-color);">
                    <?php echo nl2br(htmlspecialchars($return->reason)); ?>
                </div>

                <?php if (!empty($return->evidence_image)): ?>
                    <p class="mb-2"><strong>Hình ảnh bằng chứng:</strong></p>
                    <div class="mb-3">
                        <img src="/webbanhang/public/uploads/returns/<?php echo htmlspecialchars($return->evidence_image); ?>" 
                             alt="Bằng chứng trả hàng" class="img-fluid rounded border border-secondary" style="max-height: 250px;">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Block xử lý yêu cầu -->
        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-gear-fill"></i> Tiến trình xử lý yêu cầu</h5>
                <hr class="border-secondary mb-3">
                
                <?php if ($return->status === 'pending' || $return->status === 'approved'): ?>
                    <form method="POST" action="/webbanhang/Order/processReturn">
                        <input type="hidden" name="id" value="<?php echo $return->id; ?>">
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Cập nhật trạng thái</label>
                            <select name="status" id="status" class="form-select">
                                <?php if ($return->status === 'pending'): ?>
                                    <option value="pending" selected>Chờ duyệt</option>
                                    <option value="approved">Duyệt (Chờ nhận lại hàng)</option>
                                    <option value="rejected">Từ chối yêu cầu</option>
                                <?php elseif ($return->status === 'approved'): ?>
                                    <option value="approved" selected>Đã duyệt (Chờ nhận lại hàng)</option>
                                    <option value="completed">Đã nhận hàng & Hoàn tiền</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="admin_note" class="form-label">Ghi chú của Admin (Phản hồi cho khách hàng)</label>
                            <textarea class="form-control" id="admin_note" name="admin_note" rows="3" placeholder="Ghi chú phản hồi hoặc lý do từ chối..."><?php echo htmlspecialchars($return->admin_note ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Cập nhật tiến trình
                        </button>
                    </form>
                <?php else: ?>
                    <p class="mb-2"><strong>Trạng thái hiện tại:</strong> 
                        <?php 
                        if ($return->status === 'rejected') {
                            echo '<span class="badge bg-danger">Bị từ chối</span>';
                        } elseif ($return->status === 'completed') {
                            echo '<span class="badge bg-success">Đã hoàn tiền & Đóng yêu cầu</span>';
                        }
                        ?>
                    </p>
                    <?php if (!empty($return->admin_note)): ?>
                        <p class="mb-2"><strong>Phản hồi của Admin:</strong></p>
                        <div class="p-3 rounded text-white-50" style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-color);">
                            <?php echo nl2br(htmlspecialchars($return->admin_note)); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Cột phải: Sản phẩm trong đơn hàng -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-box-seam"></i> Danh sách sản phẩm mua</h5>
                <hr class="border-secondary mb-3">
                <div class="table-responsive">
                    <table class="table align-middle text-white-50">
                        <thead>
                            <tr>
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
                                    <td><span class="text-white fw-bold"><?php echo htmlspecialchars($item->product_name); ?></span></td>
                                    <td><?php echo number_format($item->price, 0, '.', '.'); ?>đ</td>
                                    <td><?php echo htmlspecialchars($item->quantity); ?></td>
                                    <td class="text-end price-tag fw-bold"><?php echo number_format($subtotal, 0, '.', '.'); ?>đ</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Tổng tiền hóa đơn:</td>
                                <td class="text-end price-tag fw-bold fs-5"><?php echo number_format($grand_total, 0, '.', '.'); ?>đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
