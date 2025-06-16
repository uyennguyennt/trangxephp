<?php
session_start();

// Lấy dữ liệu sản phẩm
$id = $_GET['id'] ?? '';
$products = json_decode(file_get_contents('data/products.json'), true);
$product = null;

foreach ($products as $p) {
    if ($p['id'] == $id) {
        $product = $p;
        break;
    }
}

if (!$product) {
    echo "<h2>Sản phẩm không tồn tại.</h2>";
    exit;
}

// Xử lý thêm vào giỏ hoặc mua ngay
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $color = $_POST['color'];
    $quantity = max(1, intval($_POST['quantity']));
    $action = $_POST['action'];

    $item = [
        'id' => $product_id,
        'name' => $name,
        'price' => $price,
        'image' => $image,
        'color' => $color,
        'quantity' => $quantity
    ];

    // Thêm vào session cart
    $_SESSION['cart'][] = $item;

    if ($action === 'buy_now') {
        header('Location: checkout.php');
    } else {
        header('Location: cart.php');
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-image {
            width: 100%;
            max-width: 500px;
            height: auto;
            border-radius: 8px;
        }

        .thumbnail-img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            margin-right: 8px;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 6px;
            transition: border-color 0.2s;
        }

        .thumbnail-img:hover {
            border-color: #007bff;
        }

        .price-tag {
            font-size: 1.8rem;
            color: #dc3545;
            font-weight: bold;
        }

        .btn-buy-now {
            background-color: #fd7e14;
            color: white;
        }

        .btn-buy-now:hover {
            background-color: #e66b00;
        }

        .btn-back-home {
            background-color: #6c757d;
            color: white;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn-back-home:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container my-5">
    <div class="d-flex flex-column flex-md-row align-items-center gap-4">
    <!-- Ảnh sản phẩm nằm bên trái -->
    <div class="flex-fill text-center order-md-1 order-2">
        <img id="main-image" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image shadow-sm">
    </div>

    <!-- Nội dung nằm bên phải -->
    <div class="flex-fill order-md-2 order-1">
        <h2 class="mb-3"><?= htmlspecialchars($product['name']) ?></h2>
        <div class="mb-3">
            <span class="price-tag"><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</span>
        </div>

        <p><?= nl2br(htmlspecialchars($product['description'] ?? 'Không có mô tả.')) ?></p>

        <?php if (!empty($product['extra_images'])): ?>
            <div class="mb-3">
                <h5>Hình ảnh phụ:</h5>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($product['extra_images'] as $extra_image): ?>
                        <img src="<?= htmlspecialchars($extra_image) ?>" alt="Ảnh phụ" class="thumbnail-img" onclick="changeMainImage('<?= htmlspecialchars($extra_image) ?>')">
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="mt-4">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <input type="hidden" name="name" value="<?= $product['name'] ?>">
            <input type="hidden" name="price" value="<?= $product['price'] ?>">
            <input type="hidden" name="image" value="<?= $product['image'] ?>">

            <?php if (!empty($product['colors'])): ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Màu sắc:</label>
                    <select name="color" class="form-select" style="max-width: 250px;" required>
                        <?php foreach ($product['colors'] as $color): ?>
                            <option value="<?= trim($color) ?>"><?= trim($color) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else: ?>
                <input type="hidden" name="color" value="">
            <?php endif; ?>

            <div class="mb-4">
                <label class="form-label fw-semibold">Số lượng:</label>
                <input type="number" name="quantity" value="1" min="1" class="form-control" style="max-width: 120px;" required>
            </div>

            <div class="d-flex gap-3 align-items-center flex-wrap">
                <a href="index.php" class="btn-back-home">← Quay lại trang chủ</a>
                <button type="submit" name="action" value="add_to_cart" class="btn btn-outline-primary px-4">🛒 Thêm vào giỏ hàng</button>
                <button type="submit" name="action" value="buy_now" class="btn btn-buy-now px-4">💰 Mua ngay</button>
            </div>
        </form>
    </div>
</div>


           
    <script>
        function changeMainImage(src) {
            document.getElementById('main-image').src = src;
        }
    </script>
</body>
</html>
