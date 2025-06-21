// === src/README.md ===

# Task Scheduler – RTLearn Assignment ✅

A PHP-based task management and email reminder system using file storage and CRON jobs. This project fulfills all requirements provided by RTLearn.

---

## 📁 Folder Structure

```
src/
├── index.php                    # Main UI (tasks + email form)
├── functions.php                # Core logic (tasks, email, verification)
├── verify.php                   # Email verification handler
├── unsubscribe.php              # Unsubscribe handler
├── cron.php                     # Sends hourly task reminder emails
├── setup_cron.sh                # CRON job setup script
├── tasks.txt                    # Stores tasks in JSON format
├── subscribers.txt              # Stores verified emails in JSON
├── pending_subscriptions.txt    # Stores pending email verifications
└── sent_emails.txt              # Logs all outgoing emails for testing
```

---

## ✅ Features Implemented

### 1. Task Management
- Add tasks
- Prevent duplicate tasks
- Mark as complete/incomplete
- Delete tasks
- Store all in `tasks.txt` as JSON

### 2. Email Subscription System
- User submits email
- Generates verification code
- Writes email to `pending_subscriptions.txt`
- Sends a verification link (simulated via `sent_emails.txt`)
- On verification, email is moved to `subscribers.txt`

### 3. CRON Reminder System
- Runs `cron.php` every hour (or manually via browser for testing)
- Sends task reminder email with pending tasks to all subscribers
- Each reminder contains an unsubscribe link

### 4. Unsubscribe
- User clicks unsubscribe link → email removed from `subscribers.txt`

---

## 📩 Email Simulation (No Mail Server Required)
- Emails are simulated by writing HTML content to `sent_emails.txt`
- Contains:
  - Verification link
  - Reminder with task list
  - Unsubscribe link

---

## 🔁 CRON Setup

Run this from terminal in the `src/` folder:
```bash
chmod +x setup_cron.sh
./setup_cron.sh
```
This registers `cron.php` to run every hour.

---

## 🧪 Testing Flow

1. Go to: `http://localhost/src/index.php`
2. Add a task
3. Submit an email (e.g., `officialaditya9997@gmail.com`)
4. Open `sent_emails.txt`, copy verification link
5. Paste into browser → get “Email verified”
6. Run: `http://localhost/src/cron.php`
7. Check `sent_emails.txt` again for reminder
8. Click unsubscribe link to test unsubscription

---

## 📂 File Storage Format

### tasks.txt:
```json
[
  {
    "id": "abc123",
    "name": "Complete Assignment",
    "completed": false
  }
]
```

### subscribers.txt:
```json
["officialaditya9997@gmail.com"]
```

### pending_subscriptions.txt:
```json
{
  "officialaditya9997@gmail.com": {
    "code": "123456",
    "timestamp": 1718800000
  }
}
```

---

## 🚫 Limitations
- No actual email sent (uses local log file)
- PHP `mail()` is disabled in XAMPP by default

---

## 👨‍💻 Author
**Aditya Kumar**  
RTLearn PHP Task Scheduler Assignment – June 2025 ✅
