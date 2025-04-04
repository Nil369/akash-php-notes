<?php
// Start session
session_start();

// Include database connection
include '_dbConnect.php';

// Get search query from GET parameter
$query = isset($_GET['q']) ? $_GET['q'] : '';

// Sanitize input
$query = mysqli_real_escape_string($conn, $query);

// Check if query is not empty
if (empty($query)) {
    header("Location: ../index.php?error=emptysearch");
    exit();
}

// Search results arrays
$threads = [];
$blogs = [];
$users = [];

// Search in threads
if (strlen($query) >= 3) {
    // Prepare search query for threads
    $searchSql = "SELECT t.id, t.title, t.content, t.created_at, t.views, 
                    u.username, c.name as category 
                 FROM threads t
                 JOIN users u ON t.user_id = u.id
                 JOIN categories c ON t.category_id = c.id
                 WHERE (t.title LIKE ? OR t.content LIKE ?) 
                    AND t.is_draft = 0 
                 ORDER BY t.created_at DESC 
                 LIMIT 10";
    
    $searchPattern = "%$query%";
    $stmt = mysqli_prepare($conn, $searchSql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $searchPattern, $searchPattern);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
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
                    AND b.is_draft = 0 
                 ORDER BY b.created_at DESC 
                 LIMIT 10";
    
    $stmt = mysqli_prepare($conn, $searchSql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $searchPattern, $searchPattern, $searchPattern);
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
    
    // Search for users
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

// Prepare response
$response = [
    'query' => $query,
    'resultCount' => [
        'threads' => count($threads),
        'blogs' => count($blogs),
        'users' => count($users),
        'total' => count($threads) + count($blogs) + count($users)
    ],
    'threads' => $threads,
    'blogs' => $blogs,
    'users' => $users
];

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?> 