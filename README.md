# SafeFileScan

SafeFileScan is an awkward parody of VirusTotal, created to scan files for potential threats. This project is entirely written in PHP.

## Table of Contents
- [Description](#description)
- [Structure](#structure)
- [Installation](#installation)
- [Usage](#usage)
- [Admin and Superadmin](#admin-and-superadmin)
- [Includes](#includes)
- [Logs and Uploads](#logs-and-uploads)
- [Contribution](#contribution)
- [License](#license)

## Description

SafeFileScan is a PHP-based web application designed to scan files for potential threats. It aims to provide basic file scanning functionalities similar to VirusTotal, but in a simplified and educational context.

## Structure
```
SafeFileScan/
├── index.php
├── search.php
├── upload.php
├── admin/
│ ├── index.php
├── api/
│ ├── get_file.php
│ ├── get_scans.php
│ ├── put_scan.php
├── css/
│ ├── styles.css
├── includes/
│ ├── db.php
│ ├── functions.php
└── sql/
│ ├── create_tables.sql
```

## Installation

1. Install necessary dependencies (e.g., PHP, MySQL).
2. Set up /etc/apache2/sites-enabled/000-default.conf by changing `DocumentRoot /var/www/html` to `DocumentRoot /var/www/SafeFileScan`
3. Go to `/var/www` and clone the repository:
   
    ```
    git clone https://github.com/dyussekeyev/SafeFileScan.git
    ```

5. Set up the database by running the SQL script located in `sql/create_tables.sql`.

## Usage

1. Open your web browser and navigate to the project's root directory.
2. Use the provided pages to upload and scan files:
   - `index.php`: Main page to upload and scan files.
   - `search.php`: Search for previous scans.
   - `upload.php`: Upload files.

## Admin

Admin pages are located in the `admin/` directory, which includes functionalities for managing scans and files.

## Includes

The `includes/` directory contains:
- `db.php`: Database connection and queries.
- `functions.php`: General functions used throughout the project.

## Contribution

Contributions are not welcome.

## License

This project is licensed under the GPL-3.0 License. See the [LICENSE](LICENSE) file for details.
