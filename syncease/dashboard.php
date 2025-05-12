<?php
session_start();
if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 100px; }
        .button { padding: 10px 20px; margin: 10px; font-size: 16px; }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['Username']); ?>!</h1>
    <p><a href="add_task.php"><button class="button">Add Task</button></a></p>
    <p><a href="calendarview.php"><button class="button">View Calendar</button></a></p>
    <p><a href="logout.php"><button class="button">Logout</button></a></p>
</body>
</html>
