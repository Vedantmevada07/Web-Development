# Secure User Registration System

A web-based portal developed in **PHP** and backed by a relational **MySQL** database that securely processes user registration credentials, tracks duplicate entries, and displays an index of registered members.

---

## 🚀 Features

- **Double-Layer Validation:** Implements frontend HTML5 attribute enforcement (`pattern`, `minlength`, `maxlength`) coupled with server-side backup checks.
- **Anti-SQL Injection Security:** Uses parameterized prepared statements via `$conn->prepare()` to handle all data execution securely.
- **Cryptographic Hashing:** Safely hashes user passwords with the default industry standard `PASSWORD_DEFAULT` strategy before storing records.
- **Duplicate Entry Filters:** Automatically scans existing usernames and emails prior to executing the registration transaction to ensure identity consistency.
- **XSS Mitigation:** Wraps tabular database display queries inside `htmlspecialchars()` to cleanly escape user-generated string structures.
- **Character Encoding Stability:** Explicitly forces data channel handling on a `utf8mb4` character mapping rule to prevent binary string collisions.

---

## 📁 Project Architecture & Files

```text
├── db.php          # Database initialization and connection pool configuration
├── register.php    # Form control layout and secure execution routine handler
└── users.php       # Analytical user data grid listing historical registrations
```

---

## 📊 Database Configuration (`user_registration`)

### SQL Schema Deployment
Execute the following query array in your database administration terminal (or **phpMyAdmin**) to establish the target ecosystem:

```sql
CREATE DATABASE IF NOT EXISTS user_registration;
USE user_registration;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

---

## 💻 Local Installation and Setup

### Prerequisites
- A local web server suite with **PHP 7.4+** and **MySQL** compatibility (such as **XAMPP**, **WAMP**, **MAMP**, or a native **LAMP** stack stack setup).

### Deployment Guidelines

1. **Relocate Project Folder:** Clone or place your scripts (`db.php`, `register.php`, `users.php`) into your local server web folder path (e.g., `C:/xampp/htdocs/user_registration/`).
2. **Review Credentials:** Open `db.php` and verify that the connection fields reflect your environmental parameters:
   ```php
   \$servername = "localhost";
   \$username = "root";
   \$password = ""; // Append database password credential if needed
   \$database = "user_registration";
3. **Boot Environment:** Activate your **Apache** and **MySQL** modules from your development suite management dashboard.
4. **Access Application Portal:** Open your preferred browser app and visit the landing console via:
   url
   http://localhost/user_registration/register.php
