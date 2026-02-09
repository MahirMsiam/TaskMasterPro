# TaskMaster Pro

TaskMaster Pro is a self-hosted project management platform with AI-powered insights for small to medium teams. It combines project planning, task tracking, and analytics in a clean, modern UI.

## Features
- Role-based authentication and dashboards
- Project and task tracking (foundation)
- Secure password handling and session controls
- Notification scaffolding and activity feeds
- Database schema and sample data for quick setup

## Tech Stack
- PHP 8.1+
- MySQL 8+
- HTML5, CSS3, Vanilla JavaScript
- Apache (XAMPP)

## Setup
1. Clone the repository into your XAMPP `htdocs` directory.
2. Create a MySQL database named `taskmaster_pro`.
3. Import `sql/schema.sql` and `sql/sample_data.sql` into the database.
4. Update database credentials in `config/database.php` if needed.
5. Visit `http://localhost/taskmaster-pro/` to launch the app.

## Demo Credentials
- **Super Admin:** admin@taskmasterpro.test / Password!1
- **Project Manager:** pm1@taskmasterpro.test / Password!1
- **Team Member:** member1@taskmasterpro.test / Password!1

## Screenshots
Add screenshots of dashboards and auth flows after deploying the UI.
