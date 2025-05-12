<?php
session_start();
require 'config.php'; // Ensure this file sets up your $pdo connection

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check if username already exists
    $stmt = $pdo->prepare('SELECT UserID FROM users WHERE Username = ?');
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $message = 'Username already taken.';
    } else {
        // Insert new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (Username, password) VALUES (?, ?)');
        $stmt->execute([$username, $hashed_password]);
        $userID = $pdo->lastInsertId();
        $_SESSION['UserID'] = $userID;
        $_SESSION['Username'] = $username;
        header('Location: dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 300px; margin: 50px auto; }
        .message { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post">
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>
            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>
            <input type="submit" value="Sign Up">
        </form>
    </div>
</body>
</html>
