<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
// chặn người dùng nếu không phải admin
requireAdmin();
// lấy danh sách sp
$products = getProducts();



include 'includes/header.php';
?>

<div class="admin-container">
    <h1>Quản lý sản phẩm</h1>

    
    <!-- Nút thêm sản phẩm -->
    <a href="add.php" class="btn-primary">➕ Thêm sản phẩm</a>

    <!-- Bảng sản phẩm -->
    <table class="product-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Giá</th>
                <th>Ảnh</th>
                <th>Người tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= formatCurrency($p['price']) ?></td>
                    <td><img src="<?= $p['image'] ?>" style="height: 50px;"></td>
                    <td><?= $p['created_by'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $p['id'] ?>">✏️ Sửa</a> |
                        <a href="delete.php?id=<?= $p['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">🗑️ Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
