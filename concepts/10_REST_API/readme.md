Absolutely! Here's a `README.md` file you can include in your project:

---

# 🧑‍💻 User Management REST API with PHP

This is a simple RESTful API built in PHP for managing users. It supports basic CRUD operations: Create, Read, Update, and Delete. It includes:

- RESTful API built with PHP & MySQL
- Swagger UI documentation
- Frontend client using HTML, CSS, Bootstrap 5, and JavaScript

---

## 📁 Project Structure

```
├── api/
│   ├── api-get-all.php
│   ├── api-get.php
│   ├── api-insert.php
│   ├── api-update.php
│   ├── api-delete.php
│   ├── api-search.php
│   ├── swagger.json
│   └── docs/
│       └── swagger-ui/dist   # Swagger UI Distribution
│       └── index.php         # Swagger UI Implementation
│       └── swagger.json      # Swagger UI Roues
├── config/
│   └── db-connect.php
├── index.html                # Frontend UI
├── README.md
```

---

## 🛠️ Setup Instructions

### 1. ⚙️ Database Setup

Run the following SQL to create the `users` table:

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### 2. 💾 Backend Configuration

Update `config/db-connect.php` with your database credentials:

```php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "your_database_name";
$conn = mysqli_connect($hostname, $username, $password, $database);
```

### 3. 🌐 Serve the Project

Use XAMPP or any local server, and place the project inside the `htdocs` directory. Access it via:

```
http://localhost/akash-php-notes/concepts/10_REST_API/
```

---

## 🔍 API Endpoints

| Method | Endpoint            | Description               |
|--------|---------------------|---------------------------|
| GET    | `/api-get-all.php`  | Fetch all users           |
| GET    | `/api-get.php?uid=` | Get a user by ID          |
| GET    | `/api-search.php?search=` | Search users by name   |
| POST   | `/api-insert.php`   | Create a new user         |
| PUT    | `/api-update.php`   | Update user data          |
| DELETE | `/api-delete.php`   | Delete a user             |

All APIs return JSON.

---

## 📖 API Documentation

Swagger UI is available at:

```
http://localhost/akash-php-notes/concepts/10_REST_API/api/docs/index.php
```


Use this interface to explore and test all the available API endpoints.
---
### Screenshots:
![Swagger_UI](https://github.com/user-attachments/assets/cc8efe18-e1dc-483a-ad52-a425025151ef)


---

## 💻 Frontend Features

- 📋 Table view of all users
- 🔍 Real-time search
- 📝 Edit user modal
- ❌ Delete user confirmation modal
- 📑 Link to API docs

### Technologies used:
- HTML5
- CSS3
- Bootstrap 5
- JavaScript (Fetch API)

## 📌 Future Enhancements

- Form validation (client + server)
- Pagination and sorting
- JWT-based authentication
- Export users as CSV

---

## 📃 License

This project is open-source and free to use for educational purposes.

---

Made with ❤️ in PHP
