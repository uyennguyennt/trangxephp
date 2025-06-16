<?php
// Start session
session_start();

// Include required files
require_once 'includes/db.php';

// Get search term and price filters from GET parameters
$term = isset($_GET['term']) ? trim($_GET['term']) : '';
$minPrice = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? (float)$_GET['min_price'] : null;
$maxPrice = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (float)$_GET['max_price'] : null;

// If search term is empty, redirect to home page
if (empty($term)) {
    header('Location: index.php');
    exit;
}

// Search for products
$products = searchProducts($term, $minPrice, $maxPrice);

// Get cart count from session
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['username']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm: <?= htmlspecialchars($term) ?> - AutoVN</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.replit.com/agent/bootstrap-agent-dark-theme.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">AutoVN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Trang chủ</a>
                    </li>
                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="add.php">Thêm sản phẩm</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <form class="d-flex me-2" action="search.php" method="GET">
                    <input class="form-control me-2" type="search" name="term" placeholder="Tìm kiếm xe" aria-label="Search" value="<?= htmlspecialchars($term) ?>">
                    <button class="btn btn-outline-light" type="submit">Tìm</button>
                </form>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="bi bi-cart"></i> Giỏ hàng
                            <?php if ($cartCount > 0): ?>
                            <span class="badge bg-primary"><?= $cartCount ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php if ($isLoggedIn): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= htmlspecialchars($_SESSION['username']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
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

    <!-- Main Content -->
    <div class="container my-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Bộ lọc tìm kiếm</h5>
                    </div>
                    <div class="card-body">
                        <form action="search.php" method="GET">
                            <input type="hidden" name="term" value="<?= htmlspecialchars($term) ?>">
                            
                            <div class="mb-3">
                                <label for="min_price" class="form-label">Giá tối thiểu</label>
                                <input type="number" class="form-control" id="min_price" name="min_price" value="<?= $minPrice ?? '' ?>" min="0">
                            </div>
                            
                            <div class="mb-3">
                                <label for="max_price" class="form-label">Giá tối đa</label>
                                <input type="number" class="form-control" id="max_price" name="max_price" value="<?= $maxPrice ?? '' ?>" min="0">
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Áp dụng</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <h1 class="mb-4">Kết quả tìm kiếm: "<?= htmlspecialchars($term) ?>"</h1>
                
                <?php if (empty($products)): ?>
                <div class="alert alert-info">
                    Không tìm thấy kết quả phù hợp. <a href="index.php" class="alert-link">Quay lại trang chủ</a>.
                </div>
                <?php else: ?>
                <p>Tìm thấy <?= count($products) ?> kết quả.</p>
                
                <div class="row">
                    <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text"><?= substr(htmlspecialchars($product['description']), 0, 100) ?>...</p>
                                <p class="card-text text-primary fw-bold"><?= formatCurrency($product['price']) ?></p>
                                <div class="mt-auto d-flex justify-content-between">
                                    <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-info">Chi tiết</a>
                                    <form action="add_to_cart.php" method="POST">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary">Thêm vào giỏ</button>
                                    </form>
                                </div>
                                <?php if ($isAdmin): ?>
                                <div class="mt-2 d-flex justify-content-between">
                                    <a href="edit.php?id=<?= $product['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                                    <a href="delete.php?id=<?= $product['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>AutoVN</h5>
                    <p>Cửa hàng xe hơi trực tuyến hàng đầu Việt Nam</p>
                </div>
                <div class="col-md-4">
                    <h5>Liên hệ</h5>
                    <address>
                        <p>Địa chỉ: 123 Đường ABC, Quận 1, TP.HCM</p>
                        <p>Điện thoại: (084) 123-4567</p>
                        <p>Email: info@autovn.com</p>
                    </address>
                </div>
                <div class="col-md-4">
                    <h5>Theo dõi chúng tôi</h5>
                    <div class="d-flex">
                        <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-2"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; 2023 AutoVN. Tất cả quyền được bảo lưu.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</body>
</html>