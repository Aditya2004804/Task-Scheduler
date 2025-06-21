#!/bin/bash
SCRIPT="$(pwd)/cron.php"
CRON="0 * * * * /usr/bin/php $SCRIPT > /dev/null 2>&1"
(crontab -l | grep -v -F "$SCRIPT"; echo "$CRON") | crontab -
echo "CRON job set to run hourly for cron.php"
