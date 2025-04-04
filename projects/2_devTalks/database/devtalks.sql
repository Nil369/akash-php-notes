-- DevTalks Database Schema

-- Create Database
CREATE DATABASE IF NOT EXISTS devtalks;
USE devtalks;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Member', 'Moderator', 'Admin') DEFAULT 'Member',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME,
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE
);

-- User Profiles Table
CREATE TABLE IF NOT EXISTS user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100),
    bio TEXT,
    location VARCHAR(100),
    website VARCHAR(255),
    github VARCHAR(255),
    twitter VARCHAR(255),
    avatar VARCHAR(255) DEFAULT 'partials/img/avatars/default.png',
    cover_image VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    slug VARCHAR(50) NOT NULL UNIQUE,
    icon VARCHAR(50),
    parent_id INT,
    order_index INT DEFAULT 0,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Tags Table
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    slug VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Threads Table
CREATE TABLE IF NOT EXISTS threads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    views INT DEFAULT 0,
    is_sticky BOOLEAN DEFAULT FALSE,
    is_closed BOOLEAN DEFAULT FALSE,
    is_resolved BOOLEAN DEFAULT FALSE,
    is_draft BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Thread Replies Table
CREATE TABLE IF NOT EXISTS thread_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    thread_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    code_snippet TEXT,
    code_language VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_solution BOOLEAN DEFAULT FALSE,
    parent_id INT DEFAULT NULL,
    FOREIGN KEY (thread_id) REFERENCES threads(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES thread_replies(id) ON DELETE SET NULL
);

-- Thread Tags Junction Table
CREATE TABLE IF NOT EXISTS thread_tags (
    thread_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (thread_id, tag_id),
    FOREIGN KEY (thread_id) REFERENCES threads(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Blogs Table
CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    featured_image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at DATETIME,
    views INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    is_draft BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Blog Tags Junction Table
CREATE TABLE IF NOT EXISTS blog_tags (
    blog_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (blog_id, tag_id),
    FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Blog Comments Table
CREATE TABLE IF NOT EXISTS blog_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blog_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    parent_id INT DEFAULT NULL,
    is_approved BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES blog_comments(id) ON DELETE SET NULL
);

-- Likes Table (for both blogs and threads)
CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content_type ENUM('blog', 'thread', 'thread_reply', 'blog_comment') NOT NULL,
    content_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_like (user_id, content_type, content_id)
);

-- Bookmarks Table
CREATE TABLE IF NOT EXISTS bookmarks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content_type ENUM('blog', 'thread') NOT NULL,
    content_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_bookmark (user_id, content_type, content_id)
);

-- User Badges Table
CREATE TABLE IF NOT EXISTS badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    class VARCHAR(50) DEFAULT 'bg-primary',
    requirement_type ENUM('posts_count', 'solutions_count', 'reputation', 'manual') NOT NULL,
    requirement_count INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- User Badges Junction Table
CREATE TABLE IF NOT EXISTS user_badges (
    user_id INT NOT NULL,
    badge_id INT NOT NULL,
    awarded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, badge_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE
);

-- Notifications Table
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content VARCHAR(255) NOT NULL,
    link VARCHAR(255),
    type ENUM('reply', 'mention', 'like', 'solution', 'badge', 'follow', 'system') NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Follows Table
