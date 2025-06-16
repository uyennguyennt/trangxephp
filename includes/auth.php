<?php
// Hàm xác thực đăng nhập
function authenticateUser($username, $password) {
    // đọc dữ liệu từ data.json và chuyển  đổi thành mảng php bằng json_decode
    $users = json_decode(file_get_contents('data/users.json'), true);

    foreach ($users as $user) {
        // So sánh username và kiểm tra mật khẩu (đã mã hóa)
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            // Đăng nhập thành công => Lưu thông tin vào session
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'] ?? 'user'; // Nếu không có role thì gán mặc định là 'user'
            return true;
        }
    }

    return false;
}

// Hàm yêu cầu quyền admin nếu không phải admin vừa sẻ chuển hướng đến trang đăng nhập
function requireAdmin() {
    if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: login.php'); // Chuyển hướng nếu không phải admin
        exit;
    }
}

// Hàm đăng ký người dùng mới
function registerUser($username, $hashedPassword) {
    $usersFile = 'data/users.json';

    if (!file_exists($usersFile)) {
        file_put_contents($usersFile, json_encode([]));
    }

    $data = json_decode(file_get_contents($usersFile), true);

    foreach ($data as $user) {
        if ($user['username'] === $username) {
            return false; // Trùng tên
        }
    }

    $data[] = [
        'username' => $username,
        'password' => $hashedPassword,
        'role' => 'user' // Gán mặc định là user
    ];

    file_put_contents($usersFile, json_encode($data, JSON_PRETTY_PRINT));
    return true;
}
?>
