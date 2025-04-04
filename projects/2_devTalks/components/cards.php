<?php

// Blog Card Component
function BlogCard($blogId, $title, $excerpt, $author, $date, $category, $imageUrl = null, $likes = 0, $comments = 0) {
    return '
    <div class="card blog-card">
        ' . ($imageUrl ? '<img src="' . $imageUrl . '" class="card-img-top" alt="Blog thumbnail">' : '') . '
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="badge bg-primary category-badge">' . $category . '</span>
                <small class="text-muted">' . date('M d, Y', strtotime($date)) . '</small>
            </div>
            <h5 class="card-title">' . $title . '</h5>
            <p class="card-text">' . $excerpt . '</p>
            <div class="d-flex align-items-center mb-3">
                <img src="partials/img/avatars/default.png" class="avatar avatar-sm me-2" alt="User avatar">
                <span class="small">' . $author . '</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-sm btn-outline-secondary like-btn" data-post-id="' . $blogId . '">
                        <i class="bi bi-heart"></i> <span class="count">' . $likes . '</span>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-chat"></i> <span class="count">' . $comments . '</span>
                    </button>
                </div>
                <a href="blog.php?id=' . $blogId . '" class="btn btn-sm btn-primary">Read More</a>
            </div>
        </div>
    </div>';
}

// Thread Card Component
function ThreadCard($threadId, $title, $author, $date, $category, $replies = 0, $views = 0, $isHot = false, $isSticky = false) {
    $statusBadge = '';
    if ($isSticky) {
        $statusBadge = '<span class="badge bg-warning me-1">Sticky</span>';
    }
    if ($isHot) {
        $statusBadge .= '<span class="badge bg-danger me-1">Hot</span>';
    }
    
    return '
    <div class="card thread-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="badge bg-primary category-badge me-1">' . $category . '</span>
                    ' . $statusBadge . '
                </div>
                <small class="text-muted">' . date('M d, Y', strtotime($date)) . '</small>
            </div>
            <h5 class="card-title">' . $title . '</h5>
            <div class="d-flex align-items-center mb-3">
                <img src="partials/img/avatars/default.png" class="avatar avatar-sm me-2" alt="User avatar">
                <span class="small">' . $author . '</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted me-3">
                        <i class="bi bi-chat"></i> ' . $replies . ' Replies
                    </small>
                    <small class="text-muted">
                        <i class="bi bi-eye"></i> ' . $views . ' Views
                    </small>
                </div>
                <a href="thread.php?id=' . $threadId . '" class="btn btn-sm btn-primary">View Thread</a>
            </div>
        </div>
    </div>';
}

// User Profile Card Component
function ProfileCard($userId, $username, $role, $joinDate, $posts = 0, $reputation = 0, $avatarUrl = null) {
    return '
    <div class="card profile-card">
        <div class="card-body text-center">
            <img src="' . ($avatarUrl ? $avatarUrl : 'partials/img/avatars/default.png') . '" class="avatar avatar-lg mb-3" alt="User avatar">
            <h5 class="card-title">' . $username . '</h5>
            <span class="badge bg-info mb-2">' . $role . '</span>
            <p class="card-text small">
                <i class="bi bi-calendar"></i> Joined ' . date('M Y', strtotime($joinDate)) . '<br>
                <i class="bi bi-file-text"></i> ' . $posts . ' Posts<br>
                <i class="bi bi-star"></i> ' . $reputation . ' Reputation
            </p>
            <a href="profile.php?id=' . $userId . '" class="btn btn-sm btn-primary">View Profile</a>
        </div>
    </div>';
}

// Comment Component
function CommentCard($commentId, $author, $content, $date, $avatarUrl = null) {
    return '
    <div class="card comment-card mb-2">
        <div class="card-body">
            <div class="d-flex mb-2">
                <img src="' . ($avatarUrl ? $avatarUrl : 'partials/img/avatars/default.png') . '" class="avatar avatar-sm me-2" alt="User avatar">
                <div>
                    <h6 class="mb-0">' . $author . '</h6>
                    <small class="text-muted">' . date('M d, Y H:i', strtotime($date)) . '</small>
                </div>
            </div>
            <p class="card-text">' . $content . '</p>
            <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-outline-secondary me-2" data-comment-id="' . $commentId . '">
                    <i class="bi bi-reply"></i> Reply
                </button>
                <button class="btn btn-sm btn-outline-secondary like-btn" data-comment-id="' . $commentId . '">
                    <i class="bi bi-heart"></i> Like
                </button>
            </div>
        </div>
    </div>';
}

// Code Block Card Component
function CodeBlockCard($code, $language = 'php') {
    return '
    <div class="card code-block mb-3">
        <div class="code-header">
            <span>' . $language . '</span>
            <button class="copy-btn">Copy</button>
        </div>
        <pre><code class="language-' . $language . '">' . htmlspecialchars($code) . '</code></pre>
    </div>';
}
?>
