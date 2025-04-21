# iNotes - PHP CRUD Notes Application

A modern, responsive notes management application built with PHP, MySQL, Bootstrap 5, and DataTables.

## Features

- User Authentication System:
  - Secure login and registration
  - Password reset functionality
  - User profile management
  - Password hashing for security
- Create, Read, Update, and Delete notes
- Pin important notes to the top
- Archive notes that are no longer needed
- Tag notes with categories
- Search notes by title, description, or tag
- Responsive design with Bootstrap 5
- Enhanced table functionality with DataTables
- Real-time AJAX search
- Dark/Light theme toggle

## Database Structure

The application uses a MySQL database with the following tables:

```sql
-- Notes Table
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    tag VARCHAR(50) DEFAULT 'General',
    is_pinned BOOLEAN DEFAULT FALSE,
    is_archived BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Password Reset Table
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reset_token VARCHAR(255) NOT NULL,
    expiry_timestamp DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Installation

1. Clone or download this repository to your web server directory
2. Set up your database:
   - Option 1 (Local): Import the `db.sql` file to create the notes table locally
   - Option 2 (Cloud): Create the required tables on your cloud database using the SQL in the Database Structure section
3. Configure database connection:
   - Copy `sample-env` to `.env`
   - Edit `.env` with your database credentials (local or cloud)
4. If using cloud database, no need to start XAMPP/MySQL locally
5. Access the application through your web server

## Cloud Database Setup

To use a cloud database instead of a local MySQL server:

1. Sign up for a cloud database service (e.g., Amazon RDS, Google Cloud SQL, Aiven, etc.)
2. Create a MySQL database instance
3. Configure firewall rules to allow your application to connect
4. Get your connection credentials (host, username, password, database name)
5. Update your `.env` file with these credentials
6. The application will now connect directly to your cloud database

## Usage

- Register a new account or login with existing credentials
- View all notes on the home page
- Use the search bar in the navigation to find notes
- Click "Add Note" to create a new note
- Use the action buttons to edit, pin, archive, or delete notes
- Access archived notes through the "Archived" link in the navigation
- Manage your profile by clicking on your username
- Toggle between dark and light themes using the theme button

## Technologies Used

- PHP
- MySQL
- Bootstrap 5
- DataTables
- jQuery
- AJAX
- Font Awesome
- Prepared Statements for SQL security

## License

This project is open source and available under the [MIT License](LICENSE). 