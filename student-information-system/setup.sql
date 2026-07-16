-- Create database and user
CREATE DATABASE IF NOT EXISTS sis;
USE sis;

CREATE USER IF NOT EXISTS 'ali'@'localhost' IDENTIFIED BY 'ali123';
GRANT ALL PRIVILEGES ON sis.* TO 'ali'@'localhost';
FLUSH PRIVILEGES;

-- Users table (one table for both students and teachers)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(50) NOT NULL,   -- plaintext for demo
    email VARCHAR(100),
    role ENUM('student','teacher') DEFAULT 'student',
    full_name VARCHAR(100),
    address TEXT,
    -- Student specific
    course VARCHAR(100),
    result DECIMAL(5,2),
    attendance INT,                -- percentage
    academic_records TEXT,         -- JSON or free text
    -- Teacher specific
    department VARCHAR(100),
    course_teaches VARCHAR(100)
);

-- Demo data
INSERT INTO users (username, password, email, role, full_name, address, course, result, attendance, academic_records, department, course_teaches) VALUES
('teacher', 'teacher123', 'teacher@school.edu', 'teacher', 'Prof. Smith', '123 Faculty Lane', NULL, NULL, NULL, NULL, 'Computer Science', 'Web Security'),
('alice', 'alice123', 'alice@example.com', 'student', 'Alice Wonder', '456 Student St', 'Computer Science', 85.5, 92, 'A student, passed all exams.', NULL, NULL),
('bob', 'bob123', 'bob@example.com', 'student', 'Bob Builder', '789 Builder Ave', 'Mathematics', 72.0, 78, 'Struggling with calculus.', NULL, NULL),
('charlie', 'charlie123', 'charlie@example.com', 'student', 'Charlie Brown', '101 Peanut St', 'Physics', 90.0, 95, 'Top performer in lab.', NULL, NULL),
('diana', 'diana123', 'diana@example.com', 'student', 'Diana Prince', '202 Themyscira', 'Chemistry', 65.0, 70, 'Needs improvement in organic.', NULL, NULL);

-- Comments table for Stored XSS
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    comment TEXT,
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sample comments (safe)
INSERT INTO comments (user_id, comment) VALUES
(2, 'Good luck everyone with the finals!'),
(3, 'Has anyone seen the new syllabus?');