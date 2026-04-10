CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255)NOT NULL UNIQUE,
    pass_word VARCHAR(255)NOT NULL,
    role ENUM('admin', 'teacher', 'student')
);

CREATE TABLE Students(
    id INT PRIMARY KEY AUTO_INCREMENT ,
    matricule INT UNIQUE,
    surname VARCHAR(255) NOT NULL,
    family_name VARCHAR(255) NOT NULL,
    email  VARCHAR(255) UNIQUE NOT NULL,
    lvl INT,
    birth_date DATE,
    user_id INT,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE Teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    user_id INT UNIQUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE Modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    coefficient INT NOT NULL DEFAULT 1,
    teacher_id INT,
    FOREIGN KEY (teacher_id) REFERENCES Teachers(id) ON DELETE SET NULL
);

CREATE TABLE Grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    module_id INT NOT NULL,
    grade FLOAT NOT NULL CHECK (grade >= 0 AND grade <= 20),
    FOREIGN KEY (student_id) REFERENCES Students(id) ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES Modules(id) ON DELETE CASCADE,
    UNIQUE(student_id, module_id)
);