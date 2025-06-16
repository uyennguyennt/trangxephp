<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Đọc dữ liệu từ file JSON
$products = json_decode(file_get_contents('data/products.json'), true);


// Kiểm tra xem dữ liệu đã được đọc thành công hay chưa
if ($products === null) {
    echo "Không thể đọc dữ liệu sản phẩm!";
    exit;
}


// Đếm tổng số sản phẩm trong giỏ hàng
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}

$isLoggedIn = isset($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>AutoVN - Cửa hàng xe hơi trực tuyến</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar: thanh điều hướng -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">🚗 AutoVN</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                        <i class="bi bi-cart-fill"></i> Giỏ hàng
                        <?php if ($cartCount > 0): ?>
                            <span class="badge bg-primary"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
            
            <form class="d-flex me-2" action="search.php" method="GET">
                <input class="form-control me-2" type="search" name="term" placeholder="Tìm xe...">
                <button class="btn btn-outline-light" type="submit">Tìm</button>
            </form>

            <ul class="navbar-nav">
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <li><a class="dropdown-item" href="index1.php">Quản lý</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Đăng nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Đăng ký</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Main -->
<div class="container my-5">
    <h1 class="text-center mb-4 fw-bold">Danh Sách Xe Ô Tô</h1>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card car-display h-100">
                    <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="text-muted"><?= substr(htmlspecialchars($product['description']), 0, 80) ?>...</p>
                        <p class="text-danger fw-bold"><?= number_format($product['price']) ?> VNĐ</p>
                        <div class="mt-auto d-flex justify-content-between">
                            <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-outline-info btn-sm">Chi tiết</a>
                            <form action="add_to_cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer class="bg-dark text-white text-center py-4 mt-5">
    <p class="mb-0">&copy; 2025 AutoVN. Tất cả quyền được bảo lưu.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
