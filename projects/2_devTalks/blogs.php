<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include 'partials/_dbConnect.php';

// Mock data for blog posts
$blogs = [
    [
        'id' => 1,
        'title' => 'Getting Started with React Hooks',
        'excerpt' => 'Learn how to use useState and useEffect to manage state in your functional components.',
        'author' => 'Jane Dev',
        'date' => '2023-04-01',
        'category' => 'React',
        'image' => 'partials/img/blog1.jpg',
        'likes' => 24,
        'comments' => 5
    ],
    [
        'id' => 2,
        'title' => 'PHP 8.1 Features You Should Know',
        'excerpt' => 'Explore the new features and improvements in PHP 8.1 that make coding more efficient.',
        'author' => 'John Coder',
        'date' => '2023-03-28',
        'category' => 'PHP',
        'image' => null,
        'likes' => 18,
        'comments' => 3
    ],
    [
        'id' => 3,
        'title' => 'Building Responsive Layouts with CSS Grid',
        'excerpt' => 'A comprehensive guide to creating modern responsive layouts using CSS Grid.',
        'author' => 'CSS Master',
        'date' => '2023-03-25',
        'category' => 'CSS',
        'image' => 'partials/img/blog1.jpg',
        'likes' => 32,
        'comments' => 7
    ],
    [
        'id' => 4,
        'title' => 'Introduction to Docker for Developers',
        'excerpt' => 'Learn how to containerize your applications with Docker for consistent development environments.',
        'author' => 'DevOps Guru',
        'date' => '2023-03-22',
        'category' => 'DevOps',
        'image' => null,
        'likes' => 15,
        'comments' => 2
    ],
    [
        'id' => 5,
        'title' => 'Understanding TypeScript Generics',
        'excerpt' => 'Dive deep into TypeScript generics and learn how to write more reusable and type-safe code.',
        'author' => 'TS Enthusiast',
        'date' => '2023-03-20',
        'category' => 'TypeScript',
        'image' => 'partials/img/blog1.jpg',
        'likes' => 27,
        'comments' => 4
    ],
    [
        'id' => 6,
        'title' => 'Optimizing MySQL Database Performance',
        'excerpt' => 'Learn practical techniques to improve MySQL database performance for high-traffic applications.',
        'author' => 'DB Expert',
        'date' => '2023-03-18',
        'category' => 'Databases',
        'image' => null,
        'likes' => 21,
        'comments' => 6
    ]
];

// Categories for filter
$categories = [
    'All', 'JavaScript', 'PHP', 'CSS', 'DevOps', 'TypeScript', 'React', 'Vue', 'Node.js', 'Databases', 'Security'
];

// Get current category filter (if any)
$currentCategory = isset($_GET['category']) ? $_GET['category'] : 'All';

// Filter blogs if a category is selected
if ($currentCategory != 'All') {
    $filteredBlogs = array_filter($blogs, function($blog) use ($currentCategory) {
        return $blog['category'] == $currentCategory;
    });
    $blogs = $filteredBlogs;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts - Dev Talks</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="partials/css/style.css">
    <!-- Set initial theme based on user preference -->
    <script>
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme') || 
                        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        
        // Apply theme class to document
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        document.write(`<body class="${savedTheme}-mode">`);
    </script>
</head>
<body>

    <?php 
        include "components/header.php"; 
        include "components/loginModal.php";
        include "components/signupModal.php";
        include "components/cards.php";
    ?>

    <div class="container mt-4">
        <!-- Blog Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Developer Blog</h1>
            <?php if(isset($_SESSION['userId'])): ?>
            <a href="create-blog.php" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Write New Post
            </a>
            <?php else: ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="bi bi-plus-lg"></i> Write New Post
            </button>
            <?php endif; ?>
        </div>

        <!-- Category Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Filter by Category</h5>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($categories as $category): ?>
                    <a href="blogs.php<?php echo $category != 'All' ? '?category=' . urlencode($category) : ''; ?>" 
                       class="btn btn-sm <?php echo $category == $currentCategory ? 'btn-primary' : 'btn-outline-primary'; ?>">
                        <?php echo $category; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Blog Posts -->
        <div class="row">
            <?php if(count($blogs) > 0): ?>
                <?php foreach ($blogs as $blog): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <?php echo BlogCard(
                        $blog['id'],
                        $blog['title'],
                        $blog['excerpt'],
                        $blog['author'],
                        $blog['date'],
                        $blog['category'],
                        $blog['image'],
                        $blog['likes'],
                        $blog['comments']
                    ); ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No blog posts found for the selected category. Try a different category or check back later!
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Blog pagination" class="my-4">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>

        <!-- Newsletter Signup -->
        <div class="card mb-4">
            <div class="card-body text-center p-4">
                <h3>Stay Updated with Developer Content</h3>
                <p class="mb-4">Subscribe to our newsletter to receive weekly development tips, tutorials, and news.</p>
                <form class="row g-3 justify-content-center">
                    <div class="col-md-6">
                        <input type="email" class="form-control" placeholder="Your email address">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include "components/footer.php"; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>
    <!-- Custom JS -->
    <script src="partials/js/script.js"></script>
</body>
</html> 