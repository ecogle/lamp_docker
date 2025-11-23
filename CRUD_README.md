# Task Manager CRUD Application

A complete PHP CRUD (Create, Read, Update, Delete) application for managing tasks, running on Podman with MySQL.

## Features

- ✅ **Create** new tasks with title, description, status, priority, and due date
- 📖 **Read** and display all tasks in a clean table view
- ✏️ **Update** existing tasks
- 🗑️ **Delete** tasks with confirmation
- 🎨 Modern, responsive UI
- 🔒 Prepared statements for SQL injection prevention

## Quick Start

### 1. Start the Services

```powershell
podman-compose up -d
```

### 2. Initialize the Database

Access phpMyAdmin at [http://localhost:8000](http://localhost:8000):
- Login as `root` / `exampleRootPass`
- Select the `exampledb` database
- Go to the SQL tab
- Copy and paste the contents of `src/schema.sql`
- Click "Go" to execute

Or use the command line:

```powershell
Get-Content src/schema.sql | podman exec -i docker-db-1 mysql -uroot -pexampleRootPass exampledb
```

### 3. Access the Application

Open your browser and visit:
- **Task Manager**: [http://localhost:8080/tasks.php](http://localhost:8080/tasks.php)

## Project Structure

```
src/
├── schema.sql          # Database DDL (table definitions)
├── db.php              # Database connection utility
├── tasks.php           # CRUD application (main interface)
├── db_test.php         # Database connection test
└── index.php           # PHP info page
```

## Database Schema

### Tasks Table

| Column      | Type                                    | Description                |
|-------------|-----------------------------------------|----------------------------|
| id          | INT (Primary Key, Auto Increment)       | Unique task identifier     |
| title       | VARCHAR(255)                            | Task title                 |
| description | TEXT                                    | Task description           |
| status      | ENUM('pending', 'in_progress', 'completed') | Task status          |
| priority    | ENUM('low', 'medium', 'high')           | Task priority              |
| due_date    | DATE                                    | Task due date              |
| created_at  | TIMESTAMP                               | Creation timestamp         |
| updated_at  | TIMESTAMP                               | Last update timestamp      |

## Files Overview

### schema.sql
Contains the DDL (Data Definition Language) to create the `tasks` table and insert sample data.

### db.php
Database utility functions:
- `getConnection()` - Returns PDO connection with proper configuration
- `query($sql, $params)` - Execute SELECT queries, return all rows
- `queryOne($sql, $params)` - Execute SELECT query, return single row
- `execute($sql, $params)` - Execute INSERT/UPDATE/DELETE queries

### tasks.php
Complete CRUD interface:
- **Create**: Form to add new tasks
- **Read**: Table displaying all tasks with formatted data
- **Update**: Edit form pre-filled with task data
- **Delete**: Delete button with confirmation

## Usage Examples

### Creating a Task
1. Fill in the form at the top of the page
2. Title is required; other fields are optional
3. Click "➕ Create Task"

### Editing a Task
1. Click "Edit" button next to any task
2. Form will populate with current data
3. Modify as needed and click "💾 Update Task"

### Deleting a Task
1. Click "Delete" button next to any task
2. Confirm the deletion in the prompt
3. Task will be removed from the database

## Database Connection Details

- **Host**: `db` (Podman Compose service name)
- **Database**: `exampledb`
- **Username**: `root`
- **Password**: `exampleRootPass`

These are configured in `db.php` and match the settings in `docker-compose.yml`.

## Security Features

- ✅ Prepared statements prevent SQL injection
- ✅ Output escaping with `htmlspecialchars()` prevents XSS
- ✅ Delete confirmation prevents accidental deletions
- ✅ PDO error mode set to exceptions for better error handling

## Stopping the Application

```powershell
podman-compose down
```

To remove the database volume as well:

```powershell
podman-compose down -v
```

## Troubleshooting

**Can't connect to database?**
- Ensure all services are running: `podman-compose ps`
- Check if the database is initialized: visit phpMyAdmin
- Verify the schema was imported: check for `tasks` table

**Table doesn't exist?**
- Import `src/schema.sql` using phpMyAdmin or command line

**Permission denied?**
- Ensure MySQL container has started properly
- Check logs: `podman-compose logs db`

## Extending the Application

This basic CRUD app can be extended with:
- User authentication and authorization
- Search and filter functionality
- Pagination for large datasets
- REST API endpoints
- File attachments
- Task categories or tags
- User assignment and collaboration

---

**Author**: Created for Podman PHP development environment
**License**: Open source - use freely
