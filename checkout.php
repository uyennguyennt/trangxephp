<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

function h($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Hàm định dạng tiền tệ an toàn
function formatCurrency($num) {
    return number_format((float)$num, 0, ',', '.') . ' VNĐ';
}

if (!isset($_SESSION['username'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: register.php');
    exit;
}

if (empty($_SESSION['cart'])) {
    echo "<div class='alert alert-warning text-center mt-5'>🛒 Giỏ hàng của bạn đang trống!</div>";
    exit;
}

$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .checkout-container {
            max-width: 900px;
            margin: 40px auto;
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* đỏ bóng nhẹ*/
        }
        .table thead {
            background-color: #f1f1f1;
        }
        /* phần hiển thị tổng tiên*/
        .total-section {
            font-size: 1.2rem;
            font-weight: 600; /* chữ đậm*/
            text-align: right;
            margin-top: 20px;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-success {
            width: 100%;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container checkout-container">
    <h2 class="mb-4 text-center text-success">💳 Thanh toán đơn hàng</h2>

    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Màu sắc</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $item): 
                $price = isset($item['price']) ? (float)$item['price'] : 0;
                $qty = isset($item['quantity']) ? (int)$item['quantity'] : 1;
                $itemTotal = $price * $qty;
                $totalPrice += $itemTotal;
            ?>
            <tr>
                <td><?= h($item['name'] ?? 'Không rõ') ?></td>
                <td><?= h($item['color'] ?? '-') ?></td>
                <td><?= h($qty) ?></td>
                <td><?= formatCurrency($price) ?></td>
                <td><?= formatCurrency($itemTotal) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-section">
        Tổng cộng: <?= formatCurrency($totalPrice) ?>
    </div>

    <form method="POST" action="process_payment.php" class="mt-4">
        <div class="mb-3">
            <label for="address" class="form-label">📦 Địa chỉ nhận hàng:</label>
            <input type="text" class="form-control" id="address" name="address" required placeholder="Ví dụ: 123 Lê Lợi, Quận 1, TP.HCM">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">📞 Số điện thoại:</label>
            <input type="text" class="form-control" id="phone" name="phone" required placeholder="Ví dụ: 0901234567">
        </div>
        <button type="submit" class="btn btn-success">✅ Xác nhận thanh toán</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
