<?php
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
    }
}
?>
<!DOCTYPE html>
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
    <div class="container">
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Login</div>
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
>>>>>>> lab4-csrf
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<<<<<<< HEAD
<<<<<<< HEAD

</body>
=======
</body>

>>>>>>> lab3-redis_localstogare
=======
</body>

>>>>>>> lab4-csrf
</html>