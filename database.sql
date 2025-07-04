CREATE DATABASE codenest;
USE codenest;

CREATE TABLE accounts (
    account_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user', 'instructor') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users_profiles (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    gender ENUM('male', 'female'),
    birthdate DATE,
    phone_number VARCHAR(20),
    bio TEXT,
    profile_picture VARCHAR(255),
    location VARCHAR(100),
    FOREIGN KEY (account_id) REFERENCES accounts(account_id)
);
