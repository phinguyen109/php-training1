<?php
// Start the session
session_start();

require_once 'models/UserModel.php';
$userModel = new UserModel();

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
            'id' => $user[0]['id'] ?? null,
            'username' => $user[0]['username'] ?? null
        ]));

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
        exit;
    } else {
        $_SESSION['message'] = 'Login failed';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>User form</title>
    <?php include 'views/meta.php' ?>
</head>

<body>
    <?php include 'views/header.php' ?>

    <div class="container">
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Login</div>
                    <div style="float:right; font-size: 80%; position: relative; top:-10px">
                        <a href="#">Forgot password?</a>
                    </div>
                </div>

                <div style="padding-top:30px" class="panel-body">
                    <?php if (!empty($_SESSION['message'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['message']) ?>
                        </div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>

                    <form method="post" class="form-horizontal" role="form">
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
                            <label for="remember"> Remember Me</label>
                        </div>

                        <div class="margin-bottom-25 input-group">
                            <div class="col-sm-12 controls">
                                <button type="submit" name="submit" value="submit"
                                    class="btn btn-primary">Submit</button>
                                <a id="btn-fblogin" href="#" class="btn btn-primary">
                                    Login with Facebook
                                </a>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 control">
                                Don't have an account!
                                <a href="form_user.php">Sign Up Here</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>