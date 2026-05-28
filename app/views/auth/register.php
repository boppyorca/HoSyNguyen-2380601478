<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center my-5">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4 fw-bold text-primary">Đăng Ký Tài Khoản</h3>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger border-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Vui lòng sửa các lỗi sau:
                        <ul class="mb-0 mt-2 ps-3">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/webbanhang/Auth/store">
                    <div class="mb-3">
                        <label for="username" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" required 
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" placeholder="Tên dùng để đăng nhập">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Tối thiểu 6 ký tự">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" placeholder="Họ và tên đầy đủ">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" placeholder="Ví dụ: name@example.com">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Đăng ký thành viên</button>
                </form>

                <div class="text-center mt-3">
                    <p class="mb-0 text-muted">Đã có tài khoản? <a href="/webbanhang/Auth/login">Đăng nhập</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
