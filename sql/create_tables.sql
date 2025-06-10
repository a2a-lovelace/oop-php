CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) DEFAULT NULL,
    email VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    fio VARCHAR(255) DEFAULT NULL,
    role VARCHAR(50) DEFAULT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE departments (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE users_departments (
    post_id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) DEFAULT NULL,
    department_id INT(11) DEFAULT NULL,
    post VARCHAR(50) DEFAULT NULL,
    PRIMARY KEY (post_id),
    FOREIGN KEY (user_id) REFERENCES users(id) 
        ON UPDATE RESTRICT 
        ON DELETE RESTRICT,
    FOREIGN KEY (department_id) REFERENCES departments(id) 
        ON UPDATE RESTRICT 
        ON DELETE RESTRICT
);
