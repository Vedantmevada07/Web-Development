# StudentHub — Event Registration & Management System

A web-based portal developed in **PHP** and backed by a relational **MySQL** database to manage student records, organise campus events, and handle multi-entity registrations.

---

## 🚀 Features

- **Relational Integrity:** Implements primary and foreign key constraints linking students, events, and their cross-reference registrations.
- **Prepared SQL Statements:** Utilizes parameterized input queries via `$conn->prepare()` on forms to prevent SQL injection vulnerabilities.
- **Dynamic Selection Inputs:** Populates select dropdown elements in real-time from active relational tables.
- **Data Sanitization:** Implements structural backend regex matching (`/^[0-9]{10}$/`) and frontend bounds checks (`min="1" max="8"`) to protect analytical workflows.
- **Tabular Data Previews:** Employs relational SQL `INNER JOIN` clauses to compile comprehensive transaction tables matching student records with assigned event items.

---

## 📁 Architecture & File Structure

```text
├── db.php               # MySQLi centralized connection configuration file
├── index.php            # Main hub dashboard UI featuring modular navigation routes
├── students.php         # Student onboarding control form and real-time records grid
├── events.php           # Event planner form panel and event timeline tracking log
└── registrations.php    # Mapping dashboard tying specific students to upcoming events
```

---

## 📊 Relational Database Schema (`studenthub`)

The application structure runs on a dedicated database schema tracking three relational entities:

```text
   ┌────────────────┐          ┌───────────────────────┐          ┌───────────────┐
   │    students    │          │     registrations     │          │    events     │
   ├────────────────┤          ├───────────────────────┤          ├───────────────┤
   │ PK student_id  │◄─────────┤ FK student_id         │          │ PK event_id   │
   │    name        │          │ FK event_id           │─────────►│    event_name │
   │    email       │          │    registration_date  │          │    ...        │
   └────────────────┘          └───────────────────────┘          └───────────────┘
```

### Table Profiles
1. **`students`**: Holds contact references, unique email IDs, and assigned core semesters.
2. **`events`**: Records the titles, calendar timelines, structural descriptions, and venue locations for upcoming programmes.
3. **`registrations`**: A tracking matrix resolving many-to-many relationship maps using constraints referencing `students` and `events` rows.

---

## 💻 Setup and Installation

### 1. Database Setup
1. Launch your local administration control dashboard (e.g., **phpMyAdmin** or terminal MySQL interface).
2. Create the working database container and build out the relational tables using the raw initialization code below:

```sql
CREATE DATABASE IF NOT EXISTS studenthub;
USE studenthub;

CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(15),
    course VARCHAR(100),
    semester INT
);

CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    location VARCHAR(150),
    description TEXT
);

CREATE TABLE IF NOT EXISTS registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    event_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (event_id) REFERENCES events(event_id)
);

-- Seed Initial Mock Records
INSERT INTO students (name, email, mobile, course, semester) VALUES
('Vedant Mevada', 'vedant@gmail.com', '9876543210', 'B.Tech', 1),
('Rahul Patel', 'rahul@gmail.com', '9876543211', 'B.Tech', 2);

INSERT INTO events (event_name, event_date, location, description) VALUES
('Student Hackathon', '2026-11-15', 'DEPSTAR', 'College coding event'),
('Technical Workshop', '2026-11-25', 'Seminar Hall', 'Web development workshop');
```

### 2. Application Deployment
1. Move the application module files (`index.php`, `db.php`, `students.php`, `events.php`, `registrations.php`) into your active web-server routing folder (such as XAMPP's `htdocs/studenthub/`).
2. Adjust access parameters inside `db.php` if your local environment utilizes non-standard credentials:
   ```php
   \$servername = "localhost";
   \$username = "root";
   \$password = ""; // Specify your database password here if necessary
   \$database = "studenthub";
   ```
3. Start the **Apache** and **MySQL** modules inside your local framework manager.
4. Launch your web browser and test the active connection configuration via:
   ```url
   http://localhost/studenthub/index.php