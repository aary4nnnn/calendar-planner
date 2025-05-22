<?php
require 'auth.php';
require 'db.php';

$title = $_POST['title'];
$date = $_POST['task_date'];
$id = $_POST['id'] ?? null;
$user_id = $_SESSION['user_id'];

if ($id) {
    $stmt = $pdo->prepare("UPDATE tasks SET title = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $id, $user_id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, task_date) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $title, $date]);
}

header("Location: login.php");
exit;
