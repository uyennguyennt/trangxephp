<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu sản phẩm từ form
    $product = [
        'id' => $_POST['product_id'],
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'image' => $_POST['image'],
        'color' => $_POST['color'],
        'quantity' => $_POST['quantity']
    ];

    // Thêm sản phẩm vào giỏ hàng
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product['id'] && $item['color'] == $product['color']) {
            $item['quantity'] += $product['quantity'];
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = $product;
    }

    // Kiểm tra nếu người dùng nhấn "Mua ngay"
    if (isset($_POST['action']) && $_POST['action'] === 'buy_now') {
        // Chuyển hướng tới trang thanh toán
        header('Location: checkout.php');
        exit;
    }

    // Sau khi thêm vào giỏ hàng, quay lại trang sản phẩm
    header('Location: product.php?id=' . $product['id']);
    exit;
}
?>
