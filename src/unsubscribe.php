<?php
include 'functions.php';
$email = $_GET['email'] ?? '';
unsubscribeEmail($email);
echo "<p>You have been unsubscribed.</p>";
?>