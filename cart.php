<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: register.php');
    exit;
}

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý cập nhật số lượng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $index => $qty) {
            $qty = max(0, (int)$qty); // Đảm bảo không âm
            if ($qty > 0 && isset($_SESSION['cart'][$index])) {
                $_SESSION['cart'][$index]['quantity'] = $qty;
            } elseif (isset($_SESSION['cart'][$index])) {
                unset($_SESSION['cart'][$index]);
            }
        }
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        header('Location: cart.php');
        exit;
    }
}

// Xử lý xóa sản phẩm
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $index = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    header('Location: cart.php');
    exit;
}

// Tính tổng
$totalAmount = 0;
foreach ($_SESSION['cart'] as $item) {
    $itemPrice = isset($item['price']) ? (float)$item['price'] : 0;
    $itemQty = isset($item['quantity']) ? (int)$item['quantity'] : 1;
    $totalAmount += $itemPrice * $itemQty;
}
?>

<!-- Thêm CSS vào đây -->
 <style>
/* Đặt font chữ cho container */
.container {
    font-family: 'Arial', sans-serif;
}

/* tiêu đề Giỏ hàng và thay đổi cỡ chữ */
h2.mb-4.text-center {
    color: #ff0000; 
    font-size: 50px;
}

/* Thiết lập kiểu cho card */
.card {
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 30px;
}

/* Padding : tạo khoảng cách cho card body */
.card-body {
    padding: 20px; 
}

/*bảng: Căn giữa và padding cho các ô trong bảng */
.table th, .table td {
    vertical-align: middle;
    padding: 12px;
    text-align: center;
}

/* Thiết lập màu nền cho header của bảng */
.table thead {
    background-color: #f8f9fa;
    font-weight: bold;
    color: #333;
}

/* Màu nền cho tiêu đề các cột Sản phẩm, Giá, Số lượng, Thành tiền */
.table th {
    font-size: 16px;
    color: #ffffff; /* Màu chữ trắng */
    background-color: rgb(247, 207, 216); /* Màu nền đỏ */
    text-transform: uppercase;
}

/* Các ô trong bảng */
.table tbody {
    border-bottom: 1px solid #ddd;
}

.table td {
    padding: 15px;
}

/* Màu nền cho các ô trong bảng */
.table td.text-start {
    background-color: #f0f8ff; /* Màu nền nhẹ cho sản phẩm */
}

.table td.text-nowrap {
    background-color: #fff7e6; /* Màu nền nhẹ cho giá */
}

.table input[type="number"] {
    background-color: #e8f8e8; /* Màu nền cho ô nhập số lượng */
}

.table td.text-nowrap {
    background-color: #e2f9e2; /* Màu nền nhẹ cho thành tiền */
}

/* Màu chữ cho các cột */
.table th, .table td {
    color: #495057; /* Màu chữ tối cho tất cả các ô */
}

/* Màu chữ riêng cho cột "Giá" */
.table td.text-nowrap {
    color: #dc3545; /* Màu chữ đỏ cho cột giá */
}

/* Màu chữ riêng cho cột "Số lượng" */
.table input[type="number"] {
    color: #17a2b8; /* Màu chữ xanh cho số lượng */
}

/* Màu chữ riêng cho cột "Thành tiền" */
.table td.text-nowrap {
    color: #28a745; /* Màu chữ xanh lá cho thành tiền */
}

/* Màu chữ và nền cho nút "Xóa" */
.table .remove-btn {
    background-color: red; /* Màu nền đỏ */
    color: white; /* Màu chữ trắng */
    padding: 5px 10px;
    border-radius: 20px;
    text-decoration: none;
}

.table .remove-btn:hover {
    background-color: #ff4b4b; /* Màu nền đỏ đậm khi hover */
}

/* Màu sắc cho các nút  điều hướng "Cập nhật giỏ hàng" và "Tiến hành thanh toán" */
.cart-btn.primary {
    background-color: #ff7a00; /* Màu nền cam */
    color: white; /* Màu chữ trắng */
    font-weight: bold; /*chữ in đậm*/
    padding: 12px 24px; /*khoảng cách bên trong nút*/
    border-radius: 50px;
    text-decoration: none; /* bỏ gạch chân nếu đây là thẻ <a></a>*/
    text-align: center;
    display: inline-block;
}

.cart-btn.primary:hover {
    background-color: #ff5722; /* Màu nền cam đậm khi hover */
}

.cart-btn.secondary {
    background-color: #f1f1f1;
    color: #333;
    border: 1px solid #ddd;
    font-weight: bold;
    padding: 12px 24px;
    border-radius: 50px;
    text-decoration: none;
}

.cart-btn.secondary:hover {
    background-color: #d1d1d1; /* Màu nền xám khi hover */
}

/* Các nút ở dưới cùng hiển thị luôn màu sẵn */
.cart-btn {
    font-weight: bold;
    border-radius: 50px;
    padding: 12px 24px;
    font-size: 16px;
    text-align: center;
    display: inline-block;
}

.cart-btn:hover {
    opacity: 0.9;
}
</style>
<div class="container my-5">
    <div class="card shadow-lg border-0">
        <div class="card-body">
            <h2 class="mb-4 text-center"><i class="bi bi-cart4"></i> Giỏ hàng của bạn</h2>

            <?php if (empty($_SESSION['cart'])): ?>
                <div class="alert alert-info text-center">
                    🛒 Giỏ hàng đang trống. <a href="index.php" class="text-decoration-none fw-bold">Tiếp tục mua sắm</a>
                </div>
            <?php else: ?>
                <form method="POST" action="cart.php">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                    <th>Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                                    <tr>
                                        <td class="text-start">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="<?= isset($item['image']) ? htmlspecialchars($item['image']) : 'default.jpg' ?>" alt="" class="rounded" style="width: 70px;">
                                                <div>
                                                    <strong><?= isset($item['name']) ? htmlspecialchars($item['name']) : 'Không tên' ?></strong><br>
                                                    <?php if (!empty($item['color'])): ?>
                                                        <small class="text-muted">Màu: <span class="badge bg-info text-dark"><?= htmlspecialchars($item['color']) ?></span></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-nowrap"><?= formatCurrency(isset($item['price']) ? $item['price'] : 0) ?></td>
                                        <td>
                                            <input type="number" name="quantity[<?= $index ?>]" value="<?= isset($item['quantity']) ? $item['quantity'] : 1 ?>" min="1" class="form-control text-center" style="width: 80px; margin: auto;">
                                        </td>
                                        <td class="text-nowrap"><?= formatCurrency((isset($item['price']) ? $item['price'] : 0) * (isset($item['quantity']) ? $item['quantity'] : 1)) ?></td>
                                        <td>
                                            <a href="cart.php?remove=<?= $index ?>" class="remove-btn" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                                <i class="bi bi-trash3"></i> Xóa
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-light fw-bold">
                                    <td colspan="3" class="text-end">Tổng cộng:</td>
                                    <td colspan="2" class="text-start"><?= formatCurrency($totalAmount) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                        <a href="index.php" class="cart-btn secondary">
                            ← Tiếp tục mua sắm
                        </a>
                        <button type="submit" name="update_cart" class="cart-btn primary">
                            <i class="bi bi-arrow-repeat"></i> Cập nhật giỏ hàng
                        </button>
                        <a href="checkout.php" class="cart-btn">
                            <i class="bi bi-credit-card-fill"></i> Tiến hành thanh toán
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
