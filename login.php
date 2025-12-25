<?php
session_start();
require '../includes/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username=?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Wrong login";
    }
}
?>

<form method="post">
    <h2>Admin Login</h2>
    <input name="username" placeholder="Username" required>
    <input name="password" type="password" placeholder="Password" required>
    <button>Login</button>
    <p style="color:red"><?= $error ?></p>
</form>
