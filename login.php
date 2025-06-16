<?php
session_start();
$usersFile = 'data/users.json';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            // Lưu session
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];

            // Chuyển hướng dựa vào role
            if ($user['role'] === 'admin') {
                header('Location: index1.php'); // Trang quản trị
            } else {
                header('Location: index.php');  // Trang bán hàng
            }
            exit;
        }
    }

    $error = 'Sai tên đăng nhập hoặc mật khẩu.';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
<div class="bg-white p-4 rounded shadow" style="width: 400px;">
    <h3 class="text-center mb-4">🔐 Đăng nhập</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Tên đăng nhập</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-success w-100">Đăng nhập</button>
    </form>

    <p class="mt-3 text-center">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
</div>
</body>
</html>
