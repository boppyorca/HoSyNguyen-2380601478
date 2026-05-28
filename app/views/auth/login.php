<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center my-5">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4 fw-bold text-primary">Đăng Nhập</h3>

                <?php 
                $success_msg = SessionHelper::flash('success');
                if ($success_msg): 
                ?>
                    <div class="alert alert-success border-0"><?php echo htmlspecialchars($success_msg); ?></div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger border-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/webbanhang/Auth/authenticate">
                    <div class="mb-3">
                        <label for="username" class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control" id="username" name="username" required placeholder="Nhập tên đăng nhập">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Nhập mật khẩu">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Đăng nhập</button>
                </form>

                <div class="text-center mt-3">
                    <p class="mb-0 text-muted">Chưa có tài khoản? <a href="/webbanhang/Auth/register">Đăng ký ngay</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
