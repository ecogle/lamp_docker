-- Active: 1763903836277@@127.0.0.1@3306@testdb
-- Database: exampledb (already created via docker-compose.yml)

-- Drop table if exists
DROP TABLE IF EXISTS tasks;

-- Create tasks table
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO tasks (title, description, status, priority, due_date) VALUES
('Setup development environment', 'Install Podman and configure docker-compose', 'completed', 'high', '2025-11-20'),
('Create database schema', 'Design and implement tasks table', 'in_progress', 'high', '2025-11-23'),
('Build CRUD interface', 'Implement Create, Read, Update, Delete operations', 'pending', 'medium', '2025-11-25'),
('Add user authentication', 'Implement login and registration', 'pending', 'low', '2025-12-01'),
('Write documentation', 'Document API endpoints and usage', 'pending', 'medium', '2025-11-30');
