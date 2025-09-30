<?php
// Start the session
session_start();
require_once 'models/UserModel.php';
$userModel = new UserModel();

<<<<<<< HEAD
// Basic security headers (tùy chỉnh theo môi trường)
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: no-referrer-when-downgrade");
// CSP cơ bản — bạn có thể siết chặt hơn nếu cần
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");

// Validate id từ GET (ép kiểu int)
$_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$user = null;

if ($_id !== null && $_id !== false) {
    $user = $userModel->findUserById($_id); // Update existing user (UserModel phải trả array hoặc null)
}

// Tạo CSRF token nếu chưa có
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Xử lý POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Validate CSRF
    $posted_csrf = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!hash_equals($_SESSION['csrf_token'], $posted_csrf)) {
        http_response_code(400);
        die("CSRF validation failed");
    }

    // Lấy và sanitize inputs (loại bỏ thẻ HTML)
    $payload = [];

    // id (nếu có)
    $payload['id'] = isset($_POST['id']) && $_POST['id'] !== '' ? intval($_POST['id']) : null;

    // name: strip tags + trim + giới hạn độ dài
    $rawName = isset($_POST['name']) ? $_POST['name'] : '';
    $safeName = strip_tags(trim($rawName));
    if (mb_strlen($safeName, 'UTF-8') > 255) {
        $safeName = mb_substr($safeName, 0, 255, 'UTF-8');
    }
    $payload['name'] = $safeName;

    // password: nếu có thì hash (nếu để trống thì bỏ qua khi update)
    $rawPassword = isset($_POST['password']) ? $_POST['password'] : '';
    if ($rawPassword !== '') {
        // Hash password với password_hash
        $payload['password'] = password_hash($rawPassword, PASSWORD_DEFAULT);
    } else {
        $payload['password'] = null; // nghĩa là không cập nhật password nếu update
    }

    // Gọi model — đảm bảo UserModel dùng prepared statements
    if (!empty($payload['id'])) {
        // Nếu update: tuỳ implement của bạn, updateUser nên xử lý password null nghĩa là không đổi mật khẩu
        $userModel->updateUser($payload);
    } else {
        // Insert: bắt buộc có password
        if (empty($payload['password'])) {
            // Trường hợp insert mà không có password, xử lý lỗi đơn giản (bạn có thể hiển thị lỗi chi tiết hơn)
            $_SESSION['form_error'] = "Password is required for new user.";
            // chuyển hướng lại hoặc tiếp tục hiển thị form
        } else {
            $userModel->insertUser($payload);
            // Sau insert xong, có thể unset token để tránh double submit
            unset($_SESSION['csrf_token']);
            header('Location: list_users.php');
            exit;
        }
    }

    // Sau update, redirect về danh sách
    unset($_SESSION['csrf_token']);
    header('Location: list_users.php');
    exit;
=======
$user = NULL; //Add new user
$_id = NULL;

if (!empty($_GET['id'])) {
    $_id = $_GET['id'];
    $user = $userModel->findUserById($_id);//Update existing user
}


if (!empty($_POST['submit'])) {

    if (!empty($_id)) {
        $userModel->updateUser($_POST);
    } else {
        $userModel->insertUser($_POST);
    }
    header('location: list_users.php');
>>>>>>> main
}

?>
<!DOCTYPE html>
<<<<<<< HEAD
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>User form</title>
    <?php include 'views/meta.php' ?>
</head>

<body>
    <?php include 'views/header.php'?>
    <div class="container">
        <?php if ($user || $_id === null) { ?>
        <div class="alert alert-warning" role="alert">
            User form
        </div>

        <?php
            // Lấy giá trị hiển thị: ưu tiên $user từ DB, nếu không có thì lấy value từ POST (đã escape)
            $displayName = '';
            if (!empty($user) && is_array($user) && isset($user[0]['name'])) {
                $displayName = $user[0]['name'];
            } else {
                $displayName = isset($_POST['name']) ? $_POST['name'] : '';
            }
            ?>

        <form method="POST" autocomplete="off" novalidate>
            <input type="hidden" name="id" value="<?php echo ($_id !== null && $_id !== false) ? (int)$_id : '' ?>">
            <!-- CSRF token -->
            <input type="hidden" name="csrf_token"
                value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">

            <div class="form-group">
                <label for="name">Name</label>
                <input id="name" class="form-control" name="name" placeholder="Name"
                    value="<?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control" placeholder="Password">
                <?php if (!empty($user)) { ?>
                <small class="form-text text-muted">Để trống nếu không muốn thay đổi mật khẩu.</small>
                <?php } ?>
            </div>

            <?php if (!empty($_SESSION['form_error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_SESSION['form_error'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['form_error']); ?>
            </div>
            <?php endif; ?>

            <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
        </form>
        <?php } else { ?>
        <div class="alert alert-success" role="alert">
            User not found!
        </div>
        <?php } ?>
    </div>
</body>

=======
<html>
<head>
    <title>User form</title>
    <?php include 'views/meta.php' ?>
</head>
<body>
    <?php include 'views/header.php'?>
    <div class="container">

            <?php if ($user || !isset($_id)) { ?>
                <div class="alert alert-warning" role="alert">
                    User form
                </div>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $_id ?>">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input class="form-control" name="name" placeholder="Name" value='<?php if (!empty($user[0]['name'])) echo $user[0]['name'] ?>'>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>

                    <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                </form>
            <?php } else { ?>
                <div class="alert alert-success" role="alert">
                    User not found!
                </div>
            <?php } ?>
    </div>
</body>
>>>>>>> main
</html>