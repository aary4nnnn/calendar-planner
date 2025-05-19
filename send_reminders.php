<?php
require 'db.php';

$todays_events = $pdo->prepare("
    SELECT users.username, users.email, events.title, events.event_date
    FROM events
    JOIN users ON events.user_id = users.id
    WHERE event_date = CURDATE()
");
$todays_events->execute();

while ($event = $todays_events->fetch()) {
    $to = $event['email'];
    $subject = "Event Reminder: {$event['title']}";
    $message = "Hi {$event['username']}, don't forget your event today: {$event['title']} on {$event['event_date']}";
    mail($to, $subject, $message);
}
