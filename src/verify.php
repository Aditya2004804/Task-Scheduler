<?php
include 'functions.php';
$email = $_GET['email'] ?? '';
$code = $_GET['code'] ?? '';
if (verifySubscription($email, $code)) {
    echo "<p>Email verified successfully!</p>";
} else {
    echo "<p>Invalid verification link.</p>";
}
?>