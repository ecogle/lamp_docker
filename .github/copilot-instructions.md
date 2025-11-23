# Copilot Instructions for This Codebase

## Overview
This project is a simple PHP web application running in Podman, with MySQL as the database and phpMyAdmin for DB management. The architecture is designed for local development and testing using Podman Compose.

## Architecture
- **Web Service**: PHP 8.2 with Apache, source code in `src/`, served at [http://localhost:8080](http://localhost:8080).
- **Database**: MySQL 8.0, data persisted in a Podman volume (`db_data`).
- **phpMyAdmin**: Accessible at [http://localhost:8000](http://localhost:8000) for DB management.
- **Source Code**: All PHP files are in `src/`. Example files:
  - `index.php`: Shows PHP info (default landing page).
  - `db_test.php`: Tests MySQL connection using PDO.

## Developer Workflows
- **Start/Stop Services**: Use `podman-compose up -d` and `podman-compose down`.
- **Rebuild Web Service**: Use `podman-compose build web` if you change the `Dockerfile` or PHP extensions.
- **Accessing the App**: Visit [http://localhost:8080](http://localhost:8080) in your browser.
- **Database Access**: Use phpMyAdmin at [http://localhost:8000](http://localhost:8000) (login as `root`/`exampleRootPass`).

## Project Conventions
- **Database Host**: Use `db` as the MySQL host in PHP (matches Podman Compose service name).
- **Credentials**: Set in `docker-compose.yml` under the `db` service.
- **Volumes**: `src/` is mounted into the container for live code updates.
- **PHP Extensions**: `mysqli`, `pdo`, and `pdo_mysql` are installed by default.
- **No Framework**: This is a plain PHP project, no frameworks or routing libraries are used.

## Integration Points
- **PHP ↔ MySQL**: Use PDO for database access. See `db_test.php` for connection pattern.
- **phpMyAdmin**: Connects to the same MySQL container for easy DB inspection.

## Example: Database Connection
```php
$host = 'db';
$db   = 'exampledb';
$user = 'root';
$pass = 'exampleRootPass';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass);
```

## Key Files
- `docker-compose.yml`: Service definitions and environment variables (used by Podman Compose)
- `Dockerfile`: PHP/Apache setup
- `src/`: Application source code

---
_If you add new services, PHP extensions, or change DB credentials, update this file and the relevant config files._
