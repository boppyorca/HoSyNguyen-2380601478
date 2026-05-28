<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center my-4">
    <div class="col-md-8">
        <div class="card p-4 shadow-sm border-0 mb-4 text-center">
            <div class="card-body">
                <div class="display-4 text-success mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h2 class="fw-bold mb-2 text-white">Đặt Hàng Thành Công!</h2>
                <p class="text-muted mb-0">Cảm ơn bạn đã mua sắm. Đơn hàng của bạn đã được tiếp nhận và đang chờ xác thực thanh toán.</p>
            </div>
        </div>

        <?php if ($order): ?>
            <?php 
            $grand_total = 0;
            foreach ($details as $item) {
                $grand_total += $item->price * $item->quantity;
            }
            ?>
            <div class="row g-4">
                <!-- Thông tin đơn hàng -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-receipt"></i> Thông tin đơn hàng #<?php echo $order->id; ?></h5>
                            <hr class="border-secondary mb-3">
                            <p class="mb-2"><strong>Khách hàng:</strong> <?php echo htmlspecialchars($order->name); ?></p>
                            <p class="mb-2"><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order->phone); ?></p>
                            <p class="mb-2"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order->address); ?></p>
                            <p class="mb-2"><strong>Phương thức TT:</strong> 
                                <?php 
                                if ($order->payment_method === 'cod') {
                                    echo '<span class="badge bg-secondary">COD (Thanh toán khi nhận hàng)</span>';
                                } elseif ($order->payment_method === 'bank_transfer') {
                                    echo '<span class="badge bg-primary">Chuyển khoản ngân hàng</span>';
                                } elseif ($order->payment_method === 'e_wallet') {
                                    echo '<span class="badge bg-warning text-dark">Ví điện tử MoMo</span>';
                                } else {
                                    echo htmlspecialchars($order->payment_method);
                                }
                                ?>
                            </p>
                            <?php if (!empty($order->transaction_id)): ?>
                                <p class="mb-2"><strong>Mã giao dịch đối chiếu:</strong> <code class="text-warning"><?php echo htmlspecialchars($order->transaction_id); ?></code></p>
                            <?php endif; ?>
                            <p class="mb-0"><strong>Tổng tiền thanh toán:</strong> <span class="price-tag fs-5"><?php echo number_format($grand_total, 0, '.', '.'); ?>đ</span></p>
                        </div>
                    </div>
                </div>

                <!-- Hướng dẫn thanh toán nếu là Chuyển khoản hoặc Ví điện tử -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <?php if ($order->payment_method === 'bank_transfer'): ?>
                                <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-qr-code-scan"></i> Hướng dẫn chuyển khoản</h5>
                                <hr class="border-secondary mb-3">
                                <div class="mb-2 small"><strong>Ngân hàng:</strong> MB Bank</div>
                                <div class="mb-2 small"><strong>Số tài khoản:</strong> <span class="text-white fw-bold">2380601478</span></div>
                                <div class="mb-2 small"><strong>Chủ tài khoản:</strong> <span class="text-white fw-bold">HỒ SỸ NGUYỄN</span></div>
                                <div class="mb-3 small"><strong>Nội dung:</strong> <span class="text-warning fw-bold">DH<?php echo $order->id; ?></span></div>
                                
                                <div class="text-center mt-3 bg-white p-2 rounded d-inline-block mx-auto d-block" style="width: 140px;">
                                    <?php 
                                    $qr_url = "https://api.vietqr.io/image/970422-2380601478-qr-mQrjB4b.jpg?accountName=HO%20SY%20NGUYEN&amount=" . $grand_total . "&addInfo=DH" . $order->id;
                                    ?>
                                    <img src="<?php echo $qr_url; ?>" alt="VietQR" class="img-fluid">
                                </div>
                                <div class="text-muted small text-center mt-1" style="font-size: 0.75rem;">Quét mã VietQR để thanh toán chính xác số tiền</div>
                            
                            <?php elseif ($order->payment_method === 'e_wallet'): ?>
                                <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-wallet2"></i> Hướng dẫn chuyển ví MoMo</h5>
                                <hr class="border-secondary mb-3">
                                <div class="mb-2 small"><strong>Ví điện tử:</strong> MoMo</div>
                                <div class="mb-2 small"><strong>Số điện thoại ví:</strong> <span class="text-white fw-bold">0987654321</span></div>
                                <div class="mb-2 small"><strong>Chủ ví:</strong> <span class="text-white fw-bold">HỒ SỸ NGUYỄN</span></div>
                                <div class="mb-3 small"><strong>Nội dung:</strong> <span class="text-warning fw-bold">DH<?php echo $order->id; ?></span></div>
                                
                                <div class="text-center mt-3 bg-white p-3 rounded d-inline-block mx-auto d-block" style="width: 120px; border: 2px solid #a50064;">
                                    <div class="d-flex flex-column align-items-center justify-content-center" style="width: 80px; height: 80px; color: #a50064;">
                                        <i class="bi bi-qr-code-scan fs-2"></i>
                                        <span class="fw-bold mt-1" style="font-size: 0.65rem;">MOMO QR</span>
                                    </div>
                                </div>
                                <div class="text-muted small text-center mt-1" style="font-size: 0.75rem;">Quét chuyển khoản ví MoMo</div>
                            
                            <?php else: ?>
                                <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-truck"></i> Giao hàng COD</h5>
                                <hr class="border-secondary mb-3">
                                <p class="small text-muted">Đơn hàng của quý khách sẽ được đóng gói và giao đến địa chỉ trong thời gian sớm nhất.</p>
                                <p class="small text-muted">Quý khách vui lòng chuẩn bị số tiền mặt <strong class="text-warning"><?php echo number_format($grand_total, 0, '.', '.'); ?>đ</strong> để thanh toán khi nhận hàng.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="/webbanhang/Order/myOrders" class="btn btn-primary px-4 py-2 me-2">
                <i class="bi bi-bag-check me-1"></i> Theo dõi đơn hàng
            </a>
            <a href="/webbanhang/Product" class="btn btn-secondary px-4 py-2">
                <i class="bi bi-arrow-left me-1"></i> Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

