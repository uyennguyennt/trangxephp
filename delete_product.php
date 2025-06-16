<?php
session_start();

// Chỉ cho phép quản trị truy cập
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Kiểm tra và lấy ID sản phẩm từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header('Location: index1.php');  // Nếu không có ID hợp lệ, quay về trang quản lý sản phẩm
    exit;
}

// Đọc dữ liệu sản phẩm từ file JSON
$products = json_decode(file_get_contents('data/products.json'), true);

// Tìm kiếm sản phẩm theo ID
$productIndex = null;
foreach ($products as $index => $product) {
    if ($product['id'] == $id) {
        $productIndex = $index;
        break;
    }
}

// Nếu không tìm thấy sản phẩm
if ($productIndex === null) {
    header('Location: index1.php?notfound=1');  // Nếu không có sản phẩm, quay lại trang quản lý
    exit;
}

// Xóa sản phẩm khỏi mảng
unset($products[$productIndex]);

// Cập nhật lại dữ liệu vào file JSON
file_put_contents('data/products.json', json_encode(array_values($products), JSON_PRETTY_PRINT));

// Quay về trang quản lý sản phẩm với thông báo đã xóa
header('Location: index1.php?deleted=1');
exit;
?>
