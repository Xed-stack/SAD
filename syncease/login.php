<?php
session_start();
require 'config.php'; // Ensure this file sets up your $pdo connection

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check for hardcoded user
    if ($username === 'Xedrik' && $password === '12345678') {
        $_SESSION['UserID'] = 1;
        $_SESSION['Username'] = 'Xedrik';
        header('Location: dashboard.php');
        exit();
    } else {
        // Check database for user
        $stmt = $pdo->prepare('SELECT UserID, password FROM users WHERE Username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['UserID'] = $user['UserID'];
            $_SESSION['Username'] = $username;
            header('Location: dashboard.php');
            exit();
        } else {
            $message = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 300px; margin: 50px auto; }
        .message { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post">
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>
            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
