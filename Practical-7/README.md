# Secure Student Registration System

A lightweight, secure **PHP web application** that validates student registration details and persistently stores data across both **CSV** and **JSON** file repositories.

## 🚀 Features

- **Input Sanitization:** Uses `htmlspecialchars()` to mitigate Cross-Site Scripting (XSS) risks.
- **Server-Side Validation:** Ensures required inputs, evaluates email syntax, forces 10-digit mobile limits, and verifies minimal password thresholds.
- **Cryptographic Security:** Hashes passwords via `PASSWORD_DEFAULT` before serialization.
- **Dual-Engine Storage:** Records submissions concurrently inside a tabular CSV and a formatted JSON store.
- **Concurrent File Locking:** Employs `LOCK_EX` parameters while appending JSON payloads to counter race conditions.
- **Context-Aware Feedback:** Displays custom success status tags or itemised lists highlighting structural submission faults directly in the layout.

---

## 📁 File Structure

```text
├── index.php            # Combined frontend interface layout and backend processing routine
├── registrations.csv    # Flat-file database appending registered entry items sequentially
└── registrations.json   # Multi-dimensional object store preserving historical signups
```

---

## 🛠️ Validation Specifications

The backend handles the following strict layout parameters during incoming requests:

| Target Field | Core Evaluation Profile | Fail Response Alert Message |
| :--- | :--- | :--- |
| **Full Name** | Must not be empty strings. | "Name is required." |
| **Email Address** | Must pass standard structure validation filters. | "Enter a valid email address." |
| **Mobile Number** | Must span exactly 10 sequential numeric digits. | "Mobile number must contain exactly 10 digits." |
| **Password** | Requires a minimum character length of `6`. | "Password must contain at least 6 characters." |
| **Confirm Password** | Matrix match testing against original password entry. | "Password and Confirm Password do not match." |
| **Course** | Space-trimmed character values cannot be left empty. | "Course is required." |

---

## 💻 Setup and Installation

### Prerequisites
- A PHP local environment utility framework installed (**XAMPP**, **WAMP**, **MAMP**, or standard PHP CLI).

### Execution Steps

1. **Clone or Copy files:** Move `index.php` into your local server web directory (e.g., `htdocs/` for XAMPP).
2. **Launch Environment Server:** Start your Apache utility modules using your control interface dashboard.
3. **Verify File Permissions:** Ensure the execution directory grants appropriate write access so the backend can generate and append to storage files seamlessly.
4. **Access UI Web Interface:** Open your browser and navigate to:
   ```url
   http://localhost/index.php
   ```

---

## 📊 Data Preview Formats

### CSV Engine Format (`registrations.csv`)
```csv
Name,Email,Mobile,"Password Hash",Course
"Rohit Sharma",rohitsharma@gmail.com,9876543219,$2y$10$ClSUx5tvic1G...,B.Tech.
```

### JSON Engine Format (`registrations.json`)
```json
[
    {
        "name": "Rohit Sharma",
        "email": "rohitsharma@gmail.com",
        "mobile": "9876543219",
        "password": "\$2y\$10\$ClSUx5tvic1G...",
        "course": "B.Tech."
    }
]
```
