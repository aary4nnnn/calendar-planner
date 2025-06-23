<?php
require 'auth.php';
require 'db.php';

$task = ['title' => '', 'description' => '', 'task_date' => $_GET['date'] ?? date('Y-m-d'), 'status' => 'pending'];

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    $task = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= isset($task['id']) ? 'Edit Task' : 'Add Task' ?></title>
  <link rel="stylesheet" href="assets/auth.css">
  <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(to right, #667eea, #764ba2);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .task-form-container {
        background: #fff;
        padding: 2rem 2.5rem;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        max-width: 400px;
        width: 100%;
        animation: fadeIn 0.4s ease-in-out;
    }

    .task-form-container h2 {
        margin-bottom: 1rem;
        color: #333;
        text-align: center;
    }

    .task-form-container input,
    .task-form-container textarea,
    .task-form-container button {
        width: 100%;
        padding: 10px 14px;
        margin: 8px 0 16px;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 15px;
        transition: 0.3s;
    }

    .task-form-container input:focus,
    .task-form-container textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }

    .task-form-container textarea {
        resize: vertical;
        min-height: 80px;
    }

    .task-form-container button {
        background-color: #667eea;
        color: white;
        border: none;
        font-weight: bold;
        cursor: pointer;
    }

    .task-form-container button:hover {
        background-color: #5a67d8;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
  </style>
</head>
<body>

<div class="task-form-container">
    <h2><?= isset($task['id']) ? 'Edit Task' : 'Add Task' ?></h2>
    <form method="post" action="save_task.php">
        <input type="hidden" name="id" value="<?= $task['id'] ?? '' ?>">
        <input type="hidden" name="task_date" value="<?= $task['task_date'] ?>">

        <label>Title</label>
        <input name="title" value="<?= htmlspecialchars($task['title']) ?>" required>

        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($task['description'] ?? '') ?></textarea>

        <button type="submit">Save Task</button>
    </form>
</div>

</body>
</html>
