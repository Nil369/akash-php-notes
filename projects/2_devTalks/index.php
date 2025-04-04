<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dev Talks - Community for Developers</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Prism.js CSS for syntax highlighting -->
    <link rel="stylesheet" href="partials/css/prism.css">
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
        include "components/banner.php";
        include "components/cards.php";
        include "components/alert.php";
    ?>

    <div class="container mt-4">
        <!-- Status alerts for login/logout/signup -->
        <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
            <div class="row">
                <div class="col-12">
                    <?php echo ShowAlert(htmlspecialchars($_GET['message']), htmlspecialchars($_GET['status'])); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Featured section -->
        <div class="row">
            <div class="col-12">
                <?php echo ShowAlert('<strong>Welcome to DevTalks!</strong> A community forum for developers to share knowledge and connect.', 'info'); ?>
            </div>
        </div>

        <!-- Main content -->
        <div class="row">
            <!-- Left column: Latest posts -->
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Latest Blogs</h2>
                    <a href="blogs.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>

                <div class="row">
                    <?php
                    // Example data - would come from database in real implementation
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
                        ]
                    ];
                    
                    foreach ($blogs as $blog) {
                        echo '<div class="col-md-6 mb-4">';
                        echo BlogCard(
                            $blog['id'],
                            $blog['title'],
                            $blog['excerpt'],
                            $blog['author'],
                            $blog['date'],
                            $blog['category'],
                            $blog['image'],
                            $blog['likes'],
                            $blog['comments']
                        );
                        echo '</div>';
                    }
                    ?>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Popular Discussions</h2>
                    <a href="forum.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>

                <?php
                // Example data - would come from database in real implementation
                $threads = [
                    [
                        'id' => 1,
                        'title' => 'How to optimize MySQL queries for large datasets?',
                        'author' => 'Database Guy',
                        'date' => '2023-04-02',
                        'category' => 'Databases',
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
                        'category' => 'Node.js',
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
                    ]
                ];
                
                foreach ($threads as $thread) {
                    echo ThreadCard(
                        $thread['id'],
                        $thread['title'],
                        $thread['author'],
                        $thread['date'],
                        $thread['category'],
                        $thread['replies'],
                        $thread['views'],
                        $thread['isHot'],
                        $thread['isSticky']
                    );
                }
                ?>
            </div>

            <!-- Right sidebar -->
            <div class="col-lg-4">
                <!-- Search widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Search</h5>
                    </div>
                    <div class="card-body">
                        <form action="search.php" method="get">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search..." name="q">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Categories widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="category.php?cat=web-development" class="btn btn-sm btn-outline-primary">Web Development</a>
                            <a href="category.php?cat=mobile" class="btn btn-sm btn-outline-success">Mobile</a>
                            <a href="category.php?cat=database" class="btn btn-sm btn-outline-danger">Database</a>
                            <a href="category.php?cat=devops" class="btn btn-sm btn-outline-info">DevOps</a>
                            <a href="category.php?cat=cloud" class="btn btn-sm btn-outline-warning">Cloud</a>
                            <a href="category.php?cat=security" class="btn btn-sm btn-outline-secondary">Security</a>
                            <a href="category.php?cat=ai-ml" class="btn btn-sm btn-outline-dark">AI & ML</a>
                            <a href="categories.php" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                    </div>
                </div>

                <!-- Featured code block -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Code of the Day</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $codeExample = <<<'EOD'
function fibonacci($n) {
    $fib = [0, 1];
    
    for ($i = 2; $i <= $n; $i++) {
        $fib[$i] = $fib[$i-1] + $fib[$i-2];
    }
    
    return $fib[$n];
}

echo fibonacci(10); // Output: 55
EOD;
                        
                        echo CodeBlockCard($codeExample, 'php');
                        ?>
                        <div class="text-center mt-3">
                            <a href="blog.php?id=6" class="btn btn-sm btn-primary">View Tutorial</a>
                        </div>
                    </div>
                </div>

                <!-- Active members -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Top Contributors</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex mb-2">
                            <img src="partials/img/avatars/default.png" class="avatar avatar-sm me-2" alt="User avatar">
                            <div>
                                <h6 class="mb-0">CodeMaster</h6>
                                <small class="text-muted">352 posts</small>
                            </div>
                        </div>
                        <div class="d-flex mb-2">
                            <img src="partials/img/avatars/default.png" class="avatar avatar-sm me-2" alt="User avatar">
                            <div>
                                <h6 class="mb-0">DevGuru</h6>
                                <small class="text-muted">287 posts</small>
                            </div>
                        </div>
                        <div class="d-flex">
                            <img src="partials/img/avatars/default.png" class="avatar avatar-sm me-2" alt="User avatar">
                            <div>
                                <h6 class="mb-0">TechNinja</h6>
                                <small class="text-muted">201 posts</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "components/footer.php"; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>
    <!-- Prism.js for syntax highlighting -->
    <script src="partials/js/prism.js"></script>
    <!-- Custom JS -->
    <script src="partials/js/script.js"></script>
</body>
</html>