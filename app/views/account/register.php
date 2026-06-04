<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center my-5">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <h3 class="card-title text-center mb-4 fw-bold text-primary">Register</h3>

                <?php
                if (isset($errors)) {
                    echo "<div class='alert alert-danger border-0'>";
                    echo "<ul class='mb-0'>";
                    foreach ($errors as $error) {
                        echo "<li>" . htmlspecialchars($error) . "</li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
                ?>

                <form class="user" action="/webbanhang/account/save" method="post">
                    <div class="row mb-3">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="username" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="fullname" class="form-label">Fullname</label>
                            <input type="text" class="form-control" id="fullname" name="fullName" placeholder="fullname" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="confirmpassword" required>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            Register
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <p class="mb-0 text-muted">Already have an account? <a href="/webbanhang/account/login">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
