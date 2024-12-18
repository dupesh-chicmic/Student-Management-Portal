SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS student_management;

USE student_management;

CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL
);

INSERT INTO courses (course_name) VALUES
('Math'),
('Science'),
('English');

CREATE TABLE IF NOT EXISTS topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_name VARCHAR(255) NOT NULL,
    course_id INT,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

INSERT INTO topics (topic_name, course_id) VALUES
('Introduction to Math', 1),
('Basic Algebra', 1),
('Physics Basics', 2),
('Biology Basics', 2),
('Shakespeare', 3);

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(190) UNIQUE NOT NULL,
    phone VARCHAR(20),
    dob DATE
);

INSERT INTO students (file, first_name, last_name, email, phone, dob) VALUES
('images.jpeg', 'John', 'Doe', 'john.doe@example.com', '1234567890', '2000-01-15'),
('pexels-tesla-lee-1304809-3857400.jpg', 'Jane', 'Smith', 'jane.smith@example.com', '1235557890', '1998-12-25'),
('Kohli2.png', 'Emma', 'Jones', 'emma.jones@example.com', '1234447890', '2001-03-30');


CREATE TABLE IF NOT EXISTS students_courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    is_completed BOOLEAN DEFAULT FALSE,
    FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY(course_id) REFERENCES courses(id) ON DELETE CASCADE
);

INSERT INTO students_courses (student_id, course_id) VALUES (1,2), (2,2), (2,3), (3,1);

CREATE TABLE IF NOT EXISTS students_topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    topic_id INT,
    is_completed BOOLEAN DEFAULT FALSE,
    FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY(topic_id) REFERENCES topics(id) ON DELETE CASCADE
);

INSERT INTO students_topics (student_id, topic_id) VALUES (1,3), (1,4), (2,3), (2,4), (2,5), (3,1), (3,2);