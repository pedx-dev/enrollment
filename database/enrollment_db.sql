-- College Enrollment System Database Schema
-- Run this SQL file in phpMyAdmin to create the database

-- Create database
CREATE DATABASE IF NOT EXISTS enrollment_db;
USE enrollment_db;

-- Settings table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value) VALUES
('college_name', 'HOLYCROSSCORE'),
('college_code', '26'),
('next_student_number', '4'),
('academic_year', '2024-2025'),
('semester', 'First Semester');

-- Users table (for admin and teachers login)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    role ENUM('admin', 'teacher') NOT NULL DEFAULT 'teacher',
    department VARCHAR(255),
    specialization VARCHAR(255),
    phone VARCHAR(50),
    status ENUM('pending', 'active', 'inactive', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin and teacher accounts (password is 'password')
INSERT INTO users (email, password, name, role, department, specialization, phone, status) VALUES
('admin@college.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin', NULL, NULL, NULL, 'active'),
('john.smith@college.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Prof. John Smith', 'teacher', 'Computer Science', 'Data Science', '+63-2-8123-4567', 'active'),
('maria.garcia@college.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Maria Garcia', 'teacher', 'Business Administration', 'Finance', '+63-2-8123-4568', 'active'),
('michael.johnson@college.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Prof. Michael Johnson', 'teacher', 'Computer Science', 'Web Development', '+63-2-8123-4569', 'active');

-- Students table
CREATE TABLE IF NOT EXISTS students (
    id VARCHAR(20) PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    date_of_birth DATE,
    place_of_birth VARCHAR(255),
    sex ENUM('Male', 'Female', 'Other'),
    civil_status VARCHAR(50),
    nationality VARCHAR(100),
    religion VARCHAR(100),
    address TEXT,
    phone VARCHAR(50),
    email VARCHAR(255),
    guardian_name VARCHAR(255),
    guardian_phone VARCHAR(50),
    guardian_relationship VARCHAR(100),
    course VARCHAR(255),
    year_level VARCHAR(50),
    section VARCHAR(20),
    status ENUM('active', 'inactive', 'graduated', 'dropped') DEFAULT 'active',
    enrollment_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample students
INSERT INTO students (id, first_name, middle_name, last_name, date_of_birth, place_of_birth, sex, civil_status, nationality, religion, address, phone, email, guardian_name, guardian_phone, guardian_relationship, course, year_level, section, status, enrollment_date) VALUES
('26-00001', 'Maria', 'Grace', 'Santos', '2003-05-15', 'Manila', 'Female', 'Single', 'Filipino', 'Roman Catholic', '123 Main St, Quezon City, Metro Manila', '+63-9175551234', 'maria.santos@email.com', 'Juan Santos', '+63-9175555678', 'Father', 'Bachelor of Science in Computer Science', 'First Year', 'A', 'active', '2024-06-15'),
('26-00002', 'Juan', 'Miguel', 'Cruz', '2003-08-22', 'Cebu', 'Male', 'Single', 'Filipino', 'Roman Catholic', '456 Oak Ave, Cebu City, Cebu', '+63-9175552345', 'juan.cruz@email.com', 'Rosa Cruz', '+63-9175556789', 'Mother', 'Bachelor of Science in Information Technology', 'Second Year', 'B', 'active', '2023-06-20'),
('26-00003', 'Angela', 'Rose', 'Reyes', '2004-02-10', 'Davao', 'Female', 'Single', 'Filipino', 'Evangelical', '789 Pine Rd, Davao City, Davao', '+63-9175553456', 'angela.reyes@email.com', 'Fernando Reyes', '+63-9175557890', 'Father', 'Bachelor of Arts in Business Administration', 'First Year', 'A', 'active', '2024-06-18');

-- Courses table
CREATE TABLE IF NOT EXISTS courses (
    id VARCHAR(20) PRIMARY KEY,
    code VARCHAR(20) NOT NULL,
    name VARCHAR(255) NOT NULL,
    department VARCHAR(255),
    credits INT DEFAULT 3,
    description TEXT,
    semester VARCHAR(50),
    instructor_id INT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert sample courses
INSERT INTO courses (id, code, name, department, credits, description, semester, instructor_id) VALUES
('CS101', 'CS101', 'Introduction to Programming', 'Computer Science', 3, 'Fundamentals of programming using Python', 'First Semester', 2),
('CS103', 'CS103', 'Discrete Mathematics', 'Computer Science', 3, 'Logic, sets, functions, and combinatorics', 'First Semester', 2),
('CS105', 'CS105', 'Computer Organization', 'Computer Science', 3, 'CPU, memory, and low-level data representation', 'First Semester', 4),
('CS102', 'CS102', 'Web Development', 'Computer Science', 3, 'HTML, CSS, and JavaScript fundamentals', 'Second Semester', 4),
('CS201', 'CS201', 'Data Structures', 'Computer Science', 3, 'Arrays, lists, stacks, queues, trees, graphs', 'Second Semester', 2),
('CS203', 'CS203', 'Database Systems', 'Computer Science', 3, 'Relational databases and SQL', 'Second Semester', 4),
('BA101', 'BA101', 'Business Fundamentals', 'Business Administration', 3, 'Introduction to business concepts', 'First Semester', 3),
('BA102', 'BA102', 'Principles of Management', 'Business Administration', 3, 'Planning, organizing, leading, and controlling', 'First Semester', 3),
('BA202', 'BA202', 'Financial Management', 'Business Administration', 3, 'Budgeting, forecasting, and financial analysis', 'Second Semester', 3),
('IT101', 'IT101', 'Information Technology Essentials', 'Information Technology', 3, 'Basics of IT systems', 'First Semester', 2),
('IT102', 'IT102', 'Networking Fundamentals', 'Information Technology', 3, 'Network models, protocols, and routing', 'Second Semester', 2);

-- Enrollments table (student course registrations)
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL,
    course_id VARCHAR(20) NOT NULL,
    enrollment_date DATE NOT NULL,
    status ENUM('active', 'completed', 'dropped', 'pending') DEFAULT 'active',
    grade VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id)
);

-- Insert sample enrollments
INSERT INTO enrollments (student_id, course_id, enrollment_date, status) VALUES
('26-00001', 'CS101', '2024-06-15', 'active'),
('26-00001', 'CS102', '2024-06-15', 'active'),
('26-00001', 'CS103', '2024-06-15', 'active'),
('26-00002', 'CS101', '2023-06-20', 'completed'),
('26-00002', 'CS201', '2024-01-15', 'active'),
('26-00002', 'IT101', '2023-06-20', 'completed'),
('26-00003', 'BA101', '2024-06-18', 'active'),
('26-00003', 'BA102', '2024-06-18', 'active');

-- Attendance table (for teacher tracking)
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL,
    course_id VARCHAR(20) NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present', 'absent', 'late', 'excused') NOT NULL,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (student_id, course_id, attendance_date)
);

-- Create indexes for better performance
CREATE INDEX idx_students_status ON students(status);
CREATE INDEX idx_students_course ON students(course);
CREATE INDEX idx_enrollments_student ON enrollments(student_id);
CREATE INDEX idx_enrollments_course ON enrollments(course_id);
CREATE INDEX idx_attendance_date ON attendance(attendance_date);
CREATE INDEX idx_courses_department ON courses(department);
CREATE INDEX idx_users_role ON users(role);