CREATE TABLE IF NOT EXISTS follows (
    follower_id INT NOT NULL,
    followee_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (follower_id, followee_id),
    FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (followee_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Authentication Tokens Table (for "Remember Me" functionality)
CREATE TABLE IF NOT EXISTS auth_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    selector VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Messages Table
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Activity Log Table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity_type ENUM('blog_create', 'blog_update', 'thread_create', 'thread_reply', 'comment', 'profile_update', 'badge_earned', 'login', 'logout') NOT NULL,
    content_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    details TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Reported Content Table
CREATE TABLE IF NOT EXISTS reported_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reported_by INT NOT NULL,
    content_type ENUM('blog', 'thread', 'thread_reply', 'blog_comment', 'user') NOT NULL,
    content_id INT NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('pending', 'resolved', 'dismissed') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    resolved_at DATETIME,
    resolved_by INT,
    FOREIGN KEY (reported_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (resolved_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert some default categories
INSERT INTO categories (name, description, slug, icon) VALUES
('Web Development', 'Discussion about web development technologies and practices', 'web-development', 'bi-globe'),
('Mobile Development', 'Mobile app development for iOS, Android, and cross-platform', 'mobile-development', 'bi-phone'),
('DevOps', 'CI/CD, deployment, infrastructure, and operations', 'devops', 'bi-gear'),
('Data Science', 'Data analysis, machine learning, and AI', 'data-science', 'bi-graph-up'),
('Databases', 'Database design, optimization, and management', 'databases', 'bi-server'),
('Cloud Computing', 'AWS, Azure, Google Cloud, and general cloud topics', 'cloud-computing', 'bi-cloud'),
('UI/UX', 'User interface design and user experience', 'ui-ux', 'bi-palette'),
('JavaScript', 'JavaScript language, frameworks, and libraries', 'javascript', 'bi-filetype-js'),
('PHP', 'PHP language, frameworks, and libraries', 'php', 'bi-filetype-php'),
('Python', 'Python language, frameworks, and libraries', 'python', 'bi-filetype-py'),
('Java', 'Java language, frameworks, and libraries', 'java', 'bi-cup-hot'),
('React', 'React.js library and ecosystem', 'react', 'bi-filetype-jsx'),
('Vue', 'Vue.js framework and ecosystem', 'vue', 'bi-box'),
('Angular', 'Angular framework and ecosystem', 'angular', 'bi-diagram-3'),
('Node.js', 'Node.js runtime and ecosystem', 'nodejs', 'bi-hdd-stack'),
('Security', 'Web security, authentication, and protection', 'security', 'bi-shield-lock'),
('Testing', 'Testing methodologies, frameworks, and tools', 'testing', 'bi-bug'),
('Tools & Productivity', 'Development tools, editors, and productivity tips', 'tools-productivity', 'bi-tools'),
('Career & Learning', 'Career development, learning resources, and education', 'career-learning', 'bi-mortarboard'),
('Help & Support', 'General help and support for developers', 'help-support', 'bi-question-circle');

-- Insert some default tags
INSERT INTO tags (name, slug, description) VALUES
('JavaScript', 'javascript', 'JavaScript programming language'),
('PHP', 'php', 'PHP programming language'),
('Python', 'python', 'Python programming language'),
('Java', 'java', 'Java programming language'),
('SQL', 'sql', 'Structured Query Language'),
('HTML', 'html', 'Hypertext Markup Language'),
('CSS', 'css', 'Cascading Style Sheets'),
('React', 'react', 'React JavaScript library'),
('Vue', 'vue', 'Vue.js JavaScript framework'),
('Angular', 'angular', 'Angular JavaScript framework'),
('Node.js', 'nodejs', 'Node.js JavaScript runtime'),
('Laravel', 'laravel', 'Laravel PHP framework'),
('Django', 'django', 'Django Python framework'),
('Spring', 'spring', 'Spring Java framework'),
('Database', 'database', 'Database technologies and concepts'),
('API', 'api', 'Application Programming Interface'),
('REST', 'rest', 'Representational State Transfer'),
('GraphQL', 'graphql', 'GraphQL query language'),
('Docker', 'docker', 'Docker containerization platform'),
('Kubernetes', 'kubernetes', 'Kubernetes container orchestration'),
('AWS', 'aws', 'Amazon Web Services'),
('Azure', 'azure', 'Microsoft Azure cloud platform'),
('GCP', 'gcp', 'Google Cloud Platform'),
('Security', 'security', 'Application and web security'),
('Performance', 'performance', 'Application performance optimization'),
('Testing', 'testing', 'Software testing methodologies and tools'),
('DevOps', 'devops', 'Development and operations practices'),
('CI/CD', 'ci-cd', 'Continuous Integration and Continuous Deployment'),
('Mobile', 'mobile', 'Mobile app development'),
('Web', 'web', 'Web development'),
('Frontend', 'frontend', 'Frontend development'),
('Backend', 'backend', 'Backend development'),
('Fullstack', 'fullstack', 'Full-stack development'),
('Career', 'career', 'Career development in tech'),
('Learning', 'learning', 'Learning and education in tech'),
('Beginner', 'beginner', 'Content for beginners'),
('Advanced', 'advanced', 'Advanced topics and techniques'),
('Optimization', 'optimization', 'Code and system optimization'),
('Architecture', 'architecture', 'Software architecture'),
('Design Patterns', 'design-patterns', 'Software design patterns');

-- Insert default badges
INSERT INTO badges (name, description, icon, class, requirement_type, requirement_count) VALUES
('Newcomer', 'Joined the community', 'bi-person-plus', 'bg-secondary', 'manual', 0),
('First Post', 'Created your first post', 'bi-pencil-square', 'bg-secondary', 'posts_count', 1),
('Regular Contributor', 'Created 10 posts or threads', 'bi-pencil', 'bg-primary', 'posts_count', 10),
('Expert Contributor', 'Created 50 posts or threads', 'bi-pencil-fill', 'bg-primary', 'posts_count', 50),
('Solution Provider', 'Provided a solution to a thread', 'bi-check-circle', 'bg-success', 'solutions_count', 1),
('Problem Solver', 'Provided 10 solutions to threads', 'bi-check-circle-fill', 'bg-success', 'solutions_count', 10),
('Rising Star', 'Reached 100 reputation points', 'bi-star-half', 'bg-warning', 'reputation', 100),
('Superstar', 'Reached 1000 reputation points', 'bi-star-fill', 'bg-warning', 'reputation', 1000),
('Code Master', 'Contributed high-quality code examples', 'bi-code-square', 'bg-info', 'manual', 0),
('Helpful Mentor', 'Recognized for helping other members', 'bi-people', 'bg-info', 'manual', 0);

-- Create an admin user (password: admin123)
INSERT INTO users (username, email, password, role, created_at, email_verified) VALUES
('admin', 'admin@devtalks.com', '$2y$10$GJsZca1LyR0q.uUgFr8lXOwsTgRO5G5s/Wm3Qk9vOJl4F8pXmDpLG', 'Admin', NOW(), TRUE);

-- Create admin's profile
INSERT INTO user_profiles (user_id, full_name, bio, avatar) VALUES
(1, 'Admin User', 'I am the site administrator.', 'partials/img/avatars/default.png');

-- Grant the admin the necessary badges
INSERT INTO user_badges (user_id, badge_id) VALUES
(1, 1), -- Newcomer
(1, 10); -- Helpful Mentor 