<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center my-5">
    <div class="col-md-8">
        <div class="card p-4 shadow-sm border-0">
            <div class="card-body">
                <h3 class="card-title text-center mb-4 fw-bold text-primary">
                    <i class="bi bi-arrow-counterclockwise"></i> Gửi Yêu Cầu Trả Hàng
                </h3>
                
                <?php
                $error = $_SESSION['error'] ?? null;
                unset($_SESSION['error']);
                if ($error):
                ?>
                    <div class="alert alert-danger border-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="mb-4">
                    <h5 class="fw-bold mb-3 text-white-50">Tóm tắt đơn hàng #<?php echo $order->id; ?></h5>
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
                                    <td colspan="3" class="text-end fw-bold">Tổng thanh toán:</td>
                                    <td class="text-end price-tag fw-bold fs-5"><?php echo number_format($grand_total, 0, '.', '.'); ?>đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <form method="POST" action="/webbanhang/Order/submitReturn" enctype="multipart/form-data">
                    <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                    
                    <div class="mb-3">
                        <label for="reason" class="form-label text-warning">Lý do trả hàng <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reason" name="reason" rows="4" placeholder="Vui lòng cung cấp chi tiết lý do bạn muốn trả hàng..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-warning d-block">Hình ảnh bằng chứng (nếu có)</label>
                        <div class="upload-zone" onclick="document.getElementById('evidence_image').click();">
                            <i class="bi bi-cloud-upload fs-1 text-primary"></i>
                            <p class="mt-2 text-white-50">Kéo thả hoặc click để chọn ảnh bằng chứng lỗi sản phẩm</p>
                            <span class="small text-muted" style="font-size: 0.8rem;">Chấp nhận file: JPG, JPEG, PNG, GIF (Tối đa 10MB)</span>
                            <input type="file" id="evidence_image" name="evidence_image" style="display: none;" accept="image/*">
                        </div>
                        <div class="text-center">
                            <img id="imgPreview" class="img-preview mx-auto d-block" alt="Preview Image">
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="/webbanhang/Order/show/<?php echo $order->id; ?>" class="btn btn-secondary">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary px-4">Gửi yêu cầu trả hàng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('evidence_image');
    const preview = document.getElementById('imgPreview');
    
    if (fileInput && preview) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                preview.style.display = 'none';
            }
        });
    }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
