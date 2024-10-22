-- Create the database
CREATE DATABASE IF NOT EXISTS safefilescan_db;
USE safefilescan_db;

-- Create tables
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'superadmin') NOT NULL,
    privileges TEXT
);

CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hash_md5 VARCHAR(32) NOT NULL,
    hash_sha1 VARCHAR(40) NOT NULL,
    hash_sha256 VARCHAR(64) NOT NULL,
    size INT NOT NULL,
    file_type VARCHAR(255),
    date_first_upload DATETIME NOT NULL,
    date_last_analysis DATETIME NOT NULL
);

CREATE TABLE scan_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_id INT NOT NULL,
    verdict_kaspersky VARCHAR(255),
    verdict_trendmicro VARCHAR(255),
    verdict_eset VARCHAR(255),
    date_scan DATETIME NOT NULL,
    FOREIGN KEY (file_id) REFERENCES files(id)
);

CREATE TABLE event_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_type VARCHAR(50) NOT NULL,
    event_description TEXT NOT NULL,
    event_date DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create default users
INSERT INTO users (username, password, role) VALUES ('superadmin', 'password', 'superadmin');
INSERT INTO users (username, password, role) VALUES ('admin', 'password', 'admin');
