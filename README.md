# oop-php
OOP in PHP, semesters 1+2

## new version in new_folder
## sql scripts in sql folder
```sql
CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) DEFAULT NULL,
    email VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    fio VARCHAR(255) DEFAULT NULL,
    role VARCHAR(50) DEFAULT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE testdepartments (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE test_users_departments (
    post_id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) DEFAULT NULL,
    department_id INT(11) DEFAULT NULL,
    post VARCHAR(50) DEFAULT NULL,
    PRIMARY KEY (post_id),
    FOREIGN KEY (user_id) REFERENCES testusers(id) 
        ON UPDATE RESTRICT 
        ON DELETE RESTRICT,
    FOREIGN KEY (department_id) REFERENCES testdepartments(id) 
        ON UPDATE RESTRICT 
        ON DELETE RESTRICT
);
```
