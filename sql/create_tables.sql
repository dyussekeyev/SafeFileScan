-- Create the database
CREATE DATABASE IF NOT EXISTS safefilescan_db;
USE safefilescan_db;

-- Create tables
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    date_last_logon DATETIME NOT NULL
);

CREATE TABLE avs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    api_key VARCHAR(255)
);

CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hash_md5 VARCHAR(32) NOT NULL,
    hash_sha1 VARCHAR(40) NOT NULL,
    hash_sha256 VARCHAR(64) NOT NULL,
    size INT NOT NULL,
    file_type VARCHAR(255),
    date_first_upload DATETIME NOT NULL
);

CREATE TABLE scans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_id INT NOT NULL,
    av_id INT NOT NULL,
    verdict VARCHAR(255),
    date_scan DATETIME NOT NULL,
    FOREIGN KEY (file_id) REFERENCES files(id),
    FOREIGN KEY (av_id) REFERENCES avs(id)
);

-- Create default admins
INSERT INTO admins (username, password, date_last_logon) VALUES ('superadmin', '$2y$10$iWwEF8cr0VCJvsx/2fcWoeKou4w99HAwlqTYp7rUI3b3LWNyURNZG', NOW());

-- Create default avs
INSERT INTO avs (name, api_key) VALUES ('Dummy', 'dummykey');
