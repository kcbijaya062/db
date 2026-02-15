-- Table for students
drop table Students ;
CREATE TABLE Students (
    student_id VARCHAR(50) PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    age INT,
    address VARCHAR(255),
    student_type VARCHAR(20) CHECK (student_type IN ('undergraduate', 'graduate')) NOT NULL,
    status_on_probation CHAR(1) CHECK (status_on_probation IN ('Y', 'N'))
);

-- Table for undergraduate students
drop table Undergraduates cascade constraints;
CREATE TABLE Undergraduates (
    student_id VARCHAR(50) PRIMARY KEY,
    standing VARCHAR(255),
    FOREIGN KEY (student_id) REFERENCES Students(student_id)
);

-- Table for graduate students
drop table Graduates cascade constraints;
CREATE TABLE Graduates (
    student_id VARCHAR(50) PRIMARY KEY,
    concentration VARCHAR(255),
    FOREIGN KEY (student_id) REFERENCES Students(student_id)
);

-- Table for courses
drop table Course cascade constraints;
CREATE TABLE Course (
    course_number VARCHAR(50) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    credit_hours INT NOT NULL
);

-- Table for sections
drop TABLE Sections cascade constraints ;
CREATE TABLE Sections (
    section_id INT PRIMARY KEY,
    course_number VARCHAR(50),
    section_time VARCHAR(255),
    semester_season VARCHAR(255),
    semester_year INT,
    date_of_enrollment_deadline DATE,
    section_date DATE, 
    capacity INT,
    FOREIGN KEY (course_number) REFERENCES Course(course_number)
);



-- many-to-many relationship for Courses
drop TABLE Prerequisites;
CREATE TABLE Prerequisites (
    course_number VARCHAR(50),
    prerequisite_number VARCHAR(50),
    PRIMARY KEY (course_number, prerequisite_number),
    FOREIGN KEY (course_number) REFERENCES Course(course_number),
    FOREIGN KEY (prerequisite_number) REFERENCES Course(course_number)
);

-- many-to-many relationship
  Drop TABLE Enroll;
CREATE TABLE Enroll (
    student_id VARCHAR(9),
    section_id INT,
    grade VARCHAR(2),
    PRIMARY KEY (student_id, section_id),
    FOREIGN KEY (student_id) REFERENCES Students(student_id),
    FOREIGN KEY (section_id) REFERENCES Sections(section_id)
);



--data insertions
INSERT INTO Students (student_id, first_name, last_name, age, address, student_type, status_on_probation) VALUES
('01', 'Ali', 'baba', 20, '123 Maple Street', 'undergraduate', 'N');


INSERT INTO Students (student_id, first_name, last_name, age, address, student_type, status_on_probation) VALUES
('02', 'Bob', 'kc', 24, '456 Oak Avenue', 'graduate', 'Y');

INSERT INTO Undergraduates (student_id, standing) VALUES
('01', 'Senior');

INSERT INTO Graduates (student_id, concentration) VALUES
('02', 'Computer Science');

INSERT INTO Course (course_number, title, credit_hours) VALUES
('C01', 'Introduction to Programming', 3);

INSERT INTO Course (course_number, title, credit_hours) VALUES('C02', 'Data Structures', 4);

INSERT INTO Sections (section_id, course_number, section_time, semester_season, semester_year, date_of_enrollment_deadline,section_date ,capacity) VALUES
(1, 'C01', 'TR 9:00-10:30', 'Fall', 2023, TO_DATE('2023-08-01', 'YYYY-MM-DD'),TO_DATE('2023-08-14', 'YYYY-MM-DD'),30);


INSERT INTO Sections (section_id, course_number, section_time, semester_season, semester_year, date_of_enrollment_deadline,section_date, capacity) VALUES
(2, 'C02', 'MW 10:00-11:30', 'Spring', 2024, TO_DATE('2024-08-01', 'YYYY-MM-DD'),TO_DATE('2024-08-15', 'YYYY-MM-DD'), 25);

INSERT INTO Prerequisites (course_number, prerequisite_number) VALUES
('C02', 'C01');

INSERT INTO Enroll (student_id, section_id, grade) VALUES
('01', 1, 'A');
INSERT INTO Enroll (student_id, section_id, grade) VALUES
('02', 2, 'A');
