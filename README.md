## Description

SafeFileScan is an awkward parody of VirusTotal, created to scan files for potential threats. This project is entirely written in PHP.

# Structure
```
SafeFileScan/
├── index.php
├── upload.php
├── search.php
├── rescan.php
├── admin/
│   ├── index.php
│   ├── upload.php
│   ├── delete_scan.php
│   ├── delete_file.php
├── superadmin/
│   ├── index.php
│   ├── manage_admins.php
│   ├── view_logs.php
├── includes/
│   ├── db.php
│   ├── functions.php
├── logs/
├── uploads/
└── sql/
    ├── create_tables.sql
```

## Installation

1. Clone the repository: 
    ```sh
    git clone https://github.com/dyussekeyev/SafeFileScan.git
    ```
2. Navigate to the project directory:
    ```sh
    cd SafeFileScan
    ```
3. Set up the database by running the SQL script located in `sql/create_tables.sql`.

## Usage

1. Open your web browser and navigate to the project's root directory.
2. Use the provided pages to upload and scan files:
   - `index.php`: Main page to upload and scan files.
   - `search.php`: Search for previous scans.
   - `rescan.php`: Rescan files.

## Admin and Superadmin

- Admin pages are located in the `admin/` directory, which includes functionalities for managing scans and files.
- Superadmin pages are located in the `superadmin/` directory, including management of admins and viewing logs.

## Includes

The `includes/` directory contains:
- `db.php`: Database connection and queries.
- `functions.php`: General functions used throughout the project.

## Logs and Uploads

- `logs/`: Directory for storing logs.
- `uploads/`: Directory for storing uploaded files.

## License

This project is licensed under the License. See the [LICENSE](LICENSE) file for details.
