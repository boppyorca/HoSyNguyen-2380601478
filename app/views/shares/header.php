<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; }

        .navbar { background: linear-gradient(135deg, #1e3a5f 0%, #2d6a9f 100%); box-shadow: 0 2px 12px rgba(0,0,0,.2); }
        .navbar-brand { font-weight: 700; font-size: 1.3rem; color: #fff !important; }
        .navbar-brand i { color: #f0c040; }
        .nav-link { color: rgba(255,255,255,.8) !important; font-weight: 500; padding: .45rem .9rem !important; border-radius: 7px; transition: all .2s; }
        .nav-link:hover { color: #fff !important; background: rgba(255,255,255,.15); }
        .nav-link i { margin-right: 4px; }

        .card { border: none; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.07); transition: transform .2s, box-shadow .2s; }
        .card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }

        .btn { border-radius: 8px; font-weight: 500; }
        .btn-primary { background: linear-gradient(135deg, #2d6a9f, #1e3a5f); border: none; }
        .btn-primary:hover { background: linear-gradient(135deg, #1e3a5f, #2d6a9f); }
        .btn-success { background: linear-gradient(135deg, #28a745, #1e7e34); border: none; }
        .btn-success:hover { background: linear-gradient(135deg, #1e7e34, #28a745); }

        .form-control, .form-select { border-radius: 8px; border: 1.5px solid #d0d7e0; padding: .55rem .9rem; transition: border-color .2s, box-shadow .2s; }
        .form-control:focus, .form-select:focus { border-color: #2d6a9f; box-shadow: 0 0 0 3px rgba(45,106,159,.15); }
        .form-label { font-weight: 600; color: #3a3a4a; font-size: .9rem; margin-bottom: 5px; }

        .table-wrapper { background: #fff; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
        .table { margin: 0; }
        .table thead th { background: #1e3a5f; color: #fff; border: none; font-weight: 600; padding: 14px 16px; }
        .table tbody td { padding: 13px 16px; vertical-align: middle; border-color: #f0f2f5; }
        .table tbody tr:hover { background: #f7f9fc; }

        .price-tag { color: #e63946; font-weight: 700; }
        .badge-cat { background: #e8f0fe; color: #1e3a5f; font-weight: 600; border-radius: 20px; padding: 4px 12px; font-size: .78rem; }

        .product-img { width: 54px; height: 54px; object-fit: cover; border-radius: 10px; border: 2px solid #e8eaf0; }
        .no-img { width: 54px; height: 54px; background: #f0f2f5; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; color: #bbb; font-size: 1.4rem; }

        .upload-zone { border: 2px dashed #2d6a9f; border-radius: 12px; padding: 28px 20px; text-align: center; cursor: pointer; background: #f7f9fc; transition: background .2s; }
        .upload-zone:hover { background: #e8f0fe; }
        .img-preview { max-width: 160px; max-height: 160px; border-radius: 10px; border: 2px solid #e0e0e0; display: none; margin-top: 10px; }

        .page-title { font-size: 1.5rem; font-weight: 700; color: #1e3a5f; margin-bottom: 22px; display: flex; align-items: center; gap: 10px; }
        .page-title i { background: linear-gradient(135deg, #2d6a9f, #1e3a5f); color: #fff; padding: 8px 10px; border-radius: 10px; font-size: 1rem; }

        .product-card .card-img-top { height: 200px; object-fit: cover; border-radius: 14px 14px 0 0; }
        .product-card .card-img-placeholder { height: 200px; background: linear-gradient(135deg, #e8f0fe, #f0f2f5); border-radius: 14px 14px 0 0; display: flex; align-items: center; justify-content: center; color: #adb5bd; font-size: 3rem; }
        .product-card .card-body { padding: 16px; }
        .product-card .card-title { font-weight: 700; font-size: 1rem; color: #1e3a5f; margin-bottom: 6px; }
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
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>Product/add"><i class="bi bi-plus-circle"></i> Thêm sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>Category"><i class="bi bi-tags"></i> Danh mục</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>Category/add"><i class="bi bi-folder-plus"></i> Thêm danh mục</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-4">
