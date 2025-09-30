<?php
// logout.php (ngắn, thực tế)
session_start();

// Lấy session id (nếu bạn dùng cookie 'session_id' để lưu Redis session)
$cookieSessionId = $_COOKIE['session_id'] ?? null;

// Hủy PHP session
$_SESSION = [];
session_unset();
session_destroy();

// Xóa Redis session nếu có (im lặng khi lỗi)
if ($cookieSessionId) {
    try {
        $r = new Redis();
        $r->connect('web-redis', 6379);
        $r->del("session:$cookieSessionId");
    } catch (Throwable $e) {
        // ignore / log nếu cần
    }
}

// Xóa cookie (httponly cookie phải xóa server-side)
setcookie('session_id', '', time() - 3600, '/', '', false, true);

// Trả JS nhỏ để xóa localStorage và redirect
?>
<!doctype html>
<html>

<body>
    <script>
    try {
        localStorage.removeItem('session_id');
    } catch (e) {}
    window.location.href = 'login.php';
    </script>
</body>

</html>