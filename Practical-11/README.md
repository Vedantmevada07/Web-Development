# Practical 11: Student Management Module (PHP + MySQLi)

## Problem Statement
Create a student management module to add, view, edit, delete, search, and filter student records. All database operations use MySQLi prepared statements and display clear success/failure messages.

## Features
- Add student records
- View all student records
- Edit student details
- Delete student records with confirmation
- Search by name, email, or mobile number
- Filter by course and semester
- Server-side input validation
- MySQLi prepared statements for all database operations
- Success and failure messages

## Folder Structure
```text
Practical-11/
├── database.sql
├── db.php
├── index.php
├── add.php
├── edit.php
├── delete.php
├── style.css
└── README.md
```

## Requirements
- XAMPP
- Apache and MySQL
- PHP with MySQLi
- Browser and phpMyAdmin

## Setup Instructions
1. Copy the `Practical-11` folder to:
   `C:\xampp\htdocs\Web_Development\Practical-11`
2. Start Apache and MySQL in XAMPP.
3. Open `http://localhost/phpmyadmin`.
4. Click the SQL tab, paste the content of `database.sql`, and click Go.
5. Check the database username/password in `db.php`. The default code uses `root` with a blank password.
6. Open:
   `http://localhost/Web_Development/Practical-11/index.php`

## Database
Database name: `student_management`

Table name: `students`

- `student_id`: primary key, auto-increment
- `name`: student name
- `email`: unique email address
- `mobile`: 10-digit mobile number
- `course`: course name
- `semester`: semester number from 1 to 8
- `created_at`: record creation timestamp

## How to Use
- Click **Add Student** to insert a new record.
- Use the search box to search by name, email, or mobile.
- Select a course and/or semester to filter the records.
- Click **Edit** to update a record.
- Click **Delete** and confirm to delete a record.
- Click **Reset** to clear search and filters.

## MySQLi
The application uses MySQLi prepared statements with `prepare()`, `bind_param()`, and `execute()` for select, insert, update, and delete operations. Input validation is performed on the server, and displayed values are escaped with `htmlspecialchars()`.

For a production website, add authentication/authorization, CSRF protection, and appropriate privacy controls.
