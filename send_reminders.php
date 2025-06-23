<?php
require 'db.php';
date_default_timezone_set('Asia/Kolkata'); // Adjust as needed

$todays_events = $pdo->prepare("
    SELECT users.username, users.email, events.title, events.description, events.event_date
    FROM events
    JOIN users ON events.user_id = users.id
    WHERE event_date = CURDATE()
");
$todays_events->execute();
$events = $todays_events->fetchAll(PDO::FETCH_ASSOC);

foreach ($events as $event) {
    $to = $event['email'];
    $subject = "Event Reminder: {$event['title']}";
    $message = "Hi {$event['username']},\n\n"
             . "Don't forget your event today:\n"
             . "📅 Title: {$event['title']}\n"
             . "📝 Description: {$event['description']}\n"
             . "📆 Date: {$event['event_date']}\n\n"
             . "Have a productive day!";
             
    $headers = "From: no-reply@vitaryan.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8";

    if (!mail($to, $subject, $message, $headers)) {
        error_log("Failed to send event reminder to $to");
    }
}
