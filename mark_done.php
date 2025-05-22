<?php
require 'auth.php';
require 'db.php';

$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? null;

if (!$id || !$type || !in_array($type, ['task', 'event'])) {
    header("Location: login.php");
    exit;
}

$table = $type === 'task' ? 'tasks' : 'events';

// Verify ownership
$stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$item = $stmt->fetch();

if ($item) {
    $update = $pdo->prepare("UPDATE $table SET status = 'done' WHERE id = ?");
    $update->execute([$id]);
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>
