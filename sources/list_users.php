<?php
// Start the session
session_start();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once 'models/UserModel.php';
$userModel = new UserModel();

$params = [];
if (!empty($_GET['keyword'])) {
    $params['keyword'] = $_GET['keyword'];
}

$users = $userModel->getUsers($params);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <?php include 'views/meta.php' ?>
</head>

<body>
    <?php include 'views/header.php' ?>
    <div class="container">
        <?php if (!empty($users)) { ?>
        <div class="alert alert-warning" role="alert">
            List of users!
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Username</th>
                    <th scope="col">Fullname</th>
                    <th scope="col">Type</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) { ?>
                <tr>
                    <th scope="row"><?php echo htmlspecialchars($user['id']) ?></th>
                    <td><?php echo htmlspecialchars($user['name']) ?></td>
                    <td>null</td>
                    <td><?php echo htmlspecialchars($user['type']) ?></td>
                    <td>
                        <a href="form_user.php?id=<?php echo $user['id'] ?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true" title="Update"></i>
                        </a>
                        <a href="view_user.php?id=<?php echo $user['id'] ?>">
                            <i class="fa fa-eye" aria-hidden="true" title="View"></i>
                        </a>
                        <!-- Delete form -->
                        <form method="POST" action="delete_user.php" style="display:inline;"
                            onsubmit="return confirm('Are you sure to delete this user?');">
                            <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">
                            <button type="submit" style="border:none; background:none; padding:0; cursor:pointer;">
                                <i class="fa fa-eraser text-danger" aria-hidden="true" title="Delete"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <div class="alert alert-dark" role="alert">
            No users found!
        </div>
        <?php } ?>
    </div>
</body>

</html>