<?php
session_start();
$usersFile = 'data/users.json';
$errors = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $errors = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

        // Kiểm tra trùng tên
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                $errors = "Tên đăng nhập đã tồn tại.";
                break;
            }
        }

        // Nếu không lỗi thì lưu và đăng nhập luôn
        if (!$errors) {
            $users[] = ['username' => $username, 'password' => $password, 'role' => 'customer'];
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'customer';
            header('Location: index.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
<div class="bg-white p-4 rounded shadow" style="width: 400px;">
    <h3 class="text-center mb-4">🔐 Đăng ký tài khoản</h3>

    <?php if ($errors): ?>
        <div class="alert alert-danger"><?= $errors ?></div>
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
        <button class="btn btn-primary w-100">Đăng ký</button>
    </form>

    <p class="mt-3 text-center">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
</div>
</body>
</html>
