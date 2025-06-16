<?php
session_start();

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Kiểm tra và lấy ID sản phẩm từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Đọc dữ liệu sản phẩm từ file JSON
$products = json_decode(file_get_contents('data/products.json'), true);

// Tìm sản phẩm theo ID
$product = null;
foreach ($products as $p) {
    if ($p['id'] == $id) {
        $product = $p;
        break;
    }
}

// Nếu không tìm thấy sản phẩm
if (!$product) {
    header('Location: index1.php?notfound=1');
    exit;
}

// Xử lý khi form được gửi đi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    // Lấy dữ liệu từ form
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $image = $_FILES['image']['name'];

    // Kiểm tra dữ liệu hợp lệ
    if (empty($name) || empty($price)) {
        $error = "Vui lòng nhập đủ thông tin!";
    } else {
        // Cập nhật thông tin sản phẩm
        $product['name'] = $name;
        $product['price'] = $price;
        
        // Cập nhật hình ảnh nếu có
        if (!empty($image)) {
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image);
            $product['image'] = $image;
        }

        // Lưu lại dữ liệu mới vào file JSON
        foreach ($products as $index => $p) {
            if ($p['id'] == $id) {
                $products[$index] = $product;
                break;
            }
        }

        file_put_contents('data/products.json', json_encode($products, JSON_PRETTY_PRINT));

        // Chuyển hướng về trang quản lý sản phẩm với thông báo thành công
        header('Location: index1.php?updated=1');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">Sửa sản phẩm</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="edit_product.php?id=<?= $id ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Giá sản phẩm</label>
            <input type="text" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Hình ảnh sản phẩm</label>
            <input type="file" class="form-control" id="image" name="image">
            <?php if (!empty($product['image'])): ?>
                <img src="uploads/<?= $product['image'] ?>" alt="Image" width="100" class="mt-2">
            <?php endif; ?>
        </div>
        <button type="submit" name="update_product" class="btn btn-success">Cập nhật</button>
        <a href="index1.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>

</body>
</html>
