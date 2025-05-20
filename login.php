<?php
require 'auth.php';
require 'db.php';

$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$startDay = date('w', strtotime("$year-$month-01"));

$stmt = $pdo->prepare("SELECT * FROM events WHERE user_id = ? AND MONTH(event_date) = ? AND YEAR(event_date) = ?");
$stmt->execute([$_SESSION['user_id'], $month, $year]);
// $events = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

$category_colors = ['work' => 'blue', 'personal' => 'green', 'birthday' => 'orange', 'default' => 'gray'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Calendar</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<h1>Welcome <?= $_SESSION['username'] ?> | <a href='logout.php'>Logout</a></h1>
<table>
    <tr><th colspan='7'><?= date('F Y', strtotime("$year-$month-01")) ?></th></tr>
    <tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>
    <tr>
    <?php
    for ($i = 0; $i < $startDay; $i++) echo "<td></td>";
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
        echo "<td>$day";
        foreach ($events as $event) {
            if ($event['event_date'] == $date) {
                $color = $category_colors[$event['category']] ?? 'gray';
                echo "<br><span class='event' style='color:$color;'>{$event['title']}</span>";
                echo "<a href='event_form.php?id={$event['id']}'>✏️</a>";
                //echo "<a href='delete_event.php?id={$event['id']}' onclick='return confirm("Delete this event? ")'>❌</a>";
                echo "<a href='delete_event.php?id={$event['id']}' onclick='return confirm(\"Delete this event?\")'>❌</a>";



            }
        }
        echo "<br><a href='event_form.php?date=$date'>➕</a>";
        echo "</td>";
        if (($day + $startDay) % 7 == 0) echo "</tr><tr>";
    }
    ?>
    </tr>
</table>
