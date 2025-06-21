<?php
function readJsonFile($filename) {
    if (!file_exists($filename)) return [];
    $content = file_get_contents($filename);
    return json_decode($content, true) ?? [];
}

function writeJsonFile($filename, $data) {
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

function logEmail($to, $subject, $message) {
    $log = "To: $to\nSubject: $subject\n$message\n----------------------\n";
    file_put_contents("sent_emails.txt", $log, FILE_APPEND);
}

function addTask($task_name) {
    $tasks = readJsonFile('tasks.txt');
    foreach ($tasks as $task) {
        if (strtolower($task['name']) == strtolower($task_name)) return;
    }
    $tasks[] = ["id" => uniqid(), "name" => $task_name, "completed" => false];
    writeJsonFile('tasks.txt', $tasks);
}

function getAllTasks() {
    return readJsonFile('tasks.txt');
}

function markTaskAsCompleted($task_id, $is_completed) {
    $tasks = readJsonFile('tasks.txt');
    foreach ($tasks as &$task) {
        if ($task['id'] === $task_id) {
            $task['completed'] = $is_completed;
            break;
        }
    }
    writeJsonFile('tasks.txt', $tasks);
}

function deleteTask($task_id) {
    $tasks = readJsonFile('tasks.txt');
    $tasks = array_filter($tasks, fn($task) => $task['id'] !== $task_id);
    writeJsonFile('tasks.txt', array_values($tasks));
}

function generateVerificationCode() {
    return strval(rand(100000, 999999));
}

function subscribeEmail($email) {
    $pending = readJsonFile('pending_subscriptions.txt');
    $code = generateVerificationCode();
    $pending[$email] = ["code" => $code, "timestamp" => time()];
    writeJsonFile('pending_subscriptions.txt', $pending);

    $verification_link = "http://" . $_SERVER['HTTP_HOST'] . "/verify.php?email=" . urlencode($email) . "&code=" . $code;
    $subject = "Verify subscription to Task Planner";
    $message = "<p>Click the link below to verify your subscription to Task Planner:</p><p><a id='verification-link' href='$verification_link'>Verify Subscription</a></p>";
    logEmail($email, $subject, $message); // Simulate sending email
    // mail($email, $subject, $message, "Content-type:text/html;charset=UTF-8\r\nFrom: no-reply@example.com");
}

function verifySubscription($email, $code) {
    $pending = readJsonFile('pending_subscriptions.txt');
    if (isset($pending[$email]) && $pending[$email]['code'] == $code) {
        $subscribers = readJsonFile('subscribers.txt');
        if (!in_array($email, $subscribers)) {
            $subscribers[] = $email;
            writeJsonFile('subscribers.txt', $subscribers);
        }
        unset($pending[$email]);
        writeJsonFile('pending_subscriptions.txt', $pending);
        return true;
    }
    return false;
}

function unsubscribeEmail($email) {
    $subscribers = readJsonFile('subscribers.txt');
    $subscribers = array_filter($subscribers, fn($e) => $e !== $email);
    writeJsonFile('subscribers.txt', array_values($subscribers));
}

function sendTaskReminders() {
    $subscribers = readJsonFile('subscribers.txt');
    $tasks = readJsonFile('tasks.txt');
    $pending_tasks = array_filter($tasks, fn($task) => !$task['completed']);
    foreach ($subscribers as $email) {
        sendTaskEmail($email, $pending_tasks);
    }
}

function sendTaskEmail($email, $pending_tasks) {
    $subject = "Task Planner - Pending Tasks Reminder";
    $body = "<h2>Pending Tasks Reminder</h2><p>Here are the current pending tasks:</p><ul>";
    foreach ($pending_tasks as $task) {
        $body .= "<li>" . htmlspecialchars($task['name']) . "</li>";
    }
    $body .= "</ul>";
    $unsubscribe_link = "http://" . $_SERVER['HTTP_HOST'] . "/unsubscribe.php?email=" . urlencode($email);
    $body .= "<p><a id='unsubscribe-link' href='$unsubscribe_link'>Unsubscribe from notifications</a></p>";
    logEmail($email, $subject, $body); // Simulate sending email
    // mail($email, $subject, $body, "Content-type:text/html;charset=UTF-8\r\nFrom: no-reply@example.com");
}
?>