<?php
session_start();

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
// gọi các file cần thiết
require_once 'includes/db.php';
require_once 'includes/auth.php';

$successMsg = '';
$errorMsg = '';
// xử lí khi form được submit lên
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = isset($_POST['price']) ? (float)$_POST['price'] : 0;
    $image = trim($_POST['image'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $extraDescription = trim($_POST['extra_description'] ?? '');
    $colors = trim($_POST['colors'] ?? '');
    $extraImages = [];

    foreach (['extra_image_1', 'extra_image_2', 'extra_image_3'] as $field) {
        if (!empty($_POST[$field])) {
            $extraImages[] = trim($_POST[$field]);
        }
    }

    $errors = [];
    // ktra dữ liệu đầu vào
    if ($name === '') $errors[] = 'Vui lòng nhập tên sản phẩm.';
    if ($price <= 0) $errors[] = 'Giá sản phẩm phải lớn hơn 0.';
    if ($image === '') $errors[] = 'Vui lòng nhập đường dẫn hình ảnh.';
    if ($description === '') $errors[] = 'Vui lòng nhập mô tả sản phẩm.';
//tạo mảng sp mới
    if (empty($errors)) {
        $newProduct = [
            'name' => $name,
            'price' => $price,
            'image' => $image,
            'description' => $description,
            'extra_description' => $extraDescription,
            'colors' => $colors,
            'extra_images' => $extraImages,
            'created_by' => $_SESSION['username'] ?? 'admin'
        ];
// thêm sp vào hệ thống thành công=> index1
        if (addProduct($newProduct)) {
            $successMsg = '✅ Thêm sản phẩm thành công!';
            header("Location: index1.php");
            exit;
        } else {
            $errorMsg = '❌ Không thể lưu sản phẩm.';
        }
    } else {
        $errorMsg = implode('<br>', $errors);
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center fs-4 fw-bold">
                    ➕ Thêm Sản Phẩm Mới
                </div>
                <div class="card-body bg-light">
                    <?php if (!empty($successMsg)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($successMsg) ?>
                            <a href="index1.php" class="btn btn-primary btn-sm float-end">⬅ Quay lại quản lý</a>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php elseif (!empty($errorMsg)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $errorMsg ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" class="mt-3">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($name ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Giá (VNĐ)</label>
                            <input type="number" id="price" name="price" class="form-control" value="<?= htmlspecialchars($price ?? '') ?>" min="1000" step="1000" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Hình ảnh chính (URL)</label>
                            <input type="text" id="image" name="image" class="form-control" value="<?= htmlspecialchars($image ?? '') ?>" required>
                        </div>

                        <?php for ($i = 1; $i <= 3; $i++): ?>
                            <div class="mb-3">
                                <label for="extra_image_<?= $i ?>" class="form-label">Hình ảnh bổ sung <?= $i ?> (URL)</label>
                                <input type="text" id="extra_image_<?= $i ?>" name="extra_image_<?= $i ?>" class="form-control" value="<?= htmlspecialchars($extraImages[$i-1] ?? '') ?>">
                            </div>
                        <?php endfor; ?>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả sản phẩm</label>
                            <textarea id="description" name="description" rows="4" class="form-control" required><?= htmlspecialchars($description ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="extra_description" class="form-label">Mô tả chi tiết (tuỳ chọn)</label>
                            <textarea id="extra_description" name="extra_description" rows="4" class="form-control"><?= htmlspecialchars($extraDescription ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="colors" class="form-label">Màu sắc (ngăn cách bằng dấu phẩy: đỏ, đen,...)</label>
                            <input type="text" id="colors" name="colors" class="form-control" value="<?= htmlspecialchars($colors ?? '') ?>">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-success w-50 me-2 fw-bold">
                                ➕ Thêm sản phẩm
                            </button>
                            <a href="index1.php" class="btn btn-outline-danger w-50 ms-2 fw-bold">
                                ❌ Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
