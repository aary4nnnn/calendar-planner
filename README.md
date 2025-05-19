
# 📅 PHP Calendar & Event Planner

A complete calendar event planner built with PHP + MySQL. Includes login system, color-coded events, categories, and daily email reminders.

## ✨ Features
- User authentication (login/register/logout)
- Monthly calendar view
- Add, edit, delete events
- Color-coding for event categories
- Email reminders (via cron job)

## 🔧 Setup

1. Import the `sql/init.sql` file into MySQL
2. Update your DB credentials in `db.php`
3. Serve project via Apache/localhost
4. For reminders, schedule `send_reminders.php` using cron:
   ```
   0 7 * * * /usr/bin/php /path/to/send_reminders.php
   ```

## 📁 Structure
Refer to `index.php` for the main calendar, and `event_form.php` to manage events.

## 📜 License
MIT
