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
    ],
    [
        'id' => 7,
        'name' => 'JavaScript',
        'slug' => 'javascript',
        'description' => 'Discussions about JavaScript, ES6+, TypeScript and related frameworks.',
        'threads' => 93,
        'posts' => 415,
        'icon' => 'bi-filetype-js'
    ],
    [
        'id' => 8,
        'name' => 'PHP',
        'slug' => 'php',
        'description' => 'PHP language, frameworks like Laravel, Symfony, and backend development.',
        'threads' => 78,
        'posts' => 302,
        'icon' => 'bi-filetype-php'
    ],
    [
        'id' => 9,
        'name' => 'Python',
        'slug' => 'python',
        'description' => 'Python programming, Django, Flask, and data analysis with Python.',
        'threads' => 81,
        'posts' => 367,
        'icon' => 'bi-filetype-py'
    ],
    [
        'id' => 10,
        'name' => 'Cloud Computing',
        'slug' => 'cloud',
        'description' => 'AWS, Azure, Google Cloud, and general cloud computing discussions.',
        'threads' => 42,
        'posts' => 186,
        'icon' => 'bi-cloud'
    ],
    [
        'id' => 11,
        'name' => 'Security',
        'slug' => 'security',
        'description' => 'Web security, authentication, encryption, and best security practices.',
        'threads' => 36,
        'posts' => 163,
        'icon' => 'bi-shield-lock'
    ],
    [
        'id' => 12,
        'name' => 'Testing',
        'slug' => 'testing',
        'description' => 'Unit testing, integration testing, and quality assurance topics.',
        'threads' => 29,
        'posts' => 147,
        'icon' => 'bi-bug'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Dev Talks</title>
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
    ?>

    <div class="container mt-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1>Forum Categories</h1>
                <p class="lead">Browse all discussion categories to find the topics that interest you.</p>
            </div>
        </div>

        <!-- Search Categories -->
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form action="search.php" method="get">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search categories..." name="q">
                                <input type="hidden" name="type" value="category">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="row">
            <?php foreach ($categories as $category): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-3 me-3">
                                <i class="bi <?php echo $category['icon']; ?> fs-4"></i>
                            </div>
                            <h4 class="mb-0"><?php echo $category['name']; ?></h4>
                        </div>
                        <p class="card-text"><?php echo $category['description']; ?></p>
                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                <small class="text-muted">
                                    <i class="bi bi-chat-left-text me-1"></i> <?php echo $category['threads']; ?> threads
                                </small>
                            </div>
                            <div>
                                <small class="text-muted">
                                    <i class="bi bi-reply me-1"></i> <?php echo $category['posts']; ?> posts
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="category.php?cat=<?php echo $category['slug']; ?>" class="btn btn-outline-primary btn-sm w-100">
                            Browse Threads
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Category Navigation -->
        <div class="card mt-4 mb-4">
            <div class="card-header">
                <h4 class="mb-0">Quick Navigation</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Development</h5>
                        <ul class="list-unstyled">
                            <li><a href="category.php?cat=web-development" class="text-decoration-none">Web Development</a></li>
                            <li><a href="category.php?cat=mobile" class="text-decoration-none">Mobile Development</a></li>
                            <li><a href="category.php?cat=javascript" class="text-decoration-none">JavaScript</a></li>
                            <li><a href="category.php?cat=php" class="text-decoration-none">PHP</a></li>
                            <li><a href="category.php?cat=python" class="text-decoration-none">Python</a></li>
                        </ul>
                        
                        <h5 class="mt-3">Design</h5>
                        <ul class="list-unstyled">
                            <li><a href="category.php?cat=ui-ux" class="text-decoration-none">UI/UX Design</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Infrastructure</h5>
                        <ul class="list-unstyled">
                            <li><a href="category.php?cat=database" class="text-decoration-none">Databases</a></li>
                            <li><a href="category.php?cat=devops" class="text-decoration-none">DevOps</a></li>
                            <li><a href="category.php?cat=cloud" class="text-decoration-none">Cloud Computing</a></li>
                            <li><a href="category.php?cat=security" class="text-decoration-none">Security</a></li>
                        </ul>
                        
                        <h5 class="mt-3">Other</h5>
                        <ul class="list-unstyled">
                            <li><a href="category.php?cat=ai-ml" class="text-decoration-none">AI & Machine Learning</a></li>
                            <li><a href="category.php?cat=testing" class="text-decoration-none">Testing</a></li>
                        </ul>
                    </div>
                </div>
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