<?php
// Hàm đọc dữ liệu sản phẩm từ file JSON
function getProducts() {
    $dataFile = "data/products.json";
    if (!file_exists($dataFile)) {
        return [];
    }

    $jsonData = file_get_contents($dataFile);
    $products = json_decode($jsonData, true);

    // Kiểm tra nếu dữ liệu không hợp lệ
    if (!is_array($products)) {
        return [];
    }

    return $products;
}

// Hàm lưu dữ liệu sản phẩm vào file JSON
function saveProducts($products) {
    $dataFile = "data/products.json";
    if (file_put_contents($dataFile, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) {
        return false;
    }
    return true;
}

// Hàm lấy sản phẩm theo ID
function getProductById($id) {
    $products = getProducts();

    foreach ($products as $product) {
        if ($product['id'] == $id) {
            return $product;
        }
    }

    return null;
}

// Hàm tìm kiếm sản phẩm
function searchProducts($searchTerm, $minPrice = null, $maxPrice = null) {
    $products = getProducts();
    $results = [];

    foreach ($products as $product) {
        $matchName = empty($searchTerm) || stripos($product['name'], $searchTerm) !== false || 
                     stripos($product['description'], $searchTerm) !== false;

        $matchPrice = (empty($minPrice) || $product['price'] >= $minPrice) && 
                      (empty($maxPrice) || $product['price'] <= $maxPrice);

        if ($matchName && $matchPrice) {
            $results[] = $product;
        }
    }

    return $results;
}

// Hàm thêm sản phẩm mới
function addProduct($product) {
    $products = getProducts();

    // Tạo ID mới nếu chưa có
    if (!isset($product['id'])) {
        $product['id'] = count($products) > 0 ? max(array_column($products, 'id')) + 1 : 1;
    }

    $products[] = $product;
    return saveProducts($products);
}

// Hàm cập nhật sản phẩm
function updateProduct($id, $updatedProduct) {
    $products = getProducts();

    foreach ($products as $key => $product) {
        if ($product['id'] == $id) {
            // Giữ nguyên ID
            $updatedProduct['id'] = $id;
            $products[$key] = $updatedProduct;
            return saveProducts($products);
        }
    }

    return false;
}

// Hàm xóa sản phẩm
function deleteProduct($id) {
    $products = getProducts();

    foreach ($products as $key => $product) {
        if ($product['id'] == $id) {
            unset($products[$key]);
            $products = array_values($products); // Sắp xếp lại mảng
            return saveProducts($products);
        }
    }

    return false;
}

// Hàm lấy và xác thực người dùng
function getUsers() {
    $usersFile = "data/users.json";
    if (!file_exists($usersFile)) {
        // Tạo file người dùng mặc định nếu chưa tồn tại
        $defaultUsers = [
            [
                "username" => "admin",
                "password" => password_hash("admin123", PASSWORD_DEFAULT),
                "role" => "admin"
            ],
            [
                "username" => "user",
                "password" => password_hash("user123", PASSWORD_DEFAULT),
                "role" => "customer"
            ]
        ];
        file_put_contents($usersFile, json_encode($defaultUsers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $defaultUsers;
    }

    $jsonData = file_get_contents($usersFile);
    $users = json_decode($jsonData, true);

    // Kiểm tra dữ liệu JSON
    if (!is_array($users)) {
        return [];
    }

    return $users;
}

// Hàm lưu người dùng
function saveUsers($users) {
    $usersFile = "data/users.json";
    if (file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) {
        return false;
    }
    return true;
}

// Hàm kiểm tra người dùng tồn tại
function getUserByUsername($username) {
    $users = getUsers();

    foreach ($users as $user) {
        if ($user['username'] === $username) {
            return $user;
        }
    }

    return null;
}

// Hàm thêm người dùng mới
function addUser($username, $password, $role = 'customer') {
    $users = getUsers();

    // Kiểm tra username đã tồn tại chưa
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            return false;
        }
    }

    // Thêm người dùng mới
    $newUser = [
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $role
    ];

    $users[] = $newUser;
    return saveUsers($users);
}

// Định dạng tiền tệ Việt Nam
if (!function_exists('formatCurrency')) {
    function formatCurrency($amount) {
        return number_format($amount, 0, ',', '.') . ' ₫';
    }
}
?>
