<?php
// Include database connection
include 'partials/_dbConnect.php';

// Get thread ID from URL parameter
$threadId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Mock data - in a real application, this would come from the database
$thread = [
    'id' => 1,
    'title' => 'How to optimize MySQL queries for large datasets?',
    'content' => 'I\'m working with a MySQL database that has grown to over 10 million rows in one table. My queries are starting to slow down significantly. What are some best practices for optimizing queries when dealing with large datasets? I\'ve already added indexes to the most commonly queried columns, but I\'m looking for more advanced techniques.',
    'author' => 'Database Guy',
    'date' => '2023-04-02',
    'category' => 'Databases',
    'views' => 145,
    'likes' => 18,
    'isResolved' => false,
    'tags' => ['MySQL', 'Database', 'Performance', 'Optimization']
];

// Thread replies
$replies = [
    [
        'id' => 1,
        'author' => 'SQL Expert',
        'content' => '<p>When dealing with large datasets in MySQL, there are several strategies you can implement:</p>
        <ol>
            <li><strong>Proper indexing</strong>: You mentioned you\'ve added indexes, which is a great start. Make sure they\'re the right indexes for your most common queries.</li>
            <li><strong>Query optimization</strong>: Use EXPLAIN to analyze your queries and identify bottlenecks.</li>
            <li><strong>Partitioning</strong>: Consider partitioning your tables if your queries typically filter by date ranges or other partition-friendly criteria.</li>
            <li><strong>Denormalization</strong>: In some cases, strategic denormalization can improve read performance.</li>
            <li><strong>Caching</strong>: Implement query caching for frequently accessed data.</li>
            <li><strong>Optimize your WHERE clauses</strong>: Ensure you\'re not using functions on indexed columns in WHERE clauses.</li>
        </ol>
        <p>Here\'s an example of using EXPLAIN to analyze a query:</p>',
        'date' => '2023-04-02 10:15:00',
        'likes' => 12,
        'is_solution' => true,
        'code' => 'EXPLAIN SELECT * FROM users 
WHERE last_login > DATE_SUB(NOW(), INTERVAL 30 DAY) 
AND status = "active";'
    ],
    [
        'id' => 2,
        'author' => 'Performance Guru',
        'content' => '<p>Adding to what SQL Expert said, consider these additional points:</p>
        <ol>
            <li><strong>Use covering indexes</strong>: Design indexes that cover all the columns in your query to avoid table lookups.</li>
            <li><strong>Limit result sets</strong>: Always use LIMIT in your queries, especially for pagination.</li>
            <li><strong>Consider alternative storage engines</strong>: InnoDB is great for most use cases, but sometimes MyISAM or even Memory engines might be better for specific needs.</li>
            <li><strong>Vertical partitioning</strong>: Split wide tables into multiple narrower ones.</li>
        </ol>
        <p>Also, make sure your server configuration is optimized. Key settings to look at include:</p>',
        'date' => '2023-04-02 11:30:00',
        'likes' => 8,
        'is_solution' => false,
        'code' => 'innodb_buffer_pool_size = 4G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT'
    ],
    [
        'id' => 3,
        'author' => 'Database Guy',
        'content' => '<p>Thank you both for the excellent advice! I\'ve implemented some of the suggestions:</p>
        <ol>
            <li>I ran EXPLAIN on my slowest queries and found that I was missing a composite index on two columns that are frequently queried together.</li>
            <li>I added proper LIMIT clauses to my pagination queries.</li>
            <li>I also implemented query caching for some of the most frequently accessed data.</li>
        </ol>
        <p>The performance has improved significantly. I\'m still looking into partitioning as that seems like it could help with my date-range queries. Does anyone have experience with how to determine the optimal partitioning strategy?</p>',
        'date' => '2023-04-03 09:45:00',
        'likes' => 5,
        'is_solution' => false,
        'code' => null
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $thread['title']; ?> - Dev Talks</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Prism.js CSS for syntax highlighting -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-okaidia.min.css" rel="stylesheet" />
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
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Thread -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-primary category-badge me-2"><?php echo $thread['category']; ?></span>
                            <?php if ($thread['isResolved']): ?>
                                <span class="badge bg-success me-2">Resolved</span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted"><?php echo date('M d, Y', strtotime($thread['date'])); ?></small>
                    </div>
                    <div class="card-body">
                        <h2 class="card-title"><?php echo $thread['title']; ?></h2>
                        
                        <div class="d-flex align-items-center mb-3">
                            <img src="partials/img/avatars/default.png" class="avatar me-2" alt="User avatar">
                            <div>
                                <h6 class="mb-0"><?php echo $thread['author']; ?></h6>
                                <small class="text-muted">Thread Starter</small>
                            </div>
                        </div>
                        
                        <div class="thread-content mb-4">
                            <p><?php echo $thread['content']; ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <?php
                            foreach ($thread['tags'] as $tag) {
                                echo '<a href="tag.php?tag=' . urlencode($tag) . '" class="text-decoration-none me-1 small">#' . $tag . '</a>';
                            }
                            ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button class="btn btn-sm btn-outline-primary like-btn me-2" data-thread-id="<?php echo $thread['id']; ?>">
                                    <i class="bi bi-heart"></i> <span class="count"><?php echo $thread['likes']; ?></span>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-bookmark"></i> Save
                                </button>
                            </div>
                            <div>
                                <span class="me-3">
                                    <i class="bi bi-eye"></i> <?php echo $thread['views']; ?> views
                                </span>
                                <span>
                                    <i class="bi bi-chat"></i> <?php echo count($replies); ?> replies
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Replies -->
                <h3 class="mb-3"><?php echo count($replies); ?> Replies</h3>
                
                <?php foreach ($replies as $index => $reply): ?>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="partials/img/avatars/default.png" class="avatar avatar-sm me-2" alt="User avatar">
                            <h6 class="mb-0"><?php echo $reply['author']; ?></h6>
                        </div>
                        <small class="text-muted"><?php echo date('M d, Y H:i', strtotime($reply['date'])); ?></small>
                    </div>
                    <div class="card-body">
                        <div class="reply-content mb-3">
                            <?php echo $reply['content']; ?>
                            
                            <?php if ($reply['code']): ?>
                            <div class="mt-3">
                                <?php echo CodeBlockCard($reply['code'], 'sql'); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button class="btn btn-sm btn-outline-primary like-btn me-2" data-reply-id="<?php echo $reply['id']; ?>">
                                    <i class="bi bi-heart"></i> <span class="count"><?php echo $reply['likes']; ?></span>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary me-2" data-bs-toggle="collapse" data-bs-target="#replyForm<?php echo $reply['id']; ?>">
                                    <i class="bi bi-reply"></i> Reply
                                </button>
                                <?php if ($reply['is_solution']): ?>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle-fill"></i> Solution
                                </span>
                                <?php else: ?>
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-check-circle"></i> Mark as Solution
                                </button>
                                <?php endif; ?>
                            </div>
                            <div>
                                <span>#<?php echo $index + 1; ?></span>
                            </div>
                        </div>
                        
                        <!-- Reply to this comment form (collapsed by default) -->
                        <div class="collapse mt-3" id="replyForm<?php echo $reply['id']; ?>">
                            <div class="card card-body bg-light">
                                <form action="partials/handleReply.php" method="post">
                                    <input type="hidden" name="threadId" value="<?php echo $thread['id']; ?>">
                                    <input type="hidden" name="parentId" value="<?php echo $reply['id']; ?>">
                                    <div class="mb-3">
                                        <textarea class="form-control" name="replyContent" rows="3" placeholder="Write your reply here..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Post Reply</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <!-- Post Reply Form -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Post Your Reply</h5>
                    </div>
                    <div class="card-body">
                        <form action="partials/handleReply.php" method="post">
                            <input type="hidden" name="threadId" value="<?php echo $thread['id']; ?>">
                            <div class="mb-3">
                                <textarea class="form-control" name="replyContent" rows="5" placeholder="Share your thoughts or solution..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="codeSnippet" class="form-label">Code Snippet (optional)</label>
                                <textarea class="form-control font-monospace" id="codeSnippet" name="codeSnippet" rows="3" placeholder="Paste your code here..."></textarea>
                            </div>
                            <div class="mb-3">
                                <select class="form-select" name="codeLanguage">
                                    <option value="sql">SQL</option>
                                    <option value="php">PHP</option>
                                    <option value="javascript">JavaScript</option>
                                    <option value="python">Python</option>
                                    <option value="java">Java</option>
                                    <option value="csharp">C#</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Post Reply</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Thread Stats Widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Thread Stats</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-calendar me-2"></i> Created</span>
                                <span><?php echo date('M d, Y', strtotime($thread['date'])); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-eye me-2"></i> Views</span>
                                <span><?php echo $thread['views']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-chat me-2"></i> Replies</span>
                                <span><?php echo count($replies); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-heart me-2"></i> Likes</span>
                                <span><?php echo $thread['likes']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-check-circle me-2"></i> Status</span>
                                <span class="badge <?php echo $thread['isResolved'] ? 'bg-success' : 'bg-warning'; ?>">
                                    <?php echo $thread['isResolved'] ? 'Resolved' : 'Open'; ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Related Threads Widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Related Threads</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <a href="thread.php?id=2" class="text-decoration-none">
                                    <h6 class="mb-1">MySQL vs PostgreSQL for large datasets</h6>
                                </a>
                                <div class="d-flex justify-content-between align-items-center small">
                                    <span class="text-muted">Mar 28, 2023</span>
                                    <span class="badge bg-primary">Databases</span>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <a href="thread.php?id=3" class="text-decoration-none">
                                    <h6 class="mb-1">Implementing database sharding in a web app</h6>
                                </a>
                                <div class="d-flex justify-content-between align-items-center small">
                                    <span class="text-muted">Mar 25, 2023</span>
                                    <span class="badge bg-primary">Databases</span>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <a href="thread.php?id=4" class="text-decoration-none">
                                    <h6 class="mb-1">Best practices for ORM with large datasets</h6>
                                </a>
                                <div class="d-flex justify-content-between align-items-center small">
                                    <span class="text-muted">Mar 22, 2023</span>
                                    <span class="badge bg-primary">Databases</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Popular Tags Widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Popular Tags</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="tag.php?tag=MySQL" class="btn btn-sm btn-outline-secondary">MySQL</a>
                            <a href="tag.php?tag=Database" class="btn btn-sm btn-outline-secondary">Database</a>
                            <a href="tag.php?tag=Performance" class="btn btn-sm btn-outline-secondary">Performance</a>
                            <a href="tag.php?tag=Optimization" class="btn btn-sm btn-outline-secondary">Optimization</a>
                            <a href="tag.php?tag=SQL" class="btn btn-sm btn-outline-secondary">SQL</a>
                            <a href="tag.php?tag=Indexing" class="btn btn-sm btn-outline-secondary">Indexing</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
    <!-- Custom JS -->
    <script src="partials/js/script.js"></script>
</body>
</html>