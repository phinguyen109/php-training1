<?php
<<<<<<< HEAD
=======
<<<<<<< HEAD
// Start the session
session_start();

require_once 'models/UserModel.php';
$userModel = new UserModel();

<<<<<<< HEAD

if (!empty($_POST['submit'])) {
    $users = [
        'username' => $_POST['username'],
        'password' => $_POST['password']
    ];
    $user = NULL;
    if ($user = $userModel->auth($users['username'], $users['password'])) {
        //Login successful
        $_SESSION['id'] = $user[0]['id'];

        $_SESSION['message'] = 'Login successful';
        header('location: list_users.php');
    }else {
        //Login failed
        $_SESSION['message'] = 'Login failed';
    }

}

?>
<!DOCTYPE html>
<html>
=======
// Kết nối Redis
$redis = new Redis();
$redis->connect('web-redis', 6379);

// Kiểm tra submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Auth
    $user = $userModel->auth($username, $password);

    if ($user && isset($user[0])) {
        $sessionId = bin2hex(random_bytes(32)); // tạo session id ngẫu nhiên

        // Lưu sessionId vào Redis kèm thông tin user
        $test = $redis->setex("session:$sessionId", 3600, json_encode([
=======
>>>>>>> main
session_start();
require_once 'models/UserModel.php';
$userModel = new UserModel();

<<<<<<< HEAD
// Redis connection
$redis = new Redis();
$redis->connect('web-redis', 6379);

// Security headers (tùy chỉnh tuỳ môi trường)
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: no-referrer-when-downgrade");
// CSP cơ bản: giới hạn script/style nguồn 'self' (tùy app mà siết chặt hơn)
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");

// Tạo CSRF token nếu chưa có
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ---------- Simple XSS detector ----------
function contains_xss($s) {
    if ($s === null || $s === '') return false;
    $s = (string)$s;

    $patterns = [
        '/<\s*script\b/i',
        '/<\s*iframe\b/i',
        '/<\s*img\b/i',
        '/<\s*svg\b/i',
        '/on\w+\s*=/i',           // onerror=, onclick=, onload=...
        '/javascript\s*:/i',
        '/data\s*:/i',
        '/<\s*\/?\s*\w+\s*[^>]*on\w+\s*=/i', // any tag with onXXX attribute
        '/<\s*style\b[^>]*>.*<\s*\/\s*style\s*>/is',
        '/<\s*base\b/i'
    ];

    foreach ($patterns as $pat) {
        if (preg_match($pat, $s)) return true;
    }

    // Giá trị chứa dấu < hoặc > nghi ngờ -> treat as XSS for testing
    if (preg_match('/[<>]/', $s)) {
        return true;
    }

    return false;
}
// ---------- End detector ----------

// Xử lý POST submit
=======
$redis = new Redis();
$redis->connect('web-redis', 6379);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    var_dump($_SESSION['csrf_token']);
}

// Xử lý submit
>>>>>>> main
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['username'], $_POST['password'], $_POST['csrf_token'])
) {

<<<<<<< HEAD
    // Validate CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // Nếu client muốn JSON, trả JSON 400
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (stripos($accept, 'application/json') !== false) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
            exit;
        }
=======

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
>>>>>>> main
        $_SESSION['message'] = 'Invalid CSRF token';
        header('Location: login.php');
        exit;
    }

<<<<<<< HEAD
    // Normalize inputs
    $username = trim((string)$_POST['username']);
    $password = trim((string)$_POST['password']);

    // Check XSS patterns (for testing/demo). Nếu phát hiện -> trả lỗi.
    if (contains_xss($username) || contains_xss($password)) {
        // Log để audit (ghi file logs server)
        error_log('XSS-like payload detected on login attempt. IP=' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . ' user_agent=' . ($_SERVER['HTTP_USER_AGENT'] ?? '') . ' payload=' . substr($username,0,200));

        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (stripos($accept, 'application/json') !== false) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'error' => 'Malicious input detected']);
            exit;
        }

        $_SESSION['message'] = 'Invalid input';
        header('Location: login.php');
        exit;
    }

    // Gọi auth (UserModel phải dùng prepared statements)
    $user = $userModel->auth($username, $password);

    if ($user) {
        // Tạo sessionId an toàn
        $sessionId = bin2hex(random_bytes(32));

        // Lưu session lên Redis (expiry 3600s)
        $redis->setex("session:$sessionId", 3600, json_encode([
            'id' => $user['id'] ?? null,
            'username' => $user['username'] ?? null
        ]));

        // Set cookie an toàn
        // NOTE: Trên môi trường development không có HTTPS, nếu đặt 'secure' => true cookie sẽ không gửi.
        // Trên production cần đảm bảo HTTPS và secure => true.
        $cookieOptions = [
            'expires' => time() + 3600,
            'path' => '/',
            'domain' => '', // set domain nếu cần
            // 'secure' => true, // BẬT trên production (HTTPS)
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', // auto detect; adjust as needed
            'httponly' => true,
            'samesite' => 'Lax'
        ];
        setcookie('session_id', $sessionId, $cookieOptions);

        // Sau login thành công, regen CSRF token
        unset($_SESSION['csrf_token']);

        // Redirect server-side
        header('Location: list_users.php');
        exit;
    } else {
        $_SESSION['message'] = 'Login failed';
        header('Location: login.php');
        exit;
=======
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $user = $userModel->auth($username, $password);

    if ($user && isset($user[0])) {
        $sessionId = bin2hex(random_bytes(32));

        $redis->setex("session:$sessionId", 3600, json_encode([
>>>>>>> lab4-csrf
            'id' => $user[0]['id'] ?? null,
            'username' => $user[0]['username'] ?? null
        ]));

<<<<<<< HEAD
        var_dump($test);

        // Lưu sessionId vào cookie (client)
        setcookie("session_id", $sessionId, time() + 3600, "/", "", false, true);
        // In ra đoạn JS để lưu vào localStorage
        echo "<script>
    localStorage.setItem('session_id', '$sessionId');
    window.location.href = 'list_users.php';
</script>";
        exit;
        header('Location: list_users.php');
=======
        setcookie("session_id", $sessionId, time() + 3600, "/", "", false, true);

        echo "<script>
                localStorage.setItem('session_id', '$sessionId');
                window.location.href = 'list_users.php';
              </script>";
>>>>>>> lab4-csrf
        exit;
    } else {
        $_SESSION['message'] = 'Login failed';
>>>>>>> main
    }
}
?>
<!DOCTYPE html>
<<<<<<< HEAD
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Login</title>
    <?php include 'views/meta.php' ?>
