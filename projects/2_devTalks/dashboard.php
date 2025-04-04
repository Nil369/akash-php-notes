<?php
// Start session
session_start();

// Include auth helper and require login
include 'partials/auth_helper.php';
requireLogin();

// Include database connection
include 'partials/_dbConnect.php';

// Mock data for user's posts
$userThreads = [
    [
        'id' => 1,
        'title' => 'How to optimize MySQL queries for large datasets?',
        'date' => '2023-04-02',
        'category' => 'Databases',
        'replies' => 12,
        'views' => 145,
        'isResolved' => false
    ],
    [
        'id' => 3,
        'title' => 'Docker vs Kubernetes - When to use what?',
        'date' => '2023-03-27',
        'category' => 'DevOps',
        'replies' => 15,
        'views' => 210,
        'isResolved' => true
    ]
];

$userBlogs = [
    [
        'id' => 2,
        'title' => 'PHP 8.1 Features You Should Know',
        'date' => '2023-03-28',
        'category' => 'PHP',
        'likes' => 18,
        'comments' => 3
    ]
];

$recentActivity = [
    [
        'type' => 'comment',
        'content' => 'Great explanation! This helped me understand closures much better.',
        'date' => '2023-04-03 14:32:15',
        'target' => 'Understanding JavaScript Closures',
        'target_id' => 5,
        'target_type' => 'blog'
    ],
    [
        'type' => 'like',
        'content' => '',
        'date' => '2023-04-03 10:15:22',
        'target' => 'Building Reactive Forms in Angular',
        'target_id' => 7,
        'target_type' => 'blog'
    ],
    [
        'type' => 'reply',
        'content' => 'In my experience, Nginx works better for high-traffic static content, while Apache has better module support.',
        'date' => '2023-04-02 16:45:30',
        'target' => 'Apache vs Nginx - Pros and Cons',
        'target_id' => 12,
        'target_type' => 'thread'
    ],
    [
        'type' => 'bookmark',
        'content' => '',
        'date' => '2023-04-01 09:20:18',
        'target' => 'Getting Started with React Hooks',
        'target_id' => 1,
        'target_type' => 'blog'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dev Talks</title>
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
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h4 class="mb-0">Dashboard</h4>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#overview" class="list-group-item list-group-item-action active">
                            <i class="bi bi-speedometer2 me-2"></i> Overview
                        </a>
                        <a href="#threads" class="list-group-item list-group-item-action">
                            <i class="bi bi-chat-left-text me-2"></i> My Threads
                        </a>
                        <a href="#blogs" class="list-group-item list-group-item-action">
                            <i class="bi bi-file-text me-2"></i> My Blog Posts
                        </a>
                        <a href="#activity" class="list-group-item list-group-item-action">
                            <i class="bi bi-activity me-2"></i> Recent Activity
                        </a>
                        <a href="#bookmarks" class="list-group-item list-group-item-action">
                            <i class="bi bi-bookmark me-2"></i> Bookmarks
                        </a>
                        <a href="#drafts" class="list-group-item list-group-item-action">
                            <i class="bi bi-file-earmark me-2"></i> Drafts
                        </a>
                        <a href="settings.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-gear me-2"></i> Settings
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Stats Cards -->
                <section id="overview" class="mb-5">
                    <h2 class="mb-4">Overview</h2>
                    
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <h3 class="text-primary mb-0"><i class="bi bi-chat-left-text"></i></h3>
                                    <h3 class="mb-0"><?php echo count($userThreads); ?></h3>
                                    <p class="text-muted mb-0">Threads</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <h3 class="text-success mb-0"><i class="bi bi-file-text"></i></h3>
                                    <h3 class="mb-0"><?php echo count($userBlogs); ?></h3>
                                    <p class="text-muted mb-0">Blog Posts</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <h3 class="text-info mb-0"><i class="bi bi-hand-thumbs-up"></i></h3>
                                    <h3 class="mb-0">24</h3>
                                    <p class="text-muted mb-0">Received Likes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <h3 class="text-warning mb-0"><i class="bi bi-reply"></i></h3>
                                    <h3 class="mb-0">18</h3>
                                    <p class="text-muted mb-0">Replies</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- My Threads -->
                <section id="threads" class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>My Threads</h2>
                        <a href="create-thread.php" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> New Thread
                        </a>
                    </div>
                    
                    <div class="card">
                        <div class="list-group list-group-flush">
                            <?php if(count($userThreads) > 0): ?>
                                <?php foreach($userThreads as $thread): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-1">
                                            <a href="thread.php?id=<?php echo $thread['id']; ?>" class="text-decoration-none">
                                                <?php echo $thread['title']; ?>
                                            </a>
                                            <?php if($thread['isResolved']): ?>
                                            <span class="badge bg-success ms-2">Resolved</span>
                                            <?php endif; ?>
                                        </h5>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="thread.php?id=<?php echo $thread['id']; ?>">View</a></li>
                                                <li><a class="dropdown-item" href="edit-thread.php?id=<?php echo $thread['id']; ?>">Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center small mt-2">
                                        <div>
                                            <span class="badge bg-primary"><?php echo $thread['category']; ?></span>
                                            <span class="text-muted ms-2"><?php echo $thread['date']; ?></span>
                                        </div>
                                        <div>
                                            <span class="me-3"><i class="bi bi-eye me-1"></i> <?php echo $thread['views']; ?></span>
                                            <span><i class="bi bi-chat me-1"></i> <?php echo $thread['replies']; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="list-group-item text-center py-4">
                                    <p class="mb-2"><i class="bi bi-chat-square-text fs-1 text-muted"></i></p>
                                    <h5>You haven't created any threads yet</h5>
                                    <p class="text-muted mb-3">Start a discussion by creating your first thread</p>
                                    <a href="create-thread.php" class="btn btn-primary">Create Thread</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                
                <!-- My Blog Posts -->
                <section id="blogs" class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>My Blog Posts</h2>
                        <a href="create-blog.php" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> New Blog Post
                        </a>
                    </div>
                    
                    <div class="card">
                        <div class="list-group list-group-flush">
                            <?php if(count($userBlogs) > 0): ?>
                                <?php foreach($userBlogs as $blog): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-1">
                                            <a href="blog.php?id=<?php echo $blog['id']; ?>" class="text-decoration-none">
                                                <?php echo $blog['title']; ?>
                                            </a>
                                        </h5>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="blog.php?id=<?php echo $blog['id']; ?>">View</a></li>
                                                <li><a class="dropdown-item" href="edit-blog.php?id=<?php echo $blog['id']; ?>">Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center small mt-2">
                                        <div>
                                            <span class="badge bg-primary"><?php echo $blog['category']; ?></span>
                                            <span class="text-muted ms-2"><?php echo $blog['date']; ?></span>
                                        </div>
                                        <div>
                                            <span class="me-3"><i class="bi bi-heart me-1"></i> <?php echo $blog['likes']; ?></span>
                                            <span><i class="bi bi-chat me-1"></i> <?php echo $blog['comments']; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="list-group-item text-center py-4">
                                    <p class="mb-2"><i class="bi bi-file-earmark-text fs-1 text-muted"></i></p>
                                    <h5>You haven't written any blog posts yet</h5>
                                    <p class="text-muted mb-3">Share your knowledge by writing your first blog post</p>
                                    <a href="create-blog.php" class="btn btn-primary">Write Blog Post</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                
                <!-- Recent Activity -->
                <section id="activity" class="mb-5">
                    <h2 class="mb-4">Recent Activity</h2>
                    
                    <div class="card">
                        <div class="list-group list-group-flush">
                            <?php if(count($recentActivity) > 0): ?>
                                <?php foreach($recentActivity as $activity): ?>
                                <div class="list-group-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <?php if($activity['type'] == 'comment' || $activity['type'] == 'reply'): ?>
                                                <div class="bg-light rounded-circle p-2">
                                                    <i class="bi bi-chat-text text-primary"></i>
                                                </div>
                                            <?php elseif($activity['type'] == 'like'): ?>
                                                <div class="bg-light rounded-circle p-2">
                                                    <i class="bi bi-heart-fill text-danger"></i>
                                                </div>
                                            <?php elseif($activity['type'] == 'bookmark'): ?>
                                                <div class="bg-light rounded-circle p-2">
                                                    <i class="bi bi-bookmark-fill text-warning"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">
                                                    <?php if($activity['type'] == 'comment' || $activity['type'] == 'reply'): ?>
                                                        Commented on 
                                                    <?php elseif($activity['type'] == 'like'): ?>
                                                        Liked 
                                                    <?php elseif($activity['type'] == 'bookmark'): ?>
                                                        Bookmarked 
                                                    <?php endif; ?>
                                                    <a href="<?php echo $activity['target_type'] == 'blog' ? 'blog.php?id=' : 'thread.php?id='; ?><?php echo $activity['target_id']; ?>" class="text-decoration-none">
                                                        <?php echo $activity['target']; ?>
                                                    </a>
                                                </h6>
                                                <small class="text-muted"><?php echo date('M d, g:i a', strtotime($activity['date'])); ?></small>
                                            </div>
                                            <?php if($activity['content']): ?>
                                                <p class="mb-0 mt-1 small text-muted"><?php echo $activity['content']; ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="list-group-item text-center py-4">
                                    <p class="mb-2"><i class="bi bi-activity fs-1 text-muted"></i></p>
                                    <h5>No recent activity</h5>
                                    <p class="text-muted">Your recent interactions will appear here</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                
                <!-- Bookmarks Section -->
                <section id="bookmarks" class="mb-5">
                    <h2 class="mb-4">Bookmarks</h2>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Saved Threads</h5>
                                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                                    </div>
                                </div>
                                <div class="list-group list-group-flush">
                                    <a href="thread.php?id=7" class="list-group-item list-group-item-action">
                                        <h6 class="mb-1">Optimizing React Performance</h6>
                                        <small class="text-muted">React • April 1, 2023</small>
                                    </a>
                                    <a href="thread.php?id=12" class="list-group-item list-group-item-action">
                                        <h6 class="mb-1">Apache vs Nginx - Pros and Cons</h6>
                                        <small class="text-muted">DevOps • March 28, 2023</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Saved Blog Posts</h5>
                                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                                    </div>
                                </div>
                                <div class="list-group list-group-flush">
                                    <a href="blog.php?id=1" class="list-group-item list-group-item-action">
                                        <h6 class="mb-1">Getting Started with React Hooks</h6>
                                        <small class="text-muted">React • April 1, 2023</small>
                                    </a>
                                    <a href="blog.php?id=5" class="list-group-item list-group-item-action">
                                        <h6 class="mb-1">Understanding JavaScript Closures</h6>
                                        <small class="text-muted">JavaScript • March 26, 2023</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Drafts Section -->
                <section id="drafts" class="mb-5">
                    <h2 class="mb-4">Drafts</h2>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Thread Drafts</h5>
                                </div>
                                <div class="card-body text-center py-4">
                                    <p class="mb-2"><i class="bi bi-file-earmark fs-1 text-muted"></i></p>
                                    <h5>No saved drafts</h5>
                                    <p class="text-muted mb-3">Draft threads will appear here</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Blog Post Drafts</h5>
                                </div>
                                <div class="list-group list-group-flush">
                                    <a href="edit-blog.php?id=draft-1" class="list-group-item list-group-item-action">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Advanced TypeScript Patterns</h6>
                                                <small class="text-muted">Last edited: March 30, 2023</small>
                                            </div>
                                            <button class="btn btn-sm btn-primary">Continue</button>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this item? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <?php include "components/footer.php"; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>
    <!-- Custom JS -->
    <script src="partials/js/script.js"></script>
    
    <script>
        // Smooth scrolling for sidebar links
        document.querySelectorAll('.list-group-item').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href && href.startsWith('#')) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        // Update active state
                        document.querySelectorAll('.list-group-item').forEach(item => {
                            item.classList.remove('active');
                        });
                        this.classList.add('active');
                        
                        // Scroll to element
                        window.scrollTo({
                            top: targetElement.offsetTop - 20,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
        
        // Set active menu item based on scroll position
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const scrollPosition = window.scrollY + 100;
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                const sectionId = section.getAttribute('id');
                
                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    document.querySelectorAll('.list-group-item').forEach(item => {
                        item.classList.remove('active');
                        if (item.getAttribute('href') === '#' + sectionId) {
                            item.classList.add('active');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html> 