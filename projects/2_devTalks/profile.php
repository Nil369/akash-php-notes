<?php
// Start session
session_start();

// Include auth helper and require login
include 'partials/auth_helper.php';
requireLogin();

// Include database connection
include 'partials/_dbConnect.php';
include "components/cards.php";

// Get user ID from URL parameter
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If no ID provided, use current user's ID
if ($userId === 0 && isLoggedIn()) {
    $userId = getCurrentUserId();
}

// In a real application, you would fetch user data from the database using the $userId
// For now, we're using mock data with the actual username from session
$user = [
    'id' => $userId,
    'username' => $_SESSION['username'],
    'fullName' => $_SESSION['username'],
    'email' => isset($_SESSION['userEmail']) ? $_SESSION['userEmail'] : 'user@example.com',
    'joinDate' => '2023-01-15',
    'bio' => 'Full-stack developer passionate about web technologies and open source.',
    'avatar' => isset($_SESSION['userAvatar']) ? $_SESSION['userAvatar'] : 'partials/img/avatars/default.png',
    'role' => 'Member',
    'location' => 'San Francisco, CA',
    'website' => 'https://example.com',
    'github' => 'github.com/user',
    'twitter' => 'twitter.com/user',
    'posts' => 352,
    'reputation' => 4850,
    'badges' => [
        ['name' => 'Solution Provider', 'class' => 'bg-success'],
        ['name' => 'Top Contributor', 'class' => 'bg-primary'],
        ['name' => 'Code Expert', 'class' => 'bg-info']
    ]
];

