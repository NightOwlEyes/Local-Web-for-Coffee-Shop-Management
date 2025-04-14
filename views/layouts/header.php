<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding-top: 56px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: url('<?= BASE_URL ?>/uploads/wallpaper/wallpaper.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .content {
            flex: 1;
        }
        
        .menu-item {
            height: 100%;
            transition: transform 0.3s;
        }
        
        .menu-item:hover {
            transform: translateY(-5px);
        }
        
        .menu-image {
            height: 200px;
            object-fit: cover;
        }
        
        .staff-card {
            height: 100%;
            transition: transform 0.3s;
        }
        
        .staff-card:hover {
            transform: translateY(-5px);
        }
        
        .staff-image {
            height: 200px;
            object-fit: cover;
        }
        
        .cart-container {
            position: sticky;
            top: 70px;
        }
        
        /* Định nghĩa màu nâu cho các nút */
        .btn-primary {
            background-color: #8B4513;
            border-color: #8B4513;
        }
        .btn-primary:hover {
            background-color: #654321;
            border-color: #654321;
        }
        
        .btn-success {
            background-color: #A0522D;
            border-color: #A0522D;
        }
        .btn-success:hover {
            background-color: #8B4513;
            border-color: #8B4513;
        }
        
        .btn-danger {
            background-color: #D2691E;
            border-color: #D2691E;
        }
        .btn-danger:hover {
            background-color: #A0522D;
            border-color: #A0522D;
        }
        
        .btn-info {
            background-color: #DEB887;
            border-color: #DEB887;
            color: #fff;
        }
        .btn-info:hover {
            background-color: #D2B48C;
            border-color: #D2B48C;
            color: #fff;
        }
        
        .btn-secondary {
            background-color: #6B4423;
            border-color: #6B4423;
        }
        .btn-secondary:hover {
            background-color: #543211;
            border-color: #543211;
        }
        
        .btn-outline-primary {
            color: #8B4513;
            border-color: #8B4513;
        }
        .btn-outline-primary:hover {
            background-color: #8B4513;
            border-color: #8B4513;
        }
        
        .btn-outline-info {
            color: #DEB887;
            border-color: #DEB887;
        }
        .btn-outline-info:hover {
            background-color: #DEB887;
            border-color: #DEB887;
        }
        
        /* Màu nền cho navbar */
        .navbar-dark {
            background-color: #8B4513 !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>/"><?= APP_NAME ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/quanly">Quản lý</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/nhanvien">Nhân viên</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/danhsach">Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/doanhthu">Doanh thu</a>
                        </li>
                    <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'staff'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/danhsach">Menu</a>
                        </li>
                        <?php if (!str_contains($_SERVER['REQUEST_URI'], '/thanhtoan')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/thanhtoan">
                                <i class="fas fa-shopping-cart"></i> Giỏ hàng
                                <span id="cartCount" class="badge bg-danger ms-1">0</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="navbar-text me-3">
                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                            <span class="badge bg-danger">Admin</span>
                        <?php else: ?>
                            <span class="badge bg-primary">Nhân viên</span>
                        <?php endif; ?>
                        <?= $_SESSION['username'] ?>
                    </div>
                    <a href="<?= BASE_URL ?>/logout" class="btn btn-outline-light btn-sm">Đăng xuất</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <div class="content container py-4">

