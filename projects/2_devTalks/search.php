<?php
// Include database connection
include 'partials/_dbConnect.php';

// Get search query from GET parameter
$query = isset($_GET['q']) ? $_GET['q'] : '';
$categorySlug = isset($_GET['cat']) ? $_GET['cat'] : '';

// Sanitize inputs
$query = mysqli_real_escape_string($conn, $query);
$categorySlug = mysqli_real_escape_string($conn, $categorySlug);

// Check if query is not empty
if (empty($query)) {
    header("Location: index.php");
    exit();
}

// Set up category filter if provided
$categoryId = null;
$categoryName = '';
if (!empty($categorySlug)) {
    $sql = "SELECT id, name FROM categories WHERE slug = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $categorySlug);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($category = mysqli_fetch_assoc($result)) {
            $categoryId = $category['id'];
            $categoryName = $category['name'];
        }
    }
}

// Search results arrays
$threads = [];
$blogs = [];
$users = [];

// Search in threads
if (strlen($query) >= 3) {
    // Prepare search query for threads
    $searchSql = "SELECT t.id, t.title, t.content, t.created_at, t.views, t.is_sticky,
                    u.username, c.name as category 
                 FROM threads t
                 JOIN users u ON t.user_id = u.id
                 JOIN categories c ON t.category_id = c.id
                 WHERE (t.title LIKE ? OR t.content LIKE ?) 
                    AND t.is_draft = 0 ";
    
    // Add category filter if provided
    if ($categoryId) {
        $searchSql .= "AND t.category_id = ? ";
    }
    
    $searchSql .= "ORDER BY t.is_sticky DESC, t.created_at DESC LIMIT 10";
    
    $searchPattern = "%$query%";
    $stmt = mysqli_prepare($conn, $searchSql);
    
    if ($stmt) {
        if ($categoryId) {
            mysqli_stmt_bind_param($stmt, "ssi", $searchPattern, $searchPattern, $categoryId);
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $searchPattern, $searchPattern);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            // Add reply count
            $replySql = "SELECT COUNT(*) as count FROM thread_replies WHERE thread_id = ?";
            $replyStmt = mysqli_prepare($conn, $replySql);
            mysqli_stmt_bind_param($replyStmt, "i", $row['id']);
            mysqli_stmt_execute($replyStmt);
            $replyResult = mysqli_stmt_get_result($replyStmt);
            $replyCount = mysqli_fetch_assoc($replyResult)['count'];
            
            $row['replies'] = $replyCount;
            
            // Truncate content for preview
            $row['content'] = substr(strip_tags($row['content']), 0, 150) . '...';
            $threads[] = $row;
        }
    }
    
    // Search in blogs
    $searchSql = "SELECT b.id, b.title, b.excerpt, b.content, b.created_at, b.views, 
                    u.username, c.name as category, b.featured_image 
                 FROM blogs b
                 JOIN users u ON b.user_id = u.id
                 JOIN categories c ON b.category_id = c.id
                 WHERE (b.title LIKE ? OR b.content LIKE ? OR b.excerpt LIKE ?) 
                    AND b.is_draft = 0 ";
    
    // Add category filter if provided
    if ($categoryId) {
        $searchSql .= "AND b.category_id = ? ";
    }
    
    $searchSql .= "ORDER BY b.created_at DESC LIMIT 10";
    
    $stmt = mysqli_prepare($conn, $searchSql);
    
    if ($stmt) {
        if ($categoryId) {
            mysqli_stmt_bind_param($stmt, "sssi", $searchPattern, $searchPattern, $searchPattern, $categoryId);
        } else {
            mysqli_stmt_bind_param($stmt, "sss", $searchPattern, $searchPattern, $searchPattern);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            // Use excerpt if available, otherwise truncate content
            if (empty($row['excerpt'])) {
                $row['excerpt'] = substr(strip_tags($row['content']), 0, 150) . '...';
            }
            unset($row['content']); // No need to send full content
            $blogs[] = $row;
        }
    }
    
    // Search for users (only if not filtering by category)
    if (!$categoryId) {
        $searchSql = "SELECT u.id, u.username, u.created_at, 
                        up.avatar, up.bio
                     FROM users u
                     LEFT JOIN user_profiles up ON u.id = up.user_id
                     WHERE u.username LIKE ? 
                     ORDER BY u.username ASC 
                     LIMIT 5";
        
        $stmt = mysqli_prepare($conn, $searchSql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $searchPattern);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            while ($row = mysqli_fetch_assoc($result)) {
                // Truncate bio if needed
                if (!empty($row['bio'])) {
                    $row['bio'] = substr($row['bio'], 0, 100) . '...';
                }
                $users[] = $row;
            }
        }
    }
}

