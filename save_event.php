<?php
require 'auth.php';
require 'db.php';

$title = $_POST['title'];
$category = $_POST['category'];
$date = $_POST['event_date'];
$description = $_POST['description'] ?? '';
$id = $_POST['id'] ?? null;
$user_id = $_SESSION['user_id'];

if ($id) {
    // Update existing event
    $stmt = $pdo->prepare("UPDATE events SET title = ?, category = ?, description = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $category, $description, $id, $user_id]);
} else {
    // Insert new event
    $stmt = $pdo->prepare("INSERT INTO events (user_id, title, event_date, category, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $date, $category, $description]);
}

header("Location: login.php");
exit;
