# Practical 12: Event Management Module

## Aim
To develop an event management module for admin users that allows them to add, view, update, delete, and upload event posters with file type and file size validation.

## Technologies Used
- HTML
- CSS
- PHP
- MySQL
- MySQLi
- XAMPP

## Features
1. Admin login and logout.
2. Add new events with event name, date, location, and description.
3. View all event records in a table.
4. Update existing event details.
5. Delete events after confirmation.
6. Upload event posters while adding or editing events.
7. Validate poster file type and file size.
8. Display success and failure messages.
9. Use MySQLi prepared statements for database operations.
10. Restrict event management pages to logged-in administrators.

## Folder Structure

Practical-12/
│
├── uploads/
│   └── Event poster images
│
├── database.sql
├── db.php
├── auth.php
├── login.php
├── logout.php
├── index.php
├── event_form.php
├── add_event.php
├── edit_event.php
├── delete_event.php
├── style.css
└── README.md

## Database Details

**Database Name:** `event_management`

### Admins Table
Stores administrator login information.

- `admin_id` – Primary key
- `username` – Admin username
- `password` – Hashed admin password

### Events Table
Stores event information.

- `event_id` – Primary key
- `event_name` – Name of the event
- `event_date` – Date of the event
- `location` – Event location
- `description` – Event description
- `poster` – Uploaded poster filename
- `created_at` – Record creation timestamp

## Event Poster Validation

The application validates uploaded posters using the following rules:

- **Allowed file formats:** JPG, JPEG, PNG, WEBP
- **Maximum file size:** 2 MB
- Checks the file extension.
- Checks the detected MIME type.
- Verifies that the uploaded file is a valid image.
- Generates a unique filename for each uploaded poster.
- Stores poster files in the `uploads/` directory.

## Installation and Setup

1. Install XAMPP on your computer.
2. Copy the project folder into:

   `C:\xampp\htdocs\Web_Development\Practical-12`

3. Open the XAMPP Control Panel.
4. Start **Apache** and **MySQL**.
5. Open phpMyAdmin:

   `http://localhost/phpmyadmin`

6. Select the **SQL** tab.
7. Copy and execute the SQL statements from `database.sql`.
8. Open `db.php` and verify the MySQL connection settings.
9. Ensure that the `uploads/` directory exists.
10. Open the application in your browser:

    `http://localhost/Web_Development/Practical-12/login.php`

## Demo Admin Login

For local classroom testing:

- **Username:** `admin`
- **Password:** `password`

Change the demo password before using the application in a real environment.

## How to Use

1. Log in using the administrator account.
2. Click **Add New Event**.
3. Enter the event name, date, location, and description.
4. Select an optional event poster.
5. Click **Save Event**.
6. View the event in the admin dashboard.
7. Click **Edit** to update event details or replace its poster.
8. Click **Delete** to remove an event after confirming the action.
9. Click **Logout** to end the admin session.

## Security Measures

- MySQLi prepared statements are used for database queries.
- Uploaded posters are validated by file type and size.
- Unique filenames are generated for uploaded posters.
- Output is escaped using `htmlspecialchars()` to reduce cross-site scripting risks.
- Admin pages require an authenticated session.
- Passwords are checked using PHP's `password_verify()` function.

## Conclusion

The Event Management Module provides a simple admin interface to manage event records and upload event posters. It uses PHP, MySQL, and MySQLi prepared statements, with validation and access control to support safer event management.