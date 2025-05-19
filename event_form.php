<?php
require 'auth.php';
require 'db.php';

$event = ['title' => '', 'event_date' => $_GET['date'] ?? date('Y-m-d'), 'category' => 'default'];
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    $event = $stmt->fetch();
}
?>
<form method="post" action="save_event.php">
    <input type="hidden" name="id" value="<?= $event['id'] ?? '' ?>">
    <input type="hidden" name="event_date" value="<?= $event['event_date'] ?>">
    Title: <input name="title" value="<?= $event['title'] ?>" required><br>
    Category: 
    <select name="category">
        <option value="work" <?= $event['category'] == 'work' ? 'selected' : '' ?>>Work</option>
        <option value="personal" <?= $event['category'] == 'personal' ? 'selected' : '' ?>>Personal</option>
        <option value="birthday" <?= $event['category'] == 'birthday' ? 'selected' : '' ?>>Birthday</option>
    </select><br>
    <button type="submit">Save</button>
</form>
