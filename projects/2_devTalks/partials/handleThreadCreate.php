<?php
// Start session
session_start();

// Include auth helper
include 'auth_helper.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: ../index.php?status=danger&message=You must be logged in to create a thread");
    exit();
}

// Include database connection
include '_dbConnect.php';

// Process thread creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $threadTitle = isset($_POST["threadTitle"]) ? trim($_POST["threadTitle"]) : "";
    $threadCategory = isset($_POST["threadCategory"]) ? trim($_POST["threadCategory"]) : "";
    $threadTags = isset($_POST["threadTags"]) ? trim($_POST["threadTags"]) : "";
    $threadContent = isset($_POST["threadContent"]) ? trim($_POST["threadContent"]) : "";
    $codeSnippet = isset($_POST["codeSnippet"]) ? trim($_POST["codeSnippet"]) : "";
    $codeLanguage = isset($_POST["codeLanguage"]) ? trim($_POST["codeLanguage"]) : "";
    $notifyReplies = isset($_POST["notifyReplies"]) ? 1 : 0;
    $threadDraft = isset($_POST["threadDraft"]) ? 1 : 0;
    
    // Validate form data
    if (empty($threadTitle) || empty($threadCategory) || empty($threadContent)) {
        header("Location: ../create-thread.php?status=danger&message=Please fill in all required fields");
        exit();
    }
    
    // Sanitize inputs for database
    $userId = $_SESSION['userId'];
    $threadTitle = mysqli_real_escape_string($conn, $threadTitle);
    $threadCategory = mysqli_real_escape_string($conn, $threadCategory);
    $threadTags = mysqli_real_escape_string($conn, $threadTags);
    $threadContent = mysqli_real_escape_string($conn, $threadContent);
    $codeSnippet = mysqli_real_escape_string($conn, $codeSnippet);
    $codeLanguage = mysqli_real_escape_string($conn, $codeLanguage);
    
    // Insert the thread into the database
    $sql = "INSERT INTO threads (user_id, title, category, tags, content, code_snippet, code_language, notify_replies, is_draft, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "issssssii", $userId, $threadTitle, $threadCategory, $threadTags, $threadContent, $codeSnippet, $codeLanguage, $notifyReplies, $threadDraft);
        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
            $threadId = mysqli_insert_id($conn);
            $status = $threadDraft ? 'success' : 'success';
            $message = $threadDraft ? 'Your thread has been saved as a draft.' : 'Your thread has been posted successfully!';
            header("Location: ../thread.php?id=" . $threadId . "&status=" . $status . "&message=" . urlencode($message));
            exit();
        } else {
            header("Location: ../create-thread.php?status=danger&message=Error creating thread. Please try again.");
            exit();
        }
        
        mysqli_stmt_close($stmt);
    } else {
        header("Location: ../create-thread.php?status=danger&message=Database error. Please try again later.");
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