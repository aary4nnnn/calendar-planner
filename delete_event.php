<?php
require 'auth.php';
require 'db.php';

$stmt = $pdo->prepare("DELETE FROM events WHERE id = ? AND user_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
header("Location: login.php");
