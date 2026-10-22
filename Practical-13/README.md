# Practical 13: PHP JSON API with Fetch API

## Aim
To develop PHP JSON API endpoints and consume them using the JavaScript Fetch API to perform List, Add, Update, and Delete operations asynchronously without reloading the webpage.

## Technologies Used
- HTML
- CSS
- JavaScript
- PHP
- MySQL
- JSON
- Fetch API
- XAMPP

## Features
- Display all students.
- Add new student records.
- Update existing student details.
- Delete student records.
- Send and receive JSON data.
- Perform CRUD operations without a full page reload.
- Validate student details and email addresses.
- Display success and error messages.
- Use prepared statements for database operations.

## Folder Structure
- `database.sql` — Creates the database and table.
- `db.php` — Connects PHP to MySQL.
- `students.php` — Handles the JSON API requests.
- `index.php` — Displays the user interface.
- `script.js` — Uses Fetch API for asynchronous operations.
- `style.css` — Styles the interface.
- `README.md` — Project documentation.

## Database
Database: `practical13`

Table: `students`

Fields:
- `id` — Primary key with auto-increment.
- `name` — Student name.
- `email` — Unique email address.
- `course` — Student course.

## Installation
1. Copy the project into `C:\xampp\htdocs\Web_Development\Practical-13`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin.
4. Execute the SQL code from `database.sql`.
5. Verify the connection settings in `db.php`.
6. Open `http://localhost/Web_Development/Practical-13/`.

## API Operations
- GET: List all students.
- POST: Add a student.
- PUT: Update student details.
- DELETE: Delete a student.

## Conclusion
This practical demonstrates how PHP and MySQL can provide a JSON API and how JavaScript Fetch API can consume the API asynchronously to perform CRUD operations without reloading the entire webpage.
