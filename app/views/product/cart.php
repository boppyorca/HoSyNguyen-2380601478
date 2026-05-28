<?php include 'app/views/shares/header.php'; ?>

<?php
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

<div class="page-title">
    <i class="bi bi-cart text-primary"></i> Giỏ hàng của bạn
</div>

<?php if ($success): ?>
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

<?php if (!empty($cart)): ?>
    <form method="POST" action="/webbanhang/Product/updateCart">
        <div class="card p-4 mb-4">
            <div class="list-group list-group-flush">
                <?php foreach ($cart as $id => $item): ?>
                    <div class="list-group-item border-bottom border-secondary-subtle py-3 px-0 d-flex align-items-center gap-3" style="background: transparent;">
                        <!-- Checkbox chọn sản phẩm -->
                        <div class="form-check fs-5 me-2">
                            <input class="form-check-input cart-item-checkbox" type="checkbox" name="selected_items[]" value="<?php echo $id; ?>" <?php echo (!isset($item['selected']) || $item['selected']) ? 'checked' : ''; ?> data-price="<?php echo $item['price']; ?>" data-id="<?php echo $id; ?>" style="cursor: pointer;">
                        </div>

                        <!-- Hình ảnh sản phẩm -->
                        <?php if (!empty($item['image'])): ?>
                            <img src="/webbanhang/uploads/products/<?php echo htmlspecialchars($item['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                 class="product-img" style="width: 80px; height: 80px;">
                        <?php else: ?>
                            <div class="no-img" style="width: 80px; height: 80px; font-size: 2rem;">
                                <i class="bi bi-image"></i>
                            </div>
                        <?php endif; ?>

                        <!-- Thông tin sản phẩm -->
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2 text-white"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                            <!-- Điều chỉnh số lượng -->
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted small me-2">Số lượng:</span>
                                <div class="input-group input-group-sm" style="max-width: 110px;">
                                    <button class="btn btn-outline-secondary" type="button" style="border-color: var(--border-color); color: #fff;" onclick="const input = this.nextElementSibling; input.value = Math.max(1, parseInt(input.value) - 1); input.dispatchEvent(new Event('change'));">-</button>
                                    <input type="number" class="form-control text-center cart-item-qty" name="quantities[<?php echo $id; ?>]" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1" max="100" data-price="<?php echo $item['price']; ?>" data-id="<?php echo $id; ?>" style="background-color: var(--input-bg); border-color: var(--border-color); color: #fff; font-weight: 600;">
                                    <button class="btn btn-outline-secondary" type="button" style="border-color: var(--border-color); color: #fff;" onclick="const input = this.previousElementSibling; input.value = Math.min(100, parseInt(input.value) + 1); input.dispatchEvent(new Event('change'));">+</button>
                                </div>
                            </div>
                        </div>

                        <!-- Giá bán & Tổng dòng -->
                        <div class="text-end">
                            <div class="price-tag fs-5 mb-1"><?php echo number_format($item['price'], 0, '.', '.'); ?>đ</div>
                            <small class="text-muted d-block" id="subtotal-<?php echo $id; ?>">Tổng: <?php echo number_format($item['price'] * $item['quantity'], 0, '.', '.'); ?>đ</small>
                        </div>

                        <!-- Nút xóa sản phẩm -->
                        <div>
                            <a href="/webbanhang/Product/removeFromCart/<?php echo $id; ?>" class="btn btn-sm btn-outline-danger border-0 ms-2" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?');" title="Xóa sản phẩm">
                                <i class="bi bi-trash fs-5"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Hộp tổng tiền tạm tính -->
        <div class="card p-4 mb-4 border-0" style="background-color: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-color) !important;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="text-muted fs-6">Đã chọn:</span>
                    <span class="text-white fw-bold fs-5 ms-1" id="cart-selected-count">0</span>
                    <span class="text-muted small"> sản phẩm để thanh toán</span>
                </div>
                <div class="text-end">
                    <span class="text-white-50 fs-5 me-2">Tổng tiền tạm tính:</span>
                    <span class="price-tag fs-3" id="cart-grand-total">0đ</span>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="/webbanhang/Product/keepCartAndRedirect" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
            </a>
            <button type="submit" name="submit_action" value="update" class="btn btn-secondary">
                <i class="bi bi-arrow-clockwise"></i> Cập nhật giỏ hàng
            </button>
            <button type="submit" name="submit_action" value="checkout" class="btn btn-primary px-4">
                Tiến hành thanh toán <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.cart-item-checkbox');
        const quantityInputs = document.querySelectorAll('.cart-item-qty');
        const grandTotalEl = document.getElementById('cart-grand-total');
        const selectedCountEl = document.getElementById('cart-selected-count');
        
        function formatCurrency(number) {
            return new Intl.NumberFormat('vi-VN').format(number) + 'đ';
        }
        
        function recalculateTotal() {
            let total = 0;
            let selectedCount = 0;
            
            checkboxes.forEach(cb => {
                const id = cb.dataset.id;
                const price = parseFloat(cb.dataset.price);
                const qtyInput = document.querySelector(`.cart-item-qty[data-id="${id}"]`);
                const qty = parseInt(qtyInput.value) || 1;
                
                // Cập nhật Subtotal hiển thị cho từng dòng
                const subtotal = price * qty;
                const subtotalEl = document.getElementById(`subtotal-${id}`);
                if (subtotalEl) {
                    subtotalEl.textContent = 'Tổng: ' + formatCurrency(subtotal);
                }
                
                if (cb.checked) {
                    total += subtotal;
                    selectedCount += qty;
                }
            });
            
            grandTotalEl.textContent = formatCurrency(total);
            selectedCountEl.textContent = selectedCount;
        }
        
        checkboxes.forEach(cb => {
            cb.addEventListener('change', recalculateTotal);
        });
        
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (parseInt(this.value) < 1) this.value = 1;
                recalculateTotal();
            });
            input.addEventListener('input', function() {
                if (parseInt(this.value) < 1) this.value = 1;
                recalculateTotal();
            });
        });
        
        // Khởi động tính toán lần đầu
        recalculateTotal();
    });
    </script>

<?php else: ?>
    <div class="card p-5 mb-4 text-center text-muted">
        <div class="card-body">
            <i class="bi bi-cart-x fs-1 d-block mb-3 text-white-50"></i>
            <h5>Giỏ hàng của bạn hiện đang trống.</h5>
            <p class="small text-muted mb-4">Hãy chọn các sản phẩm bạn muốn và thêm vào giỏ hàng ngay nhé!</p>
            <a href="/webbanhang/Product" class="btn btn-primary">Mua sắm ngay</a>
        </div>
    </div>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>

