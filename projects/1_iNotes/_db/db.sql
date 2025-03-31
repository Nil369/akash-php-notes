-- Database Creation
CREATE DATABASE IF NOT EXISTS inotes;
USE inotes;

-- Notes Table
CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    tag VARCHAR(50) DEFAULT 'General',
    is_pinned BOOLEAN DEFAULT FALSE,
    is_archived BOOLEAN DEFAULT FALSE
);

-- Insert Sample Data
INSERT INTO notes (title, description, tag) VALUES 
('Meeting Notes', 'Discussion about new project timeline', 'Work'),
('Shopping List', 'Milk, Eggs, Bread, Vegetables', 'Personal'),
('Book Recommendations', 'The Alchemist, Atomic Habits, Deep Work', 'Learning'),
('Project Ideas', 'Create a personal finance tracker app', 'Ideas'),
('Workout Plan', 'Monday: Chest, Tuesday: Back, Wednesday: Legs', 'Health'); 