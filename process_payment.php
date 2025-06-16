<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['cart'])) {
    header('Location: checkout.php');
    exit;
}

// Lấy thông tin từ form
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if ($address === '' || $phone === '') {
    echo "<div class='alert alert-danger text-center mt-5'>⚠️ Vui lòng điền đầy đủ thông tin giao hàng!</div>";
    exit;
}

// (Tùy chọn) Ở đây bạn có thể lưu đơn hàng vào file hoặc xử lý thêm nếu cần.

// Xóa giỏ hàng sau khi thanh toán
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán thành công</title>
    <meta http-equiv="refresh" content="4;url=index.php"> <!-- Tự động chuyển về trang chủ sau 4 giây -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .success-container {
            max-width: 600px;
            margin: 100px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-container h1 {
            color: #28a745;
            font-size: 2rem;
        }
        .success-container p {
            margin-top: 10px;
            font-size: 1.1rem;
        }
        .btn-home {
            margin-top: 20px;
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="success-container">
    <h1>🎉 Bạn đã thanh toán thành công!</h1>
    <p>Chúng tôi sẽ xử lý đơn hàng và giao đến bạn sớm nhất có thể.</p>
    <a href="index.php" class="btn btn-success btn-home">← Quay lại trang chủ</a>
</div>

</body>
</html>
