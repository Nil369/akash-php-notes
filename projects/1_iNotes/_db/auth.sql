-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Password Reset Tokens Table
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reset_token VARCHAR(100) NOT NULL,
    expiry_timestamp DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Modify Notes Table to include user_id
ALTER TABLE notes ADD COLUMN user_id INT AFTER id;
ALTER TABLE notes ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Update existing notes (assign to default admin user)
-- First, create default admin user if not exists
INSERT INTO users (username, email, password) 
VALUES ('admin', 'admin@example.com', '$2y$10$mFPxS1elPpYCB/eN3qp.1O2YRNL3CVL1cs1u3LHjk.Cj0AXMrqcHO') 
ON DUPLICATE KEY UPDATE id=id; -- Password is 'admin123'

-- Update existing notes to belong to admin user
UPDATE notes SET user_id = (SELECT id FROM users WHERE username = 'admin') WHERE user_id IS NULL; 