# Brody’s Daily Reports

A Simple PHP/MySQL/HTML/CSS web app to track daily school progress reports for my non-verbal son.  
Teachers submit a simple form; data is stored in MySQL and viewable in a secure admin dashboard.

**Live demo:** https://brodys.site/

## Features
- Public teacher form (mirrors paper form: Communication, Social, Academic, Adaptive, Specialists, Food/Drink, Bathroom, Notes)
- Secure admin login (bcrypt) and CSRF protection
- Search, detail view, and CSV export
- Clean PHP + PDO (prepared statements), MySQL schema included

## Tech Stack
- PHP 8.3, MySQL, HTML/CSS
- PDO (prepared statements), simple session auth
- Deployed on DreamHost shared hosting

## Getting Started (local or any shared host)
1. Create a MySQL DB and run [`schema.sql`](schema.sql) to create tables.
2. Copy `config.php.example` to `config.php` and fill in DB creds.
3. (Optional) Generate admin password hash:
   ```php
   <?php echo password_hash('YourStrongPassword', PASSWORD_BCRYPT);
