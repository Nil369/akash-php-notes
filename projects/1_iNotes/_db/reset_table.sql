USE inotes;

-- Drop the table if it exists to recreate it with proper length
DROP TABLE IF EXISTS password_resets;

-- Password Reset Tokens Table with longer token field
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reset_token VARCHAR(255) NOT NULL,
    expiry_timestamp DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Show the tables to verify
SHOW TABLES;

-- Describe the structure
DESCRIBE password_resets;

-- Update the password_resets table to use a longer token length
ALTER TABLE password_resets MODIFY reset_token VARCHAR(255) NOT NULL;

-- Show the modified structure
DESCRIBE password_resets; 