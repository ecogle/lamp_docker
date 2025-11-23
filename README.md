# Simple PHP + MySQL + Podman Example

This project is a minimal PHP web application running in Podman containers, with MySQL as the database and phpMyAdmin for database management. It is designed for local development and testing using Podman Compose.

## Features
- PHP 8.2 with Apache web server
- MySQL 8.0 database (data persisted in a Podman volume)
- phpMyAdmin for easy DB management
- Live code updates via volume mounting

## Getting Started

### Prerequisites
- [Podman](https://podman.io/) and [Podman Compose](https://github.com/containers/podman-compose) installed

### Quick Start
1. **Start all services:**
   ```sh
   podman-compose up -d
   ```
2. **Access the web app:**
   - [http://localhost:8080](http://localhost:8080) (shows PHP info by default)
3. **Access phpMyAdmin:**
   - [http://localhost:8000](http://localhost:8000)
   - Login: `root` / `exampleRootPass`
4. **Test database connection:**
   - Visit [http://localhost:8080/db_test.php](http://localhost:8080/db_test.php)

### Stopping Services
```sh
podman-compose down
```

### Rebuilding the Web Service
If you change the `Dockerfile` or PHP extensions:
```sh
podman-compose build web
```

## Project Structure
```
├── docker-compose.yml      # Podman Compose service definitions
├── Dockerfile              # PHP/Apache setup
├── src/
│   ├── index.php           # Default landing page (phpinfo)
│   └── db_test.php         # MySQL connection test
```

## Database Connection Example
```php
$host = 'db';
$db   = 'exampledb';
$user = 'root';
$pass = 'exampleRootPass';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass);
```

## Credentials & Conventions
- MySQL host: `db` (matches service name)
- Credentials: set in `docker-compose.yml`
- PHP extensions: `mysqli`, `pdo`, `pdo_mysql` enabled
- No PHP framework used

## Troubleshooting
- If you can't connect to MySQL, check the logs:
  ```sh
  podman-compose logs db
  ```
- For live code updates, ensure the `src/` directory is mounted as a volume.

---
_If you add new services, PHP extensions, or change DB credentials, update this README and `.github/copilot-instructions.md`._
