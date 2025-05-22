<?php
require 'auth.php';
require 'db.php';

$event = ['title' => '', 'event_date' => $_GET['date'] ?? date('Y-m-d'), 'category' => 'default', 'description' => ''];

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    $event = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Event Form</title>
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

    .event-form-container {
        background: #fff;
        padding: 2rem 2.5rem;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        max-width: 400px;
        width: 100%;
        animation: fadeIn 0.4s ease-in-out;
    }

    .event-form-container h2 {
        margin-bottom: 1rem;
        color: #333;
        text-align: center;
    }

    .event-form-container input,
    .event-form-container select,
    .event-form-container textarea,
    .event-form-container button {
        width: 100%;
        padding: 10px 14px;
        margin: 8px 0 16px;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 15px;
        transition: 0.3s;
    }

    .event-form-container input:focus,
    .event-form-container select:focus,
    .event-form-container textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }

    .event-form-container textarea {
        resize: vertical;
        min-height: 80px;
    }

    .event-form-container button {
        background-color: #667eea;
        color: white;
        border: none;
        font-weight: bold;
        cursor: pointer;
    }

    .event-form-container button:hover {
        background-color: #5a67d8;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
  </style>
</head>
<body>

<div class="event-form-container">
    <h2><?= isset($event['id']) ? 'Edit Event' : 'Add Event' ?></h2>
    <form method="post" action="save_event.php">
        <input type="hidden" name="id" value="<?= $event['id'] ?? '' ?>">
        <input type="hidden" name="event_date" value="<?= $event['event_date'] ?>">
        
        <label>Title</label>
        <input name="title" value="<?= htmlspecialchars($event['title']) ?>" required>

        <label>Category</label>
        <select name="category">
            <option value="work" <?= $event['category'] == 'work' ? 'selected' : '' ?>>Work</option>
            <option value="personal" <?= $event['category'] == 'personal' ? 'selected' : '' ?>>Personal</option>
            <option value="birthday" <?= $event['category'] == 'birthday' ? 'selected' : '' ?>>Birthday</option>
        </select>

        <label>Description</label>
        <textarea name="description" placeholder="Describe your event..."><?= htmlspecialchars($event['description'] ?? '') ?></textarea>

        <button type="submit">Save</button>
    </form>
</div>

</body>
</html>