</head>

<body>
    <?php include 'views/header.php' ?>
=======
<html>

<<<<<<< HEAD
>>>>>>> lab3-redis_localstogare
=======
>>>>>>> lab4-csrf
<head>
    <title>User form</title>
    <?php include 'views/meta.php' ?>
</head>
<<<<<<< HEAD
<<<<<<< HEAD
<body>
<?php include 'views/header.php'?>

    <div class="container">
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info" >
                <div class="panel-heading">
                    <div class="panel-title">Login</div>
                    <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>
                </div>

                <div style="padding-top:30px" class="panel-body" >
                    <form method="post" class="form-horizontal" role="form">

                        <div class="margin-bottom-25 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="username or email">
                        </div>

                        <div class="margin-bottom-25 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
                        </div>

                        <div class="margin-bottom-25">
                            <input type="checkbox" tabindex="3" class="" name="remember" id="remember">
=======

<body>
    <?php include 'views/header.php' ?>

=======

<body>
    <?php include 'views/header.php' ?>
>>>>>>> lab4-csrf
>>>>>>> main
    <div class="container">
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Login</div>
<<<<<<< HEAD
                </div>
                <div style="padding-top:30px" class="panel-body">
                    <?php if (!empty($_SESSION['message'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>

                    <form method="POST" class="form-horizontal" role="form" autocomplete="off">
                        <input type="hidden" name="csrf_token"
                            value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
=======
<<<<<<< HEAD
                    <div style="float:right; font-size: 80%; position: relative; top:-10px">
                        <a href="#">Forgot password?</a>
                    </div>
                </div>

=======
                </div>
>>>>>>> lab4-csrf
                <div style="padding-top:30px" class="panel-body">
                    <?php if (!empty($_SESSION['message'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['message']) ?>
                        </div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>

                    <form method="post" class="form-horizontal" role="form">
<<<<<<< HEAD
                        <div class="margin-bottom-25 input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-user"></i>
                            </span>
                            <input id="login-username" type="text" class="form-control" name="username"
                                placeholder="username or email" required>
                        </div>

                        <div class="margin-bottom-25 input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-lock"></i>
                            </span>
                            <input id="login-password" type="password" class="form-control" name="password"
                                placeholder="password" required>
                        </div>

                        <div class="margin-bottom-25">
                            <input type="checkbox" tabindex="3" name="remember" id="remember">
>>>>>>> lab3-redis_localstogare
                            <label for="remember"> Remember Me</label>
                        </div>

                        <div class="margin-bottom-25 input-group">
<<<<<<< HEAD
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                                <a id="btn-fblogin" href="#" class="btn btn-primary">Login with Facebook</a>
=======
                            <div class="col-sm-12 controls">
                                <button type="submit" name="submit" value="submit"
                                    class="btn btn-primary">Submit</button>
                                <a id="btn-fblogin" href="#" class="btn btn-primary">
                                    Login with Facebook
                                </a>
>>>>>>> lab3-redis_localstogare
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 control">
<<<<<<< HEAD
                                    Don't have an account!
                                    <a href="form_user.php">
                                        Sign Up Here
                                    </a>
=======
                                Don't have an account!
                                <a href="form_user.php">Sign Up Here</a>
>>>>>>> lab3-redis_localstogare
                            </div>
=======
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
>>>>>>> main

                        <div class="margin-bottom-25 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input type="text" class="form-control" name="username" placeholder="username or email"
                                required>
                        </div>

                        <div class="margin-bottom-25 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="password" class="form-control" name="password" placeholder="password" required>
                        </div>

                        <div class="margin-bottom-25 input-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
<<<<<<< HEAD
                        </div>
                    </form>

                    <hr>
                    <p class="text-muted small">Lưu ý: để test XSS bằng Postman, set header
                        <code>Accept: application/json</code> — server sẽ trả JSON 400 khi phát hiện payload độc hại.
                    </p>
=======
>>>>>>> lab4-csrf
                        </div>
                    </form>
>>>>>>> main
                </div>
            </div>
        </div>
    </div>
<<<<<<< HEAD
</body>

=======
<<<<<<< HEAD
<<<<<<< HEAD

</body>
=======
</body>

>>>>>>> lab3-redis_localstogare
=======
</body>

>>>>>>> lab4-csrf
>>>>>>> main
</html>