
# Practical-14: Admin Dashboard

## Aim
To develop an admin dashboard using PHP and MySQL that displays user statistics, student count, event count, and latest registrations. The dashboard uses session-based authentication and role-based access control.

## Technologies Used
- HTML
- CSS
- PHP
- MySQL
- XAMPP

## Features
1. Login and logout using PHP sessions.
2. Display total users, students, events, and registrations.
3. Display the five latest registrations.
4. Restrict user management to administrators.
5. Show dynamic menu options based on user role.
6. Use password verification and prepared statements.
7. Responsive dashboard layout.

## Folder Structure
Practical-14/
- database.sql
- db.php
- auth.php
- login.php
- dashboard.php
- students.php
- events.php
- users.php
- logout.php
- style.css
- README.md

## Database
Database name: practical14

Tables:
- users
- students
- events
- registrations

## Installation
1. Copy Practical-14 into htdocs/Web_Development.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin.
4. Import or execute database.sql.
5. Open http://localhost/Web_Development/Practical-14/login.php.

## Demo Credentials
Admin:
- Username: admin
- Password: password

Student:
- Username: student
- Password: password

## Access Control
Administrators can access the dashboard statistics and user-management page. Students receive a student dashboard and do not have access to users.php.

## Conclusion
The practical demonstrates database-driven dashboard statistics, PHP session authentication, and role-based access control.
