# TaskMaster Pro स्थापना / Installation Guide

## Prerequisites
- XAMPP (Apache + MySQL + PHP 8.1+)
- MySQL 8+

## Steps
1. **Download/Clone Repository**
   - Place the project folder inside `xampp/htdocs/taskmaster-pro`.

2. **Create Database**
   - Open phpMyAdmin and create a database named `taskmaster_pro`.

3. **Import Schema**
   - Import `sql/schema.sql` first.
   - Import `sql/sample_data.sql` for demo data.

4. **Configure Database Connection**
   - Update `config/database.php` with your DB credentials if needed.

5. **Run the Application**
   - Open `http://localhost/taskmaster-pro/` in your browser.

## Notes
- Ensure Apache and MySQL services are running in XAMPP.
- For production, enable HTTPS and set `session.cookie_secure` to `1`.
