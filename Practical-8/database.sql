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

    FOREIGN KEY (student_id)
    REFERENCES students(student_id),

    FOREIGN KEY (event_id)
    REFERENCES events(event_id)
);

INSERT INTO students
(name, email, mobile, course, semester)
VALUES
('Vedant Mevada', 'vedant@gmail.com', '9876543210', 'B.Tech', 1),
('Rahul Patel', 'rahul@gmail.com', '9876543211', 'B.Tech', 2);

INSERT INTO events
(event_name, event_date, location, description)
VALUES
('Student Hackathon', '2026-11-15', 'DEPSTAR', 'College coding event'),
('Technical Workshop', '2026-11-25', 'Seminar Hall', 'Web development workshop');