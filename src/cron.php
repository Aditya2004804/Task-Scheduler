<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'functions.php';
sendTaskReminders();
echo "CRON executed";

require_once 'functions.php';
sendTaskReminders();
?>