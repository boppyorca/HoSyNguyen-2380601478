USE my_store;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Chen tai khoan mac dinh
-- admin / admin123
INSERT INTO users (username, password, role, name, email) 
VALUES ('admin', '$2y$10$J9ArFFGuar1D/d5opeukB.PwvXoBRvyjDCXWa0qpKLdzW1bqJoINO', 'admin', 'Administrator', 'admin@example.com')
ON DUPLICATE KEY UPDATE username=username;

-- user / user123
INSERT INTO users (username, password, role, name, email) 
VALUES ('user', '$2y$10$r9qT/W9zP4cY4gWvUpboluugEWMOiAwJSg5OujZl0HYUSzQorc/.O', 'user', 'Regular User', 'user@example.com')
ON DUPLICATE KEY UPDATE username=username;
