<?php
session_start();
require 'config.php';

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $Categ = trim($_POST['category']);
    $Prio = trim($_POST['Prio']);

    if ($title ) {
        $stmt = $pdo->prepare('INSERT INTO tasks (UserID, Title, Category, Priority) VALUES (?, ?, ?, ?)');
        $stmt->execute([$_SESSION['UserID'], $title, $Categ, $Prio]);
        $message = 'Task added successfully.';
    } else {
        $message = 'Please fill in all required fields.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 400px; margin: 50px auto; }
        .message { color: green; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Task</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="post">
            <label>Title:</label><br>
            <input type="text" name="title" required><br><br>
            <label>Description:</label><br>
            <label >Category</label>
            <select name="category" id="category">
                <option value="Academic">Academic</option>
                <option value="Extracurricular">Extracurricular</option>
            </select><br><br>
            <label >Priority</label>
            <select name="Prio" id="prio">
                <option value="low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select><br><br>
            <input type="submit" value="Add Task">
        </form>

        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
