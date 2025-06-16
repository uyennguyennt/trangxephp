<!-- includes/header.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>VN Auto - Website Bán Xe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f8f8;
        }
        header, footer {
            background-color: #333;
            color: white;
            padding: 10px 20px;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin-right: 10px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        main {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
        }
        .product-detail-container {
    display: flex;
    flex-direction: column;
    gap: 30px;
    padding: 20px;
}

.product-detail {
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    align-items: flex-start;
}

.product-gallery {
    flex: 1 1 400px;
}

.product-gallery .main-image img {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.thumbnail-images {
    margin-top: 10px;
    display: flex;
    gap: 10px;
}

.thumbnail-images img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border: 2px solid transparent;
    border-radius: 6px;
    cursor: pointer;
    transition: border 0.3s;
}

.thumbnail-images img:hover,
.thumbnail-images img.active {
    border-color: #2196f3;
}

.product-info {
    flex: 1 1 400px;
}

.product-title {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 10px;
}

.product-price {
    font-size: 24px;
    font-weight: bold;
    color: #e53935;
    margin-bottom: 20px;
}

.product-description h3,
.product-features h3 {
    font-size: 18px;
    margin-bottom: 5px;
}

.product-description p,
.product-features p {
    font-size: 15px;
    color: #444;
}

.product-actions {
    margin-top: 25px;
}

.product-actions form {
    margin-bottom: 10px;
}

.product-actions .btn-primary,
.product-actions .btn-secondary {
    background-color: #2196f3;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    margin-right: 10px;
}

.product-actions .btn-secondary {
    background-color: #f57c00;
}

.product-actions .btn-primary:hover {
    background-color: #1976d2;
}

.product-actions .btn-secondary:hover {
    background-color: #e65100;
}
.cart-btn {
    padding: 12px 24px;
    border: 2px solid #198754;
    color: #198754;
    background-color: white;
    font-weight: 600;
    border-radius: 12px;
    transition: 0.3s ease;
    min-width: 200px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
}

.cart-btn:hover {
    background-color: #198754;
    color: white;
    text-decoration: none;
}

.cart-btn.secondary {
    border-color: #6c757d;
    color: #6c757d;
}
.cart-btn.secondary:hover {
    background-color: #6c757d;
    color: white;
}

.cart-btn.primary {
    border-color: #0d6efd;
    color: #0d6efd;
}
.cart-btn.primary:hover {
    background-color: #0d6efd;
    color: white;
}

    </style>
</head>
<body>
<header>
    <h1>VN Auto</h1>
    <nav>
        <a href="index.php">Trang chủ</a>
        <a href="cart.php">Giỏ hàng</a>
        <a href="login.php">Đăng nhập</a>
        <a href="register.php">Đăng ký</a>
    </nav>
</header>
<main>
