<?php
// Include authentication functions
require_once "auth/auth_functions.php";

// Check if user is logged in
$user_id = check_login();

require_once "_db/db_connect.php";

// Get search query
$searchTerm = isset($_GET['query']) ? trim($_GET['query']) : '';

// Default response
$response = [
    'data' => []
];

if (!empty($searchTerm)) {
    // Query to search in titles and descriptions with prepared statement
    $sql = "SELECT * FROM notes 
            WHERE (title LIKE ? 
            OR description LIKE ? 
            OR tag LIKE ?)
            AND user_id = ?
            AND is_archived = ?
            ORDER BY is_pinned DESC, created_at DESC";
    
    $stmt = mysqli_prepare($conn, $sql);
    $searchPattern = "%{$searchTerm}%";
    $is_archived = isset($_GET['view']) && $_GET['view'] == 'archived' ? 1 : 0;
    
    mysqli_stmt_bind_param($stmt, "sssii", $searchPattern, $searchPattern, $searchPattern, $user_id, $is_archived);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Format data for DataTables - DataTables expects an array of arrays for the rows
            // The order matches the columns in the table: Title, Description, Tag, Created, Actions
            
            // Create the actions column HTML
            $pin_icon = $row['is_pinned'] ? 'fa-thumbtack' : 'fa-thumbtack text-muted';
            $actions = '
                <div class="btn-group">
                    <a href="edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                    <a href="index.php?pin=' . $row['id'] . '" class="btn btn-sm btn-outline-warning"><i class="fas ' . $pin_icon . '"></i></a>
                    <a href="index.php?archive=' . $row['id'] . '" class="btn btn-sm btn-outline-secondary"><i class="fas fa-archive"></i></a>
                    <a href="index.php?delete=' . $row['id'] . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Are you sure you want to delete?\')"><i class="fas fa-trash-alt"></i></a>
                </div>';
            
            // Format tag with badge
            $tag = '<span class="badge bg-secondary tag">' . htmlspecialchars($row['tag']) . '</span>';
            
            // Add the row to the response as an array
            $response['data'][] = [
                htmlspecialchars($row['title']),
                nl2br(htmlspecialchars($row['description'])),
                $tag,
                date('M d, Y', strtotime($row['created_at'])),
                $actions,
                // Add hidden properties for mobile card view generation
                'id' => $row['id'],
                'is_pinned' => $row['is_pinned'],
                'is_archived' => $row['is_archived']
            ];
        }
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?> 