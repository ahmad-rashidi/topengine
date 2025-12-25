<?php
require 'auth.php'; // فقط ادمین وارد شده می‌تونه ببینه
require '../includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];

    // گرفتن اطلاعات admin از دیتابیس
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE id=?");
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($current, $admin['password'])) {
        if ($new === $confirm) {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE admins SET password=? WHERE id=?");
            $stmt->execute([$hash, $_SESSION['admin_id']]);
            $message = "Password changed successfully!";
        } else {
            $message = "New passwords do not match!";
        }
    } else {
        $message = "Current password is incorrect!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-header">
    <strong>TopEngine Admin</strong>
    <a href="dashboard.php">Dashboard</a>
    <a href="logout.php">Logout</a>
</div>

<div class="admin-container card">
    <h2>Change Password</h2>
    <?php if($message): ?>
        <p style="color:red;"><?= $message ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="password" name="current" placeholder="Current Password" required><br><br>
        <input type="password" name="new" placeholder="New Password" required><br><br>
        <input type="password" name="confirm" placeholder="Confirm New Password" required><br><br>
        <button>Change Password</button>
    </form>
</div>

</body>
</html>
