
CREATE DATABASE IF NOT EXISTS practical14;
USE practical14;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    course VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    location VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    event_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id)
        ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id)
        ON DELETE CASCADE
);

-- Demo admin account
-- Username: admin
-- Password: password
INSERT IGNORE INTO users (username, password, role)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin'
);

-- Demo student account
-- Username: student
-- Password: password
INSERT IGNORE INTO users (username, password, role)
VALUES (
    'student',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'student'
);

INSERT IGNORE INTO students (id, name, email, course) VALUES
(1, 'Aarav Patel', 'aarav@example.com', 'Computer Engineering'),
(2, 'Diya Shah', 'diya@example.com', 'Information Technology'),
(3, 'Krish Mehta', 'krish@example.com', 'Computer Engineering');

INSERT IGNORE INTO events (id, event_name, event_date, location) VALUES
(1, 'Web Development Workshop', '2026-11-10', 'Seminar Hall'),
(2, 'Technical Quiz', '2026-11-15', 'Computer Lab');

INSERT INTO registrations (student_id, event_id)
SELECT 1, 1
WHERE NOT EXISTS (
    SELECT 1 FROM registrations
    WHERE student_id = 1 AND event_id = 1
);

INSERT INTO registrations (student_id, event_id)
SELECT 2, 2
WHERE NOT EXISTS (
    SELECT 1 FROM registrations
    WHERE student_id = 2 AND event_id = 2
);
