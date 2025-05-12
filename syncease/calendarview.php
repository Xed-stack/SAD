<?php
session_start();
require 'config.php';

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

// Fetch tasks for the logged-in user
$stmt = $pdo->prepare('SELECT Title, Category FROM tasks WHERE UserID = ?');
$stmt->execute([$_SESSION['UserID']]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize tasks by date
$tasksByDate = [];
foreach ($tasks as $task) {
    $tasksByDate[$task['DueDate']][] = $task['Title'];
}

// Generate calendar for the current month
$year = date('Y');
$month = date('m');
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$firstDayOfMonth = date('w', strtotime("$year-$month-01"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calendar View</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 10px; vertical-align: top; }
        th { background-color: #f2f2f2; }
        .task { margin: 5px 0; padding: 2px 5px; background-color: #e0e0e0; border-radius: 3px; }
    </style>
</head>
<body>
    <h2>Calendar - <?php echo date('F Y'); ?></h2>
    <table>
        <tr>
            <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th>
            <th>Thu</th><th>Fri</th><th>Sat</th>
        </tr>
        <tr>
        <?php
        $day = 1;
        $cell = 0;

        // Empty cells before the first day
        for ($i = 0; $i < $firstDayOfMonth; $i++) {
            echo '<td></td>';
            $cell++;
        }

        while ($day <= $daysInMonth) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            echo '<td><strong>' . $day . '</strong>';
            if (isset($tasksByDate[$date])) {
                foreach ($tasksByDate[$date] as $taskTitle) {
                    echo '<div class="task">' . htmlspecialchars($taskTitle) . '</div>';
                }
            }
            echo '</td>';
            $day++;
            $cell++;
            if ($cell % 7 == 0 && $day <= $daysInMonth) {
                echo '</tr><tr>';
            }
        }

        // Empty cells after the last day
        while ($cell % 7 != 0) {
            echo '<td></td>';
            $cell++;
        }
        ?>
        </tr>
    </table>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
