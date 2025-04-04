# DevTalks

DevTalks is a comprehensive developer community forum built with PHP, MySQL, and Bootstrap. It provides a platform for developers to share knowledge, write blog posts, participate in discussion threads, and connect with other developers.

## Features

- **User Authentication System**
  - Registration and login
  - Email verification
  - "Remember me" functionality
  - User roles (Member, Moderator, Admin)

- **User Profiles**
  - Customizable profiles with avatars
  - Social media links
  - Activity tracking
  - Badges and reputation system

- **Blog System**
  - Rich text editor with code highlighting
  - Categories and tags
  - Comments
  - Like and bookmark functionality

- **Forum/Discussion System**
  - Thread creation with code support
  - Replies and nested comments
  - Solution marking feature
  - Thread categorization and tagging

- **UI/UX Features**
  - Responsive design for all devices
  - Dark/Light mode toggle
  - Modern Bootstrap 5 interface
  - Prism.js for code syntax highlighting

- **Additional Features**
  - Search functionality
  - Notifications system
  - Private messaging
  - Activity feed

## Technology Stack

- **Frontend**: HTML, CSS, JavaScript, Bootstrap 5
- **Backend**: PHP
- **Database**: MySQL
- **Libraries**:
  - Bootstrap 5 for UI components
  - Bootstrap Icons for icon set
  - Prism.js for code syntax highlighting
  - Quill.js for rich text editing

## Installation

1. **Clone the repository**
   ```
   git clone https://github.com/yourusername/devtalks.git
   ```

2. **Set up the database**
   - Create a MySQL database named `devtalks`
   - Import the SQL schema from `database/devtalks.sql`

3. **Configure the database connection**
   - Update the database connection details in `partials/_dbConnect.php`

4. **Configure your web server**
   - Point your web server (Apache, Nginx, etc.) to the project directory
   - Ensure PHP is properly configured

5. **Access the application**
   - Navigate to the URL of your web server
   - Default admin login:
     - Username: admin
     - Password: admin123

## Project Structure

```
devtalks/
├── components/            # Reusable UI components
│   ├── header.php
│   ├── footer.php
│   ├── cards.php
│   ├── alert.php
│   └── ...
├── database/              # Database schema and migrations
│   └── devtalks.sql
├── partials/              # Helper files and assets
│   ├── _dbConnect.php
│   ├── css/
│   ├── js/
│   ├── img/
│   └── ...
├── index.php              # Homepage
├── blog.php               # Blog post detail
├── thread.php             # Forum thread detail
├── profile.php            # User profile
├── create-blog.php        # Blog creation
├── create-thread.php      # Thread creation
└── ...
```

## Usage

1. **Register an account** or use the default admin account
2. **Create blog posts** to share your knowledge
3. **Start or join discussions** in the forum
4. **Interact with other users** through comments, likes, and messages

## Development

### Adding New Features

1. **Create a new PHP file** for your feature
2. **Include necessary components** at the beginning of the file
3. **Add the necessary HTML structure** using Bootstrap classes
4. **Implement backend functionality** (database operations, validations, etc.)

### Styling

The project uses a combination of Bootstrap 5 classes and custom CSS. If you need to add custom styles, add them to `partials/css/style.css`.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Credits

- Bootstrap - [https://getbootstrap.com/](https://getbootstrap.com/)
- Prism.js - [https://prismjs.com/](https://prismjs.com/)
- Bootstrap Icons - [https://icons.getbootstrap.com/](https://icons.getbootstrap.com/)
- Quill Editor - [https://quilljs.com/](https://quilljs.com/)

## Contact

For questions, feedback, or support, please contact [your-email@example.com](mailto:your-email@example.com) 