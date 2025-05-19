<?php
require 'auth.php';
require 'db.php';

$title = $_POST['title'];
$category = $_POST['category'];
$date = $_POST['event_date'];
$id = $_POST['id'] ?? null;
$user_id = $_SESSION['user_id'];

if ($id) {
    $stmt = $pdo->prepare("UPDATE events SET title = ?, category = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $category, $id, $user_id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO events (user_id, title, event_date, category) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $date, $category]);
}
header("Location: index.php");
