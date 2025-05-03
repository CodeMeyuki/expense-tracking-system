-- Create the database
CREATE DATABASE db_batch5_ets;

-- Use the created database
USE db_batch5_ets;

-- Create users table
CREATE TABLE tbl_users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM ('admin', 'customer')DEFAULT 'customer'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO tbl_users(username, email, password)
VALUES
    ('admin','admin@admin.com','123456');

CREATE TABLE tbl_categories(
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100)
);

CREATE TABLE tbl_expenses(
    expense_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    category_id INT,
    amount DECIMAl(5,2),
    expense_description TEXT,
    expense_date DATE  
);

CREATE TABLE tbl_budgets(
    budget_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    month VARCHAR,
    year INT,
    amount_limit DECIMAl(5,2) 
);



CREATE TABLE AccessLogs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(255),
    log_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);