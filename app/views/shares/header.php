<?php require_once 'app/helpers/SessionHelper.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0a192f;
            --card-bg: #172a45;
            --text-primary: #e2e8f0;
            --text-muted: #8892b0;
            --accent-color: #f5a623;
            --accent-hover: #d97706;
            --input-bg: #1f3554;
            --border-color: #233554;
        }

        body { 
            background-color: var(--bg-color); 
            color: var(--text-primary); 
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; 
        }

        .navbar { 
            background: rgba(15, 32, 56, 0.85); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 30px rgba(0,0,0,0.3); 
        }
        .navbar-brand { font-weight: 700; font-size: 1.3rem; color: #fff !important; }
        .navbar-brand i { color: var(--accent-color); }
        .nav-link { color: rgba(255,255,255,.85) !important; font-weight: 500; padding: .45rem .9rem !important; border-radius: 7px; transition: all .2s; }
        .nav-link:hover { color: #fff !important; background: rgba(255,255,255,.1); }
        .nav-link i { margin-right: 4px; }

        .card { 
            background-color: var(--card-bg); 
            border: 1px solid var(--border-color); 
            border-radius: 14px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.25); 
            color: var(--text-primary);
            transition: transform .2s, box-shadow .2s, border-color .2s; 
        }
        .card:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 12px 30px rgba(0,0,0,0.4); 
            border-color: var(--accent-color);
        }

        .btn { border-radius: 8px; font-weight: 600; padding: .55rem 1.2rem; transition: all .2s; }
        .btn-primary { 
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover)); 
            border: none; 
            color: #0a192f !important;
        }
        .btn-primary:hover { 
            background: linear-gradient(135deg, var(--accent-hover), var(--accent-color)); 
            transform: translateY(-1px);
        }
        .btn-secondary {
            background-color: #233554;
            border: 1px solid #30476e;
            color: #e2e8f0;
        }
        .btn-secondary:hover {
            background-color: #30476e;
            color: #fff;
        }
        .btn-outline-primary {
            border-color: var(--accent-color);
            color: var(--accent-color);
        }
        .btn-outline-primary:hover {
            background-color: var(--accent-color);
            color: #0a192f !important;
            border-color: var(--accent-color);
        }
        .btn-success { background: linear-gradient(135deg, #28a745, #1e7e34); border: none; }
        .btn-success:hover { background: linear-gradient(135deg, #1e7e34, #28a745); }

        .form-control, .form-select { 
            background-color: var(--input-bg);
            border: 1.5px solid var(--border-color); 
            color: var(--text-primary);
            border-radius: 8px; 
            padding: .55rem .9rem; 
            transition: border-color .2s, box-shadow .2s; 
        }
        .form-control:focus, .form-select:focus { 
            background-color: #243b59;
            color: #fff;
            border-color: var(--accent-color); 
            box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.2); 
        }
        .form-label { font-weight: 600; color: #a8b2d1; font-size: .9rem; margin-bottom: 5px; }

        .table-wrapper { 
            background-color: var(--card-bg); 
            border: 1px solid var(--border-color); 
            border-radius: 14px; 
            overflow: hidden; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.25); 
        }
        .table { margin: 0; color: var(--text-primary); }
        .table thead th { background: #1b2f4c; color: #fff; border-bottom: 1px solid var(--border-color); font-weight: 600; padding: 14px 16px; }
        .table tbody td { padding: 13px 16px; vertical-align: middle; border-color: var(--border-color); }
        .table tbody tr:hover { background: #1e324c; }

        .price-tag { color: var(--accent-color); font-weight: 700; }
        .badge-cat { background: rgba(245, 166, 35, 0.15); color: var(--accent-color); font-weight: 600; border-radius: 20px; padding: 4px 12px; font-size: .78rem; }

        .product-img { width: 54px; height: 54px; object-fit: cover; border-radius: 10px; border: 2px solid var(--border-color); }
        .no-img { width: 54px; height: 54px; background: var(--input-bg); border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; color: #bbb; font-size: 1.4rem; }

        .upload-zone { 
            border: 2px dashed var(--accent-color); 
            border-radius: 12px; 
            padding: 28px 20px; 
            text-align: center; 
            cursor: pointer; 
            background: var(--input-bg); 
            transition: background .2s; 
        }
        .upload-zone:hover { background: rgba(245, 166, 35, 0.1); }
        .img-preview { max-width: 160px; max-height: 160px; border-radius: 10px; border: 2px solid var(--border-color); display: none; margin-top: 10px; }

        .page-title { font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 22px; display: flex; align-items: center; gap: 10px; }
        .page-title i { background: linear-gradient(135deg, var(--accent-color), var(--accent-hover)); color: #0a192f; padding: 8px 10px; border-radius: 10px; font-size: 1rem; }

        .product-card .card-img-top { height: 200px; object-fit: cover; border-radius: 14px 14px 0 0; }
        .product-card .card-img-placeholder { height: 200px; background: linear-gradient(135deg, #172a45, #1f3554); border-radius: 14px 14px 0 0; display: flex; align-items: center; justify-content: center; color: #adb5bd; font-size: 3rem; }
        .product-card .card-body { padding: 16px; background-color: var(--card-bg); }
        .product-card .card-title { font-weight: 700; font-size: 1rem; color: #fff; margin-bottom: 6px; }

        .text-muted { color: var(--text-muted) !important; }
        .alert-danger { background-color: rgba(220, 53, 69, 0.2); border: 1px solid #dc3545; color: #ea868f; }
        .alert-success { background-color: rgba(40, 167, 69, 0.2); border: 1px solid #28a745; color: #75b798; }
        .list-group-item { background-color: var(--card-bg); border-color: var(--border-color); color: var(--text-primary); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?php echo SITE_URL; ?>">
                <i class="bi bi-shop-window"></i> Shop Manager
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <i class="bi bi-list text-white fs-4"></i>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto gap-1">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>Product/"><i class="bi bi-grid-3x3-gap"></i> Sản phẩm</a>
                    </li>
                    <?php if (SessionHelper::isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>Product/add"><i class="bi bi-plus-circle"></i> Thêm sản phẩm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>Category"><i class="bi bi-tags"></i> Danh mục</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>Category/add"><i class="bi bi-folder-plus"></i> Thêm danh mục</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Order"><i class="bi bi-receipt"></i> Đơn hàng</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Product/cart">
                            <i class="bi bi-cart"></i> Giỏ hàng
                            <?php 
                            $cart_count = 0;
                            if (isset($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $item) {
                                    $cart_count += $item['quantity'];
                                }
                            }
                            if ($cart_count > 0): 
                            ?>
                                <span class="badge bg-danger rounded-pill"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php if (SessionHelper::isLoggedIn()): ?>
                        <li class="nav-item">
                            <span class="nav-link text-white-50"><i class="bi bi-person-circle text-warning"></i> <?php echo htmlspecialchars(SessionHelper::get('user_name')); ?></span>
                        </li>
                        <?php if (!SessionHelper::isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/webbanhang/Order/myOrders"><i class="bi bi-clock-history"></i> Đơn hàng của tôi</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Auth/logout"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Auth/login"><i class="bi bi-box-arrow-in-right"></i> Đăng nhập</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Auth/register"><i class="bi bi-person-plus"></i> Đăng ký</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-4">
