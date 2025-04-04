<?php
// Include database connection
include 'partials/_dbConnect.php';

// Get category from URL parameter
$categorySlug = isset($_GET['cat']) ? $_GET['cat'] : '';

// Sanitize input
$categorySlug = mysqli_real_escape_string($conn, $categorySlug);

// Check if category exists
$validCategory = false;
$categoryData = null;

if (!empty($categorySlug)) {
    // Query the database for the category
    $sql = "SELECT * FROM categories WHERE slug = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $categorySlug);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($categoryData = mysqli_fetch_assoc($result)) {
            $validCategory = true;
        }
    }
}

// If category not found, show error
if (!$validCategory) {
    $categoryName = "Category Not Found";
    $categoryDescription = "The requested category does not exist.";
    $categoryIcon = "bi-exclamation-circle";
    $threads = [];
    $blogs = [];
} else {
    $categoryName = $categoryData['name'];
    $categoryDescription = $categoryData['description'];
    $categoryIcon = $categoryData['icon'] ?? 'bi-folder';
    $categoryId = $categoryData['id'];
    
    // Get threads for this category
    $threads = [];
    $sql = "SELECT t.*, u.username, 
               (SELECT COUNT(*) FROM thread_replies WHERE thread_id = t.id) as reply_count
            FROM threads t 
            JOIN users u ON t.user_id = u.id 
            WHERE t.category_id = ? AND t.is_draft = 0 
            ORDER BY t.is_sticky DESC, t.created_at DESC 
            LIMIT 10";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $categoryId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $threads[] = $row;
        }
    }
    
    // Get blogs for this category
    $blogs = [];
    $sql = "SELECT b.*, u.username FROM blogs b 
            JOIN users u ON b.user_id = u.id 
            WHERE b.category_id = ? AND b.is_draft = 0 
            ORDER BY b.is_featured DESC, b.created_at DESC 
            LIMIT 6";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $categoryId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $blogs[] = $row;
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
    <title><?php echo $categoryName; ?> - Dev Talks</title>
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
        <!-- Category Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="<?php echo $categoryIcon; ?> fs-1 me-3"></i>
                            <div>
                                <h1 class="mb-1"><?php echo $categoryName; ?></h1>
                                <p class="mb-0"><?php echo $categoryDescription; ?></p>
                            </div>
                        </div>
                        
                        <?php if ($validCategory): ?>
                        <div class="d-flex gap-2 mt-3">
                            <a href="create-thread.php?category=<?php echo $categorySlug; ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> New Thread
                            </a>
                            <a href="create-blog.php?category=<?php echo $categorySlug; ?>" class="btn btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i> Write Blog
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!$validCategory): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning">
                    <p class="mb-0"><strong>Category not found!</strong> The requested category does not exist. Please check the URL or browse the available categories below.</p>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Browse Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            // Get all categories
                            $sql = "SELECT * FROM categories ORDER BY name ASC";
                            $result = mysqli_query($conn, $sql);
                            
                            while ($category = mysqli_fetch_assoc($result)) {
                                echo '<div class="col-md-4 mb-3">';
                                echo '<a href="category.php?cat=' . $category['slug'] . '" class="text-decoration-none">';
                                echo '<div class="card h-100 border-light">';
                                echo '<div class="card-body">';
                                echo '<div class="d-flex align-items-center">';
                                echo '<i class="' . ($category['icon'] ?? 'bi-folder') . ' me-2"></i>';
                                echo '<h5 class="mb-0">' . $category['name'] . '</h5>';
                                echo '</div>';
                                echo '<p class="small text-muted mt-2">' . substr($category['description'], 0, 100) . '...</p>';
                                echo '</div>';
                                echo '</div>';
                                echo '</a>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        
        <!-- Main content for valid category -->
        <div class="row">
            <!-- Left column: Content -->
            <div class="col-lg-8">
                <?php if (count($threads) > 0): ?>
                <!-- Threads section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Threads</h2>
                    <a href="threads.php?cat=<?php echo $categorySlug; ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                
                <?php
                foreach ($threads as $thread) {
                    echo ThreadCard(
                        $thread['id'],
                        $thread['title'],
                        $thread['username'],
                        $thread['created_at'],
                        $categoryName,
                        $thread['reply_count'],
                        $thread['views'],
                        false,
                        $thread['is_sticky']
                    );
                }
                ?>
                <?php else: ?>
                <div class="alert alert-info mb-4">
                    <p class="mb-0">No threads found in this category. Be the first to start a discussion!</p>
                </div>
                <?php endif; ?>

                <?php if (count($blogs) > 0): ?>
                <!-- Blogs section -->
                <div class="d-flex justify-content-between align-items-center my-4">
                    <h2>Blogs</h2>
                    <a href="blogs.php?cat=<?php echo $categorySlug; ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                
                <div class="row">
                    <?php
                    foreach ($blogs as $blog) {
                        echo '<div class="col-md-6 mb-4">';
                        echo BlogCard(
                            $blog['id'],
                            $blog['title'],
                            $blog['excerpt'] ?? substr(strip_tags($blog['content']), 0, 150) . '...',
                            $blog['username'],
                            $blog['created_at'],
                            $categoryName,
                            $blog['featured_image'],
                            0, // likes count would come from database
                            0  // comments count would come from database
                        );
                        echo '</div>';
                    }
                    ?>
                </div>
                <?php else: ?>
                <div class="alert alert-info my-4">
                    <p class="mb-0">No blog posts found in this category. Be the first to write a blog!</p>
                </div>
                <?php endif; ?>
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
                                <input type="hidden" name="cat" value="<?php echo $categorySlug; ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Subcategories widget (if applicable) -->
                <?php
                $sql = "SELECT * FROM categories WHERE parent_id = ? ORDER BY name ASC";
                $stmt = mysqli_prepare($conn, $sql);
                
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $categoryId);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    
                    if (mysqli_num_rows($result) > 0) {
                        echo '<div class="card mb-4">';
                        echo '<div class="card-header">';
                        echo '<h5 class="mb-0">Subcategories</h5>';
                        echo '</div>';
                        echo '<div class="card-body">';
                        echo '<div class="d-flex flex-wrap gap-2">';
                        
                        while ($subcat = mysqli_fetch_assoc($result)) {
                            echo '<a href="category.php?cat=' . $subcat['slug'] . '" class="btn btn-sm btn-outline-secondary">';
                            echo $subcat['name'];
                            echo '</a>';
                        }
                        
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>

                <!-- Related categories widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Related Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                            // Get other categories (excluding current one)
                            $sql = "SELECT * FROM categories WHERE id != ? ORDER BY RAND() LIMIT 8";
                            $stmt = mysqli_prepare($conn, $sql);
                            
                            if ($stmt) {
                                mysqli_stmt_bind_param($stmt, "i", $categoryId);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                                
                                while ($relCat = mysqli_fetch_assoc($result)) {
                                    echo '<a href="category.php?cat=' . $relCat['slug'] . '" class="btn btn-sm btn-outline-secondary">';
                                    echo $relCat['name'];
                                    echo '</a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Stats widget -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Category Stats</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Total Threads
                                <span class="badge bg-primary rounded-pill">
                                    <?php
                                    $sql = "SELECT COUNT(*) as count FROM threads WHERE category_id = ? AND is_draft = 0";
                                    $stmt = mysqli_prepare($conn, $sql);
                                    
                                    if ($stmt) {
                                        mysqli_stmt_bind_param($stmt, "i", $categoryId);
                                        mysqli_stmt_execute($stmt);
                                        $result = mysqli_stmt_get_result($stmt);
                                        $count = mysqli_fetch_assoc($result)['count'];
                                        echo $count;
                                    } else {
                                        echo "0";
                                    }
                                    ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Total Blogs
                                <span class="badge bg-primary rounded-pill">
                                    <?php
                                    $sql = "SELECT COUNT(*) as count FROM blogs WHERE category_id = ? AND is_draft = 0";
                                    $stmt = mysqli_prepare($conn, $sql);
                                    
                                    if ($stmt) {
                                        mysqli_stmt_bind_param($stmt, "i", $categoryId);
                                        mysqli_stmt_execute($stmt);
                                        $result = mysqli_stmt_get_result($stmt);
                                        $count = mysqli_fetch_assoc($result)['count'];
                                        echo $count;
                                    } else {
                                        echo "0";
                                    }
                                    ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Last Activity
                                <span class="text-muted small">
                                    <?php
                                    $sql = "SELECT created_at FROM 
                                            (SELECT created_at FROM threads WHERE category_id = ? AND is_draft = 0
                                             UNION
                                             SELECT created_at FROM blogs WHERE category_id = ? AND is_draft = 0)
                                             as combined
                                             ORDER BY created_at DESC LIMIT 1";
                                    $stmt = mysqli_prepare($conn, $sql);
                                    
                                    if ($stmt) {
                                        mysqli_stmt_bind_param($stmt, "ii", $categoryId, $categoryId);
                                        mysqli_stmt_execute($stmt);
                                        $result = mysqli_stmt_get_result($stmt);
                                        if ($date = mysqli_fetch_assoc($result)) {
                                            echo date('M d, Y', strtotime($date['created_at']));
                                        } else {
                                            echo "No activity";
                                        }
                                    } else {
                                        echo "Unknown";
                                    }
                                    ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
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