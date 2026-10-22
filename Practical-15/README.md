
# Practical-15: Integrated StudentHub Portal

## Aim
To integrate the StudentHub modules into a single PHP and MySQL portal, verify navigation, responsiveness, authentication, database operations, security, and AJAX functionality, and deploy locally using XAMPP and Apache.

## Technologies
- HTML5
- CSS3
- JavaScript Fetch API
- PHP
- MySQL
- Apache / XAMPP
- Git and GitHub

## Features
- Home page and responsive navigation
- Student registration and login
- Session-based authentication
- Role-based menus for admin and student
- Dashboard statistics
- Student listing and AJAX-based student insertion
- Event listing and admin event creation
- Event registration with duplicate prevention
- Announcement management
- Admin-only user listing
- Prepared SQL statements
- Password hashing and verification
- CSRF token validation on protected forms
- Server-side input validation

## Folder Structure
Practical-15/
- index.php
- login.php
- register.php
- logout.php
- dashboard.php
- students.php
- events.php
- registrations.php
- announcements.php
- users.php
- db.php
- auth.php
- api.php
- style.css
- script.js
- database.sql
- README.md
- .gitignore
- uploads/
- tests/

## Local Installation
1. Install XAMPP.
2. Copy the project to htdocs/Web_Development/Practical-15.
3. Start Apache and MySQL.
4. Open phpMyAdmin.
5. Execute database.sql.
6. Visit http://localhost/Web_Development/Practical-15/.
7. Log in with a demo account.

## Demo Accounts
Admin:
- Username: admin
- Password: password

Student:
- Username: student
- Password: password

Use demo accounts only for local testing. Change demo credentials before deployment outside your local machine.

## Database
Database: studenthub15

Tables:
- users
- students
- events
- registrations
- announcements

## AJAX
The students page sends a Fetch API request to api.php. The API checks authentication, admin authorization, CSRF protection, and input validation before inserting a student. Successful responses use JSON.

## Security
- password_hash() and password_verify()
- Prepared statements
- Role checks on restricted pages
- CSRF token validation for protected forms
- HTML output escaping
- Server-side input validation

## Testing
See the Testing Checklist below. Record actual results after testing each item.

## Limitations
This practical includes student insertion using AJAX and basic event and announcement creation. It does not yet provide complete edit/delete functionality for every module or event poster uploading.

## Conclusion
The integrated portal demonstrates how individual web-development modules can share authentication, a database, common navigation, and responsive styling.