// User's recent activity
$activity = [
    [
        'type' => 'blog',
        'title' => 'Getting Started with React Hooks',
        'date' => '2023-04-01',
        'link' => 'blog.php?id=1',
        'icon' => 'bi-file-text'
    ],
    [
        'type' => 'comment',
        'title' => 'Commented on "Best practices for securing a Node.js API"',
        'date' => '2023-03-30',
        'link' => 'thread.php?id=2#comment5',
        'icon' => 'bi-chat'
    ],
    [
        'type' => 'thread',
        'title' => 'How to optimize MySQL queries for large datasets?',
        'date' => '2023-03-28',
        'link' => 'thread.php?id=1',
        'icon' => 'bi-question-circle'
    ],
    [
        'type' => 'solution',
        'title' => 'Provided solution for "React component not re-rendering"',
        'date' => '2023-03-25',
        'link' => 'thread.php?id=4#comment12',
        'icon' => 'bi-check-circle'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $user['username']; ?>'s Profile - Dev Talks</title>
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

    <!-- Alert for logout/login/signup success/error -->
    <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
        <div class="alert alert-<?php echo $_GET['status']; ?> alert-dismissible fade show container mt-4" role="alert">
            <?php echo htmlspecialchars($_GET['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <div class="row">
            <!-- Profile Sidebar -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="<?php echo $user['avatar']; ?>" class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px; object-fit: cover;" alt="User avatar">
                        <h3 class="card-title"><?php echo $user['username']; ?></h3>
                        <span class="badge <?php echo $user['role'] === 'Moderator' ? 'bg-danger' : 'bg-primary'; ?> mb-2"><?php echo $user['role']; ?></span>
                        <p class="text-muted mb-1"><i class="bi bi-geo-alt-fill"></i> <?php echo $user['location']; ?></p>
                        <p class="text-muted mb-3"><i class="bi bi-calendar"></i> Joined <?php echo date('M Y', strtotime($user['joinDate'])); ?></p>
                        
                        <div class="d-flex justify-content-center mb-3">
                            <?php if (!empty($user['github'])): ?>
                            <a href="https://<?php echo $user['github']; ?>" class="btn btn-outline-dark rounded-circle mx-1" target="_blank">
                                <i class="bi bi-github"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($user['twitter'])): ?>
                            <a href="https://<?php echo $user['twitter']; ?>" class="btn btn-outline-primary rounded-circle mx-1" target="_blank">
                                <i class="bi bi-twitter"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($user['website'])): ?>
                            <a href="<?php echo $user['website']; ?>" class="btn btn-outline-secondary rounded-circle mx-1" target="_blank">
                                <i class="bi bi-globe"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <?php if ($userId == $_SESSION['userId']): ?>
                                <a href="settings.php" class="btn btn-primary">
                                    <i class="bi bi-gear"></i> Edit Profile
                                </a>
                                <a href="dashboard.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            <?php else: ?>
                                <button class="btn btn-primary">
                                    <i class="bi bi-envelope"></i> Message
                                </button>
                                <button class="btn btn-outline-secondary">
                                    <i class="bi bi-person-plus"></i> Follow
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="mb-2"><i class="bi bi-file-text fs-4 text-primary"></i></div>
                                <h5><?php echo $user['posts']; ?></h5>
                                <div class="text-muted small">Posts</div>
                            </div>
                            <div class="col-4">
                                <div class="mb-2"><i class="bi bi-star fs-4 text-warning"></i></div>
                                <h5><?php echo $user['reputation']; ?></h5>
                                <div class="text-muted small">Reputation</div>
                            </div>
                            <div class="col-4">
                                <div class="mb-2"><i class="bi bi-award fs-4 text-success"></i></div>
                                <h5><?php echo count($user['badges']); ?></h5>
                                <div class="text-muted small">Badges</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Badges Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Badges</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($user['badges'] as $badge): ?>
                            <span class="badge <?php echo $badge['class']; ?> me-2 mb-2 py-2 px-3">
                                <i class="bi bi-award me-1"></i> <?php echo $badge['name']; ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab" aria-controls="about" aria-selected="true">About</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab" aria-controls="activity" aria-selected="false">Activity</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="blogs-tab" data-bs-toggle="tab" data-bs-target="#blogs" type="button" role="tab" aria-controls="blogs" aria-selected="false">Blogs</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="threads-tab" data-bs-toggle="tab" data-bs-target="#threads" type="button" role="tab" aria-controls="threads" aria-selected="false">Threads</button>
                    </li>
                </ul>
                
                <!-- Tab content -->
                <div class="tab-content">
                    <!-- About Tab -->
                    <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="about-tab">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">About Me</h5>
                            </div>
                            <div class="card-body">
                                <p><?php echo $user['bio']; ?></p>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 text-muted">Full Name:</div>
                                    <div class="col-sm-9"><?php echo $user['fullName']; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 text-muted">Email:</div>
                                    <div class="col-sm-9"><?php echo $user['email']; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 text-muted">Location:</div>
                                    <div class="col-sm-9"><?php echo $user['location']; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 text-muted">Website:</div>
                                    <div class="col-sm-9">
                                        <a href="<?php echo $user['website']; ?>" target="_blank" rel="noopener noreferrer">
                                            <?php echo $user['website']; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activity Tab -->
                    <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Activity</h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <?php foreach ($activity as $item): ?>
                                    <div class="d-flex mb-4">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-light p-2" style="width: 45px; height: 45px; text-align: center;">
                                                <i class="bi <?php echo $item['icon']; ?> fs-5"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">
                                                    <a href="<?php echo $item['link']; ?>" class="text-decoration-none">
                                                        <?php echo $item['title']; ?>
                                                    </a>
                                                </h6>
                                                <small class="text-muted"><?php echo date('M d, Y', strtotime($item['date'])); ?></small>
                                            </div>
                                            <span class="badge bg-secondary"><?php echo ucfirst($item['type']); ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Blogs Tab -->
                    <div class="tab-pane fade" id="blogs" role="tabpanel" aria-labelledby="blogs-tab">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo ($userId == $_SESSION['userId']) ? 'My' : $user['username'] . '\'s'; ?> Blogs</h5>
                                <?php if ($userId == $_SESSION['userId']): ?>
                                <a href="create-blog.php" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i> Write New Blog
                                </a>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <?php 
                                        echo BlogCard(
                                            1,
                                            'Getting Started with React Hooks',
                                            'Learn how to use useState and useEffect to manage state in your functional components.',
                                            $user['username'],
                                            '2023-04-01',
                                            'React',
                                            'partials/img/blog1.jpg',
                                            24,
                                            5
                                        );
                                        ?>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <?php 
                                        echo BlogCard(
                                            2,
                                            'Understanding JavaScript Promises',
                                            'A deep dive into Promises and async/await for better asynchronous code.',
                                            $user['username'],
                                            '2023-03-15',
                                            'JavaScript',
                                            null,
                                            18,
                                            3
                                        );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Threads Tab -->
                    <div class="tab-pane fade" id="threads" role="tabpanel" aria-labelledby="threads-tab">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo ($userId == $_SESSION['userId']) ? 'My' : $user['username'] . '\'s'; ?> Threads</h5>
                                <?php if ($userId == $_SESSION['userId']): ?>
                                <a href="create-thread.php" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i> Create New Thread
                                </a>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <?php 
                                echo ThreadCard(
                                    1,
                                    'How to optimize MySQL queries for large datasets?',
                                    $user['username'],
                                    '2023-03-28',
                                    'Databases',
                                    12,
                                    145,
                                    true,
                                    false
                                );
                                
                                echo ThreadCard(
                                    5,
                                    'Best practices for handling errors in Node.js applications',
                                    $user['username'],
                                    '2023-02-18',
                                    'Node.js',
                                    7,
                                    89,
                                    false,
                                    false
                                );
                                ?>
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
    <!-- Custom JS -->
    <script src="partials/js/script.js"></script>
</body>
</html> 