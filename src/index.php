<?php
include 'functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task-name'])) {
        addTask(trim($_POST['task-name']));
    }
    if (isset($_POST['email'])) {
        subscribeEmail(trim($_POST['email']));
    }
}
if (isset($_GET['complete'])) {
    markTaskAsCompleted($_GET['complete'], true);
}
if (isset($_GET['incomplete'])) {
    markTaskAsCompleted($_GET['incomplete'], false);
}
if (isset($_GET['delete'])) {
    deleteTask($_GET['delete']);
}
$tasks = getAllTasks();
?>
<!DOCTYPE html>
<html>
<head><title>Task Scheduler</title></head>
<body>
<h2>Task Manager</h2>
<form method="POST">
    <input type="text" name="task-name" id="task-name" placeholder="Enter new task" required>
    <button type="submit" id="add-task">Add Task</button>
</form>
<ul class="tasks-list">
<?php foreach ($tasks as $task): ?>
    <li class="task-item <?php echo $task['completed'] ? 'completed' : ''; ?>">
        <form method="GET" style="display:inline">
            <input type="hidden" name="<?php echo $task['completed'] ? 'incomplete' : 'complete'; ?>" value="<?php echo $task['id']; ?>">
            <input type="checkbox" class="task-status" onchange="this.form.submit()" <?php echo $task['completed'] ? 'checked' : ''; ?>>
        </form>
        <?php echo htmlspecialchars($task['name']); ?>
        <form method="GET" style="display:inline">
            <input type="hidden" name="delete" value="<?php echo $task['id']; ?>">
            <button class="delete-task">Delete</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>

<h2>Email Subscription</h2>
<form method="POST">
    <input type="email" name="email" required />
    <button id="submit-email">Submit</button>
</form>
</body>
</html>
