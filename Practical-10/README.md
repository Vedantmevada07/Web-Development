# Secure Role-Based Authentication & Session Management System

A production-grade, highly secure **PHP and MySQL** authentication system featuring role-based access control (RBAC), strict session lifecycle management, and a cryptographically secure **"Remember Me" split-token pattern**.

---

## 🚀 Enterprise Security Features

- **Split-Token "Remember Me" Pattern:** Mitigates timing attacks and database leak hazards using a dual `selector` (database lookup) and `validator` (hashed signature verification via `hash_equals()`) pattern.
- **Strict Session Isolation:** Employs explicit hardening directives including `session.use_strict_mode`, `httponly`, `secure` execution triggers, and `samesite=Lax` cookie bindings.
- **Automated Lifecycle Expiry:** Forces clean garbage collection and redirects when continuous inactivity passes a hard **15-minute (`900` seconds)** threshold.
- **Access Control Enforcement (RBAC):** Restricts route access by verifying authenticated roles (`admin` vs `user`), cleanly rejecting bad actors with standard `403 Forbidden` response states.
- **Cryptographic Security Layers:** Leverages native `password_hash()` options using `PASSWORD_DEFAULT` along with secure random byte engines (`random_bytes()`).
- **XSS Vector Defenses:** Escapes variable streams using `htmlspecialchars()` contexts across all front-facing administrative rendering grids.

---

## 📁 System Architecture & Directory Tree

```text
├── db.php               # MySQLi centralized data layer configuration context
├── auth.php             # Core session manager, cookie parsers, and gatekeeping checks
├── login.php            # Entry terminal implementing credentials validation and token binding
├── register.php         # User onboarding engine featuring standard validation pipelines
├── logout.php           # Token deletion, cookie invalidation, and session demolition routing
├── dashboard.php        # Protected portal page restricted to baseline authenticated Users
├── admin.php            # Protected gateway terminal reserved explicitly for Administrators
├── view_users.php       # Enterprise user inventory manifest restricted exclusively to Admin contexts
└── verify_cookie.php    # Cryptographic testing module analyzing active split-token signatures
```

---

## 📊 Database Configurations (`practical10`)

Set up your relational data layer framework inside your management terminal (**phpMyAdmin**) using the following clean SQL build scripts:

```sql
CREATE DATABASE IF NOT EXISTS practical10;
USE practical10;

-- 1. Core Accounts Directory
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Remember-Me Persistent Session Registry
CREATE TABLE IF NOT EXISTS remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    selector CHAR(24) NOT NULL UNIQUE,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Seed an Administrative account for system testing (Password: admin123)
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@studenthub.edu', '$2y$10$7rK6V79M8pBwH.tVux4ApepMZ.kXU0kXFexG1E1g7K9eM8y6sWb6.', 'admin');
```

---

## 🔐 Cryptographic Mechanism Exploded

Instead of storing raw text identifiers on a user's machine, this app structures persistent log-ins into distinct data slices:
1. **Cookie Structural Pattern:** `selector (24 hex characters) : validator (64 hex characters)`
2. **Database Record Indexing:** Only the `selector` and a secure **SHA-256 hash** of the `validator` are registered into the `remember_tokens` cache table.
3. **Validation Strategy:** The app retrieves hashes using the static selector string, matching signatures inside constant-time `hash_equals()` validation steps. If a database compromise occurs, interceptors cannot reverse token records into legitimate user login access vectors.

---

## 💻 Setup and Installation Instructions

### Prerequisites
- Development stack containing **PHP 7.4+** and **MySQL Server** (e.g., **XAMPP**, **WAMP**, or **MAMP**).

### Deployment Sequence

1. **Deploy Repository Components:** Copy your PHP workspace scripts to your runtime server ecosystem folder (e.g., `C:/xampp/htdocs/practical10/`).
2. **Database Verification:** Execute the initialization SQL schema provided above to build and seed your tracking architecture tables.
3. **Verify Connection Matrix:** Review configuration fields inside `db.php` to match local access policies:
   ```php
   \$conn = new mysqli("localhost", "root", "", "practical10");
   ```
4. **Boot Server:** Activate your environment's **Apache** and **MySQL** server containers via your control interface.
5. **Access Application:** Open your browser and navigate to:
   ```url
   http://localhost/practical10/login.php
   ```