// Include components
include "components/cards.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results: <?php echo htmlspecialchars($query); ?> - Dev Talks</title>
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
    ?>

    <div class="container mt-4">
        <!-- Search Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="mb-3">
                            <i class="bi bi-search me-2"></i>
                            Search Results for "<?php echo htmlspecialchars($query); ?>"
                            <?php if (!empty($categoryName)): ?>
                                <span class="badge bg-secondary ms-2">in <?php echo $categoryName; ?></span>
                            <?php endif; ?>
                        </h1>
                        
                        <form action="search.php" method="get" class="mb-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search..." name="q" value="<?php echo htmlspecialchars($query); ?>">
                                <?php if (!empty($categorySlug)): ?>
                                    <input type="hidden" name="cat" value="<?php echo htmlspecialchars($categorySlug); ?>">
                                <?php endif; ?>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main content -->
            <div class="col-lg-8">
                <!-- Results Stats -->
                <div class="alert alert-info">
                    <p class="mb-0">
                        Found <?php echo count($threads) + count($blogs) + count($users); ?> results
                        (<?php echo count($threads); ?> threads, 
                        <?php echo count($blogs); ?> blogs
                        <?php if (!$categoryId): ?>, <?php echo count($users); ?> users<?php endif; ?>)
                    </p>
                </div>
                
                <!-- Threads section -->
                <?php if (count($threads) > 0): ?>
                <h2 class="mb-3">Threads</h2>
                
                <?php
                foreach ($threads as $thread) {
                    echo ThreadCard(
                        $thread['id'],
                        $thread['title'],
                        $thread['username'],
                        $thread['created_at'],
                        $thread['category'],
                        $thread['replies'],
                        $thread['views'],
                        false,
                        $thread['is_sticky']
                    );
                }
                ?>
                <?php endif; ?>

                <!-- Blogs section -->
                <?php if (count($blogs) > 0): ?>
                <h2 class="mb-3 mt-4">Blogs</h2>
                
                <div class="row">
                    <?php
                    foreach ($blogs as $blog) {
                        echo '<div class="col-md-6 mb-4">';
                        echo BlogCard(
                            $blog['id'],
                            $blog['title'],
                            $blog['excerpt'],
                            $blog['username'],
                            $blog['created_at'],
                            $blog['category'],
                            $blog['featured_image'],
                            0, // likes count would come from database
                            0  // comments count would come from database
                        );
                        echo '</div>';
                    }
                    ?>
                </div>
                <?php endif; ?>

                <!-- Users section -->
                <?php if (count($users) > 0): ?>
                <h2 class="mb-3 mt-4">Users</h2>
                
                <div class="row">
                    <?php
                    foreach ($users as $user) {
                        echo '<div class="col-md-6 col-lg-4 mb-4">';
                        echo ProfileCard(
                            $user['id'],
                            $user['username'],
                            'Member', // Role would come from database
                            $user['created_at'],
                            0, // Posts count would come from database
                            0, // Reputation would come from database
                            $user['avatar']
                        );
                        echo '</div>';
                    }
                    ?>
                </div>
                <?php endif; ?>

                <!-- No results message -->
                <?php if (count($threads) + count($blogs) + count($users) == 0): ?>
                <div class="alert alert-warning">
                    <h4 class="alert-heading">No results found!</h4>
                    <p>We couldn't find any content matching your search query. Here are some tips:</p>
                    <ul>
                        <li>Check your spelling</li>
                        <li>Try using more general keywords</li>
                        <li>Try using different keywords</li>
                        <li>Try searching without category filters</li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right sidebar -->
            <div class="col-lg-4">
                <!-- Filter widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Filter Results</h5>
                    </div>
                    <div class="card-body">
                        <form action="search.php" method="get">
                            <input type="hidden" name="q" value="<?php echo htmlspecialchars($query); ?>">
                            
                            <div class="mb-3">
                                <label for="categoryFilter" class="form-label">Category</label>
                                <select class="form-select" id="categoryFilter" name="cat" onchange="this.form.submit()">
                                    <option value="">All Categories</option>
                                    <?php
                                    $sql = "SELECT id, name, slug FROM categories ORDER BY name ASC";
                                    $result = mysqli_query($conn, $sql);
                                    
                                    while ($category = mysqli_fetch_assoc($result)) {
                                        $selected = ($categorySlug == $category['slug']) ? 'selected' : '';
                                        echo '<option value="' . $category['slug'] . '" ' . $selected . '>';
                                        echo $category['name'];
                                        echo '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Recent Searches (if implemented) -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Popular Searches</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="search.php?q=javascript" class="text-decoration-none">javascript</a>
                            <a href="search.php?q=php" class="text-decoration-none">php</a>
                            <a href="search.php?q=mysql" class="text-decoration-none">mysql</a>
                            <a href="search.php?q=react" class="text-decoration-none">react</a>
                            <a href="search.php?q=laravel" class="text-decoration-none">laravel</a>
                            <a href="search.php?q=node.js" class="text-decoration-none">node.js</a>
                            <a href="search.php?q=python" class="text-decoration-none">python</a>
                            <a href="search.php?q=api" class="text-decoration-none">api</a>
                        </div>
                    </div>
                </div>

                <!-- Categories widget -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Browse Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                            // Reset result pointer
                            mysqli_data_seek($result, 0);
                            
                            while ($category = mysqli_fetch_assoc($result)) {
                                echo '<a href="category.php?cat=' . $category['slug'] . '" class="btn btn-sm btn-outline-secondary">';
                                echo $category['name'];
                                echo '</a>';
                            }
                            ?>
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