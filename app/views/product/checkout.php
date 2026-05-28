<?php include 'app/views/shares/header.php'; ?>

<div class="page-title">
    <i class="bi bi-credit-card text-primary"></i> Thông tin thanh toán
</div>

<form method="POST" action="/webbanhang/Product/processCheckout">
    <div class="row g-4">
        <!-- Cột trái: Thông tin thanh toán & Giao hàng -->
        <div class="col-lg-7">
            <div class="card p-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-person"></i> Thông tin giao hàng</h5>
                    <hr class="border-secondary mb-3">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ tên khách hàng <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Nhập đầy đủ họ tên" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại liên hệ <span class="text-danger">*</span></label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="Nhập số điện thoại nhận hàng" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                        <textarea id="address" name="address" class="form-control" rows="3" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố" required></textarea>
                    </div>
                    
                    <div class="mb-3 mt-4">
                        <label class="form-label">Phương thức thanh toán <span class="text-danger">*</span></label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="pay_cod" value="cod" checked required>
                            <label class="form-check-label text-white" for="pay_cod">
                                <i class="bi bi-truck text-warning me-1"></i> Thanh toán khi nhận hàng (COD)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="pay_bank" value="bank_transfer" required>
                            <label class="form-check-label text-white" for="pay_bank">
                                <i class="bi bi-bank text-warning me-1"></i> Chuyển khoản ngân hàng
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="pay_wallet" value="e_wallet" required>
                            <label class="form-check-label text-white" for="pay_wallet">
                                <i class="bi bi-wallet2 text-warning me-1"></i> Ví điện tử (MoMo / ZaloPay)
                            </label>
                        </div>
                    </div>

                    <!-- Panel hướng dẫn thanh toán chi tiết -->
                    <div id="payment_details_container" class="mt-4 p-3 rounded" style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-color); display: none;">
                        <!-- Hướng dẫn COD -->
                        <div id="details_cod" class="payment-details-panel">
                            <h6 class="fw-bold text-warning mb-2"><i class="bi bi-truck"></i> Hướng dẫn thanh toán COD</h6>
                            <p class="text-muted mb-0 small">Quý khách vui lòng chuẩn bị số tiền mặt tương ứng để thanh toán trực tiếp cho nhân viên giao hàng khi nhận hàng.</p>
                        </div>
                        
                        <!-- Hướng dẫn Chuyển khoản Ngân hàng -->
                        <div id="details_bank" class="payment-details-panel" style="display: none;">
                            <h6 class="fw-bold text-warning mb-3"><i class="bi bi-bank"></i> Hướng dẫn Chuyển khoản MB Bank</h6>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-7">
                                    <div class="mb-2 small"><strong>Ngân hàng:</strong> MB Bank (Ngân hàng Quân Đội)</div>
                                    <div class="mb-2 small"><strong>Số tài khoản:</strong> <span class="text-white fw-bold">2380601478</span></div>
                                    <div class="mb-2 small"><strong>Chủ tài khoản:</strong> <span class="text-white fw-bold">HỒ SỸ NGUYỄN</span></div>
                                    <div class="mb-0 small text-white-50">Nội dung CK: <span class="text-warning fw-bold" id="bank-transfer-note">THANH TOAN DH</span></div>
                                </div>
                                <div class="col-md-5 text-center">
                                    <div class="p-2 bg-white rounded d-inline-block shadow-sm">
                                        <img id="vietqr_checkout_img" src="https://api.vietqr.io/image/970422-2380601478-qr-mQrjB4b.jpg?accountName=HO%20SY%20NGUYEN&addInfo=Thanh%20toan%20don%20hang" alt="VietQR" class="img-fluid" style="max-height: 130px;">
                                    </div>
                                    <div class="text-muted small mt-1" style="font-size: 0.72rem;">Quét mã VietQR để thanh toán</div>
                                </div>
                            </div>
                        </div>

                        <!-- Hướng dẫn Ví điện tử -->
                        <div id="details_wallet" class="payment-details-panel" style="display: none;">
                            <h6 class="fw-bold text-warning mb-3"><i class="bi bi-wallet2"></i> Hướng dẫn Chuyển ví MoMo</h6>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-7">
                                    <div class="mb-2 small"><strong>Ví điện tử:</strong> MoMo</div>
                                    <div class="mb-2 small"><strong>Số điện thoại:</strong> <span class="text-white fw-bold">0987654321</span></div>
                                    <div class="mb-2 small"><strong>Chủ tài khoản:</strong> <span class="text-white fw-bold">HỒ SỸ NGUYỄN</span></div>
                                    <div class="mb-0 small text-white-50">Nội dung chuyển: <span class="text-warning fw-bold">THANH TOAN DH</span></div>
                                </div>
                                <div class="col-md-5 text-center">
                                    <div class="p-3 bg-white rounded d-inline-block shadow-sm" style="border: 2px solid #a50064;">
                                        <div class="d-flex flex-column align-items-center justify-content-center" style="width: 80px; height: 80px; color: #a50064;">
                                            <i class="bi bi-qr-code-scan fs-2"></i>
                                            <span class="fw-bold mt-1" style="font-size: 0.65rem;">MOMO QR</span>
                                        </div>
                                    </div>
                                    <div class="text-muted small mt-1" style="font-size: 0.72rem;">Quét mã chuyển ví MoMo</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ô nhập mã giao dịch (Chỉ hiện khi chọn Chuyển khoản hoặc Ví) -->
                    <div id="transaction_id_group" class="mt-3 mb-0" style="display: none;">
                        <label for="transaction_id" class="form-label text-warning">Mã giao dịch / Ghi chú chuyển khoản <span class="text-danger">*</span></label>
                        <input type="text" id="transaction_id" name="transaction_id" class="form-control" placeholder="Ví dụ: Mã giao dịch ngân hàng hoặc Ví điện tử">
                        <span class="text-muted small d-block mt-1" style="font-size: 0.8rem;">Vui lòng chuyển tiền trước và nhập mã giao dịch xác thực tại đây để đối soát.</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Cột phải: Tóm tắt đơn hàng -->
        <div class="col-lg-5">
            <div class="card p-4 shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column h-100">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-bag-check"></i> Đơn hàng của bạn</h5>
                    <hr class="border-secondary mb-3">
                    
                    <div class="list-group list-group-flush mb-4 flex-grow-1" style="max-height: 350px; overflow-y: auto;">
                        <?php 
                        $checkout_total = 0;
                        if (isset($_SESSION['cart'])): 
                            foreach ($_SESSION['cart'] as $id => $item):
                                if (!isset($item['selected']) || !$item['selected']) continue;
                                $subtotal = $item['price'] * $item['quantity'];
                                $checkout_total += $subtotal;
                        ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between px-0 py-3 bg-transparent text-white-50 border-bottom border-secondary-subtle">
                                <div class="me-3">
                                    <span class="fw-bold text-white d-block"><?php echo htmlspecialchars($item['name']); ?></span>
                                    <span class="small text-muted">Số lượng: <?php echo $item['quantity']; ?></span>
                                </div>
                                <div class="text-end">
                                    <div class="price-tag"><?php echo number_format($subtotal, 0, '.', '.'); ?>đ</div>
                                    <small class="text-muted d-block" style="font-size: 0.75rem;"><?php echo number_format($item['price'], 0, '.', '.'); ?>đ / sp</small>
                                </div>
                            </div>
                        <?php 
                            endforeach;
                        endif; 
                        ?>
                    </div>
                    
                    <hr class="border-secondary mb-3">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fs-5 text-white-50 fw-bold">Tổng thanh toán:</span>
                        <span class="fs-3 price-tag" id="checkout-total-price"><?php echo number_format($checkout_total, 0, '.', '.'); ?>đ</span>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                            <i class="bi bi-shield-check"></i> Xác nhận thanh toán
                        </button>
                        <a href="/webbanhang/Product/cart" class="btn btn-secondary w-100 py-2">
                            <i class="bi bi-cart"></i> Quay lại giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const payCod = document.getElementById('pay_cod');
        const payBank = document.getElementById('pay_bank');
        const payWallet = document.getElementById('pay_wallet');
        
        const detailsContainer = document.getElementById('payment_details_container');
        const detailsCod = document.getElementById('details_cod');
        const detailsBank = document.getElementById('details_bank');
        const detailsWallet = document.getElementById('details_wallet');
        
        const transactionGroup = document.getElementById('transaction_id_group');
        const transactionInput = document.getElementById('transaction_id');
        
        const checkoutTotal = <?php echo $checkout_total; ?>;
        
        function updatePaymentMethod() {
            detailsContainer.style.display = 'block';
            if (payCod.checked) {
                detailsCod.style.display = 'block';
                detailsBank.style.display = 'none';
                detailsWallet.style.display = 'none';
                transactionGroup.style.display = 'none';
                transactionInput.removeAttribute('required');
            } else if (payBank.checked) {
                detailsCod.style.display = 'none';
                detailsBank.style.display = 'block';
                detailsWallet.style.display = 'none';
                transactionGroup.style.display = 'block';
                transactionInput.setAttribute('required', 'required');
                
                // Cập nhật QR VietQR động với số tiền thực tế
                const vietqrImg = document.getElementById('vietqr_checkout_img');
                if (vietqrImg) {
                    vietqrImg.src = "https://api.vietqr.io/image/970422-2380601478-qr-mQrjB4b.jpg?accountName=HO%20SY%20NGUYEN&amount=" + checkoutTotal + "&addInfo=Thanh%20toan%20don%20hang";
                }
            } else if (payWallet.checked) {
                detailsCod.style.display = 'none';
                detailsBank.style.display = 'none';
                detailsWallet.style.display = 'block';
                transactionGroup.style.display = 'block';
                transactionInput.setAttribute('required', 'required');
            }
        }
        
        payCod.addEventListener('change', updatePaymentMethod);
        payBank.addEventListener('change', updatePaymentMethod);
        payWallet.addEventListener('change', updatePaymentMethod);
        
        // Khởi động trạng thái mặc định
        updatePaymentMethod();
    });
    </script>
</form>

<?php include 'app/views/shares/footer.php'; ?>
