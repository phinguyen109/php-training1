<?php
session_start();
require_once 'models/UserModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra CSRF
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }

    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id > 0) {
        $userModel = new UserModel();
        $userModel->deleteUserById($id);
        $_SESSION['message'] = "User deleted successfully.";
    } else {
        $_SESSION['message'] = "Invalid user ID.";
    }
}

// Quay về danh sách
header("Location: list_users.php");
exit;