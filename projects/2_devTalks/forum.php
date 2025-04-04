<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include 'partials/_dbConnect.php';

// Mock data for categories
$categories = [
    [
        'id' => 1,
        'name' => 'Web Development',
        'slug' => 'web-development',
        'description' => 'Discuss HTML, CSS, JavaScript, and all things related to web development.',
        'threads' => 145,
        'posts' => 780,
        'icon' => 'bi-globe'
    ],
    [
        'id' => 2,
        'name' => 'Mobile Development',
        'slug' => 'mobile',
        'description' => 'Share knowledge about Android, iOS, React Native, Flutter, and more.',
        'threads' => 87,
        'posts' => 342,
        'icon' => 'bi-phone'
    ],
    [
        'id' => 3,
        'name' => 'Database',
        'slug' => 'database',
        'description' => 'SQL, NoSQL, data modeling, and database optimization discussions.',
        'threads' => 64,
        'posts' => 251,
        'icon' => 'bi-database'
    ],
    [
        'id' => 4,
        'name' => 'DevOps',
        'slug' => 'devops',
        'description' => 'Docker, Kubernetes, CI/CD pipelines, and cloud infrastructure topics.',
        'threads' => 53,
        'posts' => 198,
        'icon' => 'bi-gear'
    ],
    [
        'id' => 5,
        'name' => 'UI/UX Design',
        'slug' => 'ui-ux',
        'description' => 'User interface design, user experience, design systems, and accessibility.',
        'threads' => 47,
        'posts' => 215,
        'icon' => 'bi-palette'
    ],
    [
        'id' => 6,
        'name' => 'AI & Machine Learning',
        'slug' => 'ai-ml',
        'description' => 'Artificial intelligence, machine learning, data science discussions.',
        'threads' => 32,
        'posts' => 147,
        'icon' => 'bi-robot'
    ]
];

// Mock data for recent threads
$recent_threads = [
    [
        'id' => 1,
        'title' => 'How to optimize MySQL queries for large datasets?',
        'author' => 'Database Guy',
        'date' => '2023-04-02',
        'category' => 'Database',
        'replies' => 12,
        'views' => 145,
        'isHot' => true,
        'isSticky' => false
    ],
    [
        'id' => 2,
        'title' => 'Best practices for securing a Node.js API',
        'author' => 'Security Expert',
        'date' => '2023-03-30',
        'category' => 'Web Development',
        'replies' => 8,
        'views' => 97,
        'isHot' => false,
        'isSticky' => true
    ],
    [
        'id' => 3,
        'title' => 'Docker vs Kubernetes - When to use what?',
        'author' => 'DevOps Lead',
        'date' => '2023-03-27',
        'category' => 'DevOps',
        'replies' => 15,
        'views' => 210,
        'isHot' => true,
        'isSticky' => false
    ],
    [
        'id' => 4,
        'title' => 'Flutter vs React Native in 2023: Which one to choose?',
        'author' => 'Mobile Dev',
        'date' => '2023-03-25',
        'category' => 'Mobile Development',
        'replies' => 23,
        'views' => 186,
        'isHot' => true,
        'isSticky' => false
    ],
    [
        'id' => 5,
        'title' => 'Implementing dark mode in CSS: best approaches',
        'author' => 'UI Designer',
        'date' => '2023-03-22',
        'category' => 'UI/UX Design',
        'replies' => 9,
        'views' => 104,
        'isHot' => false,
        'isSticky' => false
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forums - Dev Talks</title>
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
        <!-- Forum Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Developer Forums</h1>
            <?php if(isset($_SESSION['userId'])): ?>
            <a href="create-thread.php" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> New Thread
            </a>
            <?php else: ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="bi bi-plus-lg"></i> New Thread
            </button>
            <?php endif; ?>
        </div>

        <!-- Forum Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h3 class="text-primary mb-0"><i class="bi bi-chat-left-text"></i></h3>
                        <h3 class="mb-0">450+</h3>
                        <p class="text-muted mb-0">Threads</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h3 class="text-success mb-0"><i class="bi bi-reply"></i></h3>
                        <h3 class="mb-0">2,100+</h3>
                        <p class="text-muted mb-0">Replies</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h3 class="text-info mb-0"><i class="bi bi-people"></i></h3>
                        <h3 class="mb-0">750+</h3>
                        <p class="text-muted mb-0">Members</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h3 class="text-warning mb-0"><i class="bi bi-person-check"></i></h3>
                        <h3 class="mb-0">25+</h3>
                        <p class="text-muted mb-0">Online Now</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Section -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Categories</h3>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($categories as $category): ?>
                    <a href="category.php?cat=<?php echo $category['slug']; ?>" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-light rounded-circle p-3">
                                    <i class="bi <?php echo $category['icon']; ?> fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-1"><?php echo $category['name']; ?></h5>
                                    <small>
                                        <span class="badge bg-secondary rounded-pill"><?php echo $category['threads']; ?> threads</span>
                                    </small>
                                </div>
                                <p class="mb-1"><?php echo $category['description']; ?></p>
                                <small class="text-muted"><?php echo $category['posts']; ?> posts</small>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Recent Threads -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">Recent Discussions</h3>
            </div>
            <div class="card-body p-0">
                <?php foreach ($recent_threads as $thread): ?>
                    <?php echo ThreadCard(
                        $thread['id'],
                        $thread['title'],
                        $thread['author'],
                        $thread['date'],
                        $thread['category'],
                        $thread['replies'],
                        $thread['views'],
                        $thread['isHot'],
                        $thread['isSticky']
                    ); ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Forum Rules Card -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h3 class="mb-0">Forum Rules</h3>
            </div>
            <div class="card-body">
                <ol class="mb-0">
                    <li><strong>Be respectful to others.</strong> No personal attacks, harassment, or hate speech.</li>
                    <li><strong>Stay on topic.</strong> Create threads in the appropriate categories.</li>
                    <li><strong>No spam or self-promotion.</strong> Don't post solely to promote your product or service.</li>
                    <li><strong>No duplicate threads.</strong> Search before posting to avoid creating duplicates.</li>
                    <li><strong>Use descriptive titles.</strong> Titles should clearly reflect your question or topic.</li>
                </ol>
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