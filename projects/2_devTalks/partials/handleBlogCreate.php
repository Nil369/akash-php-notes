<?php
// Start session
session_start();

// Include auth helper
include 'auth_helper.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: ../index.php?status=danger&message=You must be logged in to create a blog post");
    exit();
}

// Include database connection
include '_dbConnect.php';

// Process blog creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $blogTitle = isset($_POST["blogTitle"]) ? trim($_POST["blogTitle"]) : "";
    $blogCategory = isset($_POST["blogCategory"]) ? trim($_POST["blogCategory"]) : "";
    $blogTags = isset($_POST["blogTags"]) ? trim($_POST["blogTags"]) : "";
    $blogExcerpt = isset($_POST["blogExcerpt"]) ? trim($_POST["blogExcerpt"]) : "";
    $blogContent = isset($_POST["blogContent"]) ? trim($_POST["blogContent"]) : "";
    $saveAsDraft = isset($_POST["saveAsDraft"]) ? 1 : 0;
    
    // Validate form data
    if (empty($blogTitle) || empty($blogCategory) || empty($blogContent)) {
        header("Location: ../create-blog.php?status=danger&message=Please fill in all required fields");
        exit();
    }
    
    // Handle featured image upload
    $featuredImage = null;
    if (isset($_FILES['featuredImage']) && $_FILES['featuredImage']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        $filename = $_FILES['featuredImage']['name'];
        $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Check if file extension is allowed
        if (in_array($fileExt, $allowed)) {
            // Create unique filename
            $newFilename = uniqid('blog_') . '.' . $fileExt;
            $uploadDir = '../partials/img/blogs/';
            
            // Create upload directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $uploadPath = $uploadDir . $newFilename;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['featuredImage']['tmp_name'], $uploadPath)) {
                $featuredImage = 'partials/img/blogs/' . $newFilename;
            } else {
                header("Location: ../create-blog.php?status=danger&message=Failed to upload image. Please try again.");
                exit();
            }
        } else {
            header("Location: ../create-blog.php?status=danger&message=Invalid file format. Allowed formats: jpg, jpeg, png, gif");
            exit();
        }
    }
    
    // Sanitize inputs for database
    $userId = $_SESSION['userId'];
    $blogTitle = mysqli_real_escape_string($conn, $blogTitle);
    $blogCategory = mysqli_real_escape_string($conn, $blogCategory);
    $blogTags = mysqli_real_escape_string($conn, $blogTags);
    $blogExcerpt = mysqli_real_escape_string($conn, $blogExcerpt);
    $blogContent = mysqli_real_escape_string($conn, $blogContent);
    $featuredImage = $featuredImage ? mysqli_real_escape_string($conn, $featuredImage) : null;
    
    // Insert the blog post into the database
    $sql = "INSERT INTO blog_posts (user_id, title, category, tags, excerpt, content, featured_image, is_draft, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "issssssi", $userId, $blogTitle, $blogCategory, $blogTags, $blogExcerpt, $blogContent, $featuredImage, $saveAsDraft);
        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
            $blogId = mysqli_insert_id($conn);
            $status = $saveAsDraft ? 'success' : 'success';
            $message = $saveAsDraft ? 'Your blog post has been saved as a draft.' : 'Your blog post has been published successfully!';
            header("Location: ../blog.php?id=" . $blogId . "&status=" . $status . "&message=" . urlencode($message));
            exit();
        } else {
            header("Location: ../create-blog.php?status=danger&message=Error creating blog post. Please try again.");
            exit();
        }
        
        mysqli_stmt_close($stmt);
    } else {
        header("Location: ../create-blog.php?status=danger&message=Database error. Please try again later.");
        exit();
    }
} else {
    // Not a POST request, redirect to home
    header("Location: ../index.php");
    exit();
}

// Close database connection
mysqli_close($conn);
?> 