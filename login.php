<?php
require 'auth.php';
require 'db.php';

$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');

$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$startDay = date('w', strtotime("$year-$month-01"));

// Fetch events
$stmt = $pdo->prepare("SELECT * FROM events WHERE user_id = ? AND MONTH(event_date) = ? AND YEAR(event_date) = ?");
$stmt->execute([$_SESSION['user_id'], $month, $year]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch tasks
$taskStmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? AND MONTH(task_date) = ? AND YEAR(task_date) = ?");
$taskStmt->execute([$_SESSION['user_id'], $month, $year]);
$tasks = $taskStmt->fetchAll(PDO::FETCH_ASSOC);

// Category colors (used as classes now)
$category_classes = ['work' => 'work', 'personal' => 'personal', 'birthday' => 'birthday', 'default' => 'default'];

// Navigation calculation
$prevMonth = $month - 1;
$prevYear = $year;
$nextMonth = $month + 1;
$nextYear = $year;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
}
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Calendar</title>
    <link rel="stylesheet" href="assets/style.css" />
</head>

<body>
   <div class="sidebar">
    <h2>Menu</h2>
    <p>Hello, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>

    <!-- Email label with icon -->
    <div style="display: flex; align-items: center; gap: 8px;">
        <img src="screenshots/email.jpg" alt="Email Icon" style="width: 23px; height: 23px;">
        <span>Email:</span>
    </div>
    

    <!-- Email value on the next line -->
    <p><em><?= htmlspecialchars($_SESSION['email']) ?></em></p>
    <button id="darkModeToggle" class="dark-toggle-btn">🌙 Dark Mode</button>
        <a href="export_tasks.php" class="download-btn">📥 Download Tasks as Excel</a>

    <a href="logout.php" class="logout-btn">Logout</a>
</div>


    <div class="main-content">
        

        <div class="main-content" id="mainContent">
            <button id="toggleBtn">☰</button> <!-- Add this button -->
            <div class="navigation-links">
                <!-- existing nav links -->
            </div>
            <!-- rest of your content -->


            <div class="navigation-links">
                <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>">⏪ Prev</a>

                <form method="get" style="display: inline;">
                    <select name="month" onchange="this.form.submit()">
                        <?php
                        for ($m = 1; $m <= 12; $m++) {
                            $monthName = date("F", mktime(0, 0, 0, $m, 10));
                            $selected = ($m == $month) ? 'selected' : '';
                            echo "<option value='$m' $selected>$monthName</option>";
                        }
                        ?>
                    </select>

                    <select name="year" onchange="this.form.submit()">
                        <?php
                        for ($y = 2000; $y <= 2050; $y++) {
                            $selected = ($y == $year) ? 'selected' : '';
                            echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                    </select>
                </form>

                <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>">Next ⏩</a>
            </div>

            <table>
                <tr>
                    <th colspan="7"><?= date('F Y', strtotime("$year-$month-01")) ?></th>
                </tr>
                <tr>
                    <th>Sun</th>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                </tr>
                <tr>
                    <?php
                    // Blank cells for days before the month starts
                    for ($i = 0; $i < $startDay; $i++) {
                        echo "<td></td>";
                    }

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $date = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                       
                        $today = date('Y-m-d');


$cellClass = ($date == $today) ? 'today-cell' : '';
echo "<td class='$cellClass'><strong>$day</strong>";


                        // --- EVENTS SECTION ---
                        $hasEvents = false;
                        $hasTasks = false;

                        // Check for events on this date
                        foreach ($events as $event) {
                            if ($event['event_date'] === $date) {
                                $hasEvents = true;
                                break;
                            }
                        }

                        // Check for tasks on this date
                        foreach ($tasks as $task) {
                            if ($task['task_date'] === $date) {
                                $hasTasks = true;
                                break;
                            }
                        }
                        if ($hasEvents) {
                            echo "<div class='events-section'>";
                            echo "<div class='section-label'>📅 Events</div>";

                            foreach ($events as $event) {
                                if ($event['event_date'] === $date) {
                                    $cls = $category_classes[$event['category']] ?? 'default';
                                    $eventTitle = htmlspecialchars($event['title']);
                                    $eventDesc = htmlspecialchars($event['description']);
                                    $status = $event['status'];
                                    $eventDate = $event['event_date'];

                                    echo "<span class='event $cls'>$eventTitle</span>";

                                    if (!empty($eventDesc)) {
                                        echo "<div class='event-description'>$eventDesc</div>";
                                    }

                                    if ($status === 'done') {
                                        echo "<span class='status-done'>✅ Event Completed</span>";
                                    } else {
                                        $statusText2 = ($eventDate > $today) ? "⏳ Upcoming"
                                            : (($eventDate == $today) ? "⚠️ Due" : "❌ Event Passed!");
                                        echo "<span class='event-status'>$statusText2</span>";
                                        echo "<a class='action-link' href='mark_done.php?id={$event['id']}&type=event'>✔️</a>";
                                    }

                                    echo "<a class='action-link' href='event_form.php?id={$event['id']}'>✏️</a>";
                                    echo "<a class='action-link' href='delete_event.php?id={$event['id']}' onclick='return confirm(\"Delete this event?\")'>🗑️</a>";
                                }
                            }
                            echo "</div>"; // End events section
                        }
                        // --- TASKS SECTION ---
                        if ($hasTasks) {
                            echo "<div class='tasks-section'>";
                            echo "<div class='section-label'>📝 Tasks</div>";

                            foreach ($tasks as $task) {
                                if ($task['task_date'] === $date) {
                                    $status = $task['status'];
                                    $statusText = ($status === 'done') ? "✅ Task Completed"
                                        : (($task['task_date'] > $today) ? "⏳ Upcoming"
                                            : (($task['task_date'] == $today) ? "⚠️ Due" : "Deadline exceeded!"));

                                    echo "<span class='task'>{$task['title']} - $statusText</span>";

                                    if ($status !== 'done') {
                                        echo "<a class='action-link' href='mark_done.php?id={$task['id']}&type=task'>✔️</a>";
                                    }

                                    echo "<a class='action-link' href='task_form.php?id={$task['id']}'>✏️</a>";
                                    echo "<a class='action-link' href='delete_task.php?id={$task['id']}' onclick='return confirm(\"Delete this task?\")'>🗑️</a>";
                                }
                            }
                            echo "</div>"; // End tasks section
                        }
                        // Add event/task links
                        //on 22 may 2025 changes logos using svg (diiferent)
                        echo "<div class='add-buttons'>
        <a class='action-link' href='event_form.php?date=$date' title='Add Event'>
            <svg viewBox='0 0 24 24' width='20' height='20'><path fill='currentColor' d='M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 
                1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 
                16H5V8h14v11zM12 10v4H8v2h4v4h2v-4h4v-2h-4v-4h-2z'/>
            </svg>
        </a>
        <a class='action-link' href='task_form.php?date=$date' title='Add Task'>
            <svg viewBox='0 0 24 24' width='20' height='20'><path fill='currentColor' d='M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 
                2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 
                16H5V8h14v11zM7 10h5v5H7v-5z'/>
            </svg>
        </a>
    </div>";

                        echo "</td>";

                        // New row after Saturday
                        if (($day + $startDay) % 7 === 0 && $day !== $daysInMonth) {
                            echo "</tr><tr>";
                        }
                    }

                    // Fill remaining cells after month end
                    $remainingCells = (7 - (($daysInMonth + $startDay) % 7)) % 7;
                    for ($i = 0; $i < $remainingCells; $i++) {
                        echo "<td></td>";
                    }
                    ?>
                </tr>
            </table>
        </div>
        <script>
            const toggleBtn = document.getElementById('toggleBtn');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');

            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('closed');
                mainContent.classList.toggle('expanded');

                // Toggle button text: show just ☰ when sidebar open, ☰ Menu when closed
                if (sidebar.classList.contains('closed')) {
                    toggleBtn.textContent = '☰ Menu';
                } else {
                    toggleBtn.textContent = '☰'; // just the icon, no text
                }

                // Set initial toggle button text based on sidebar state on page load
                if (sidebar.classList.contains('closed')) {
                    toggleBtn.textContent = '☰ Menu';
                } else {
                    toggleBtn.textContent = '☰ Close Menu';
                }

            });
        
    const darkToggleBtn = document.getElementById('darkModeToggle');
    const body = document.body;

    // Load dark mode state from localStorage
    //local storage is browser's storage
    if (localStorage.getItem('darkMode') === 'true') {
        body.classList.add('dark-mode');
    }

    darkToggleBtn.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', body.classList.contains('dark-mode'));
    });
</script>

    </div>

</body>

</html>