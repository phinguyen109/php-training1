<?php
session_start();
require_once 'models/UserModel.php';
$userModel = new UserModel();

$redis = new Redis();
$redis->connect('web-redis', 6379);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    var_dump($_SESSION['csrf_token']);
}

// Xử lý submit
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['username'], $_POST['password'], $_POST['csrf_token'])
) {


    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['message'] = 'Invalid CSRF token';
        header('Location: login.php');
        exit;
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $user = $userModel->auth($username, $password);

    if ($user && isset($user[0])) {
        $sessionId = bin2hex(random_bytes(32));

        $redis->setex("session:$sessionId", 3600, json_encode([
            'id' => $user[0]['id'] ?? null,
            'username' => $user[0]['username'] ?? null
        ]));

        setcookie("session_id", $sessionId, time() + 3600, "/", "", false, true);

        echo "<script>
                localStorage.setItem('session_id', '$sessionId');
                window.location.href = 'list_users.php';
              </script>";
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
                </div>
                <div style="padding-top:30px" class="panel-body">
                    <?php if (!empty($_SESSION['message'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['message']) ?>
                        </div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>

                    <form method="post" class="form-horizontal" role="form">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>