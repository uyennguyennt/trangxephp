<?php
session_start();

// Chỉ cho phép quản trị truy cập
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Đọc dữ liệu sản phẩm từ file JSON
$products = json_decode(file_get_contents('data/products.json'), true);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">🔧 Quản lý sản phẩm</h2>

    <a href="add_product.php" class="btn btn-success mb-3">➕ Thêm sản phẩm</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Hình ảnh</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= number_format($product['price']) ?> VNĐ</td>
                    <td>
                        <?php if (!empty($product['image'])): ?>
                            <img src="uploads/<?= $product['image'] ?>" alt="" width="80">
                        <?php else: ?>
                            (Không có ảnh)
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-warning">✏️ Sửa</a>
                        <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">🗑️ Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center">Không có sản phẩm nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="logout.php" class="btn btn-secondary mt-3">Đăng xuất</a>
    <a href="index.php" class="btn btn-outline-primary mt-2 ms-2">⬅ Quay lại trang chính</a>
</div>

</body>
</html>
