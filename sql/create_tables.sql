CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'superadmin') NOT NULL,
    privileges TEXT
);

CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    md5 VARCHAR(32) NOT NULL,
    sha1 VARCHAR(40) NOT NULL,
    sha256 VARCHAR(64) NOT NULL,
    imphash VARCHAR(64),
    size INT NOT NULL,
    first_upload_date DATETIME NOT NULL,
    last_analysis_date DATETIME NOT NULL
);

CREATE TABLE scan_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_md5 VARCHAR(32) NOT NULL,
    antivirus_name VARCHAR(50) NOT NULL,
    scan_date DATETIME NOT NULL,
    result VARCHAR(255) NOT NULL
);

CREATE TABLE event_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_type VARCHAR(50) NOT NULL,
    event_description TEXT NOT NULL,
    event_date DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);