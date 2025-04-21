<?php
// Start session for authentication
session_start();

// Load environment variables
require_once "./_db/env.php";
loadEnv();

// Check if we're using Aiven cloud database
$servername = getenv("DB_HOST");
$using_pdo = false;

if (strpos($servername, 'aivencloud.com') !== false) {
    // Use dedicated Aiven connection
    require_once "./_db/aiven_connect.php";
} else {
    // Regular mysqli connection for other databases
    require_once "./_db/db_connect.php";
}

// Check if user is authenticated
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;
$username = $is_logged_in ? $_SESSION['username'] : null;

// Include authentication functions
require_once "auth/auth_functions.php";

// Function to execute queries based on connection type
function execute_query($sql, $params, $conn, $using_pdo) {
    if ($using_pdo) {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } else {
        $stmt = mysqli_prepare($conn, $sql);
        if (count($params) > 0) {
            $types = str_repeat('i', count($params)); // Assuming all params are integers
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        return $stmt;
    }
}

// Delete note if delete button is clicked
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM notes WHERE id = ? AND user_id = ?";
    
    if ($using_pdo) {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id, $user_id]);
    } else {
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
        mysqli_stmt_execute($stmt);
    }
    
    header("Location: index.php");
}

// Toggle pin status
if (isset($_GET['pin'])) {
    $id = $_GET['pin'];
    $sql = "UPDATE notes SET is_pinned = NOT is_pinned WHERE id = ? AND user_id = ?";
    
    if ($using_pdo) {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id, $user_id]);
    } else {
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
        mysqli_stmt_execute($stmt);
    }
    
    header("Location: index.php");
}

// Toggle archive status
if (isset($_GET['archive'])) {
    $id = $_GET['archive'];
    $sql = "UPDATE notes SET is_archived = NOT is_archived WHERE id = ? AND user_id = ?";
    
    if ($using_pdo) {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id, $user_id]);
    } else {
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
        mysqli_stmt_execute($stmt);
    }
    
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iNotes - Your Digital Notebook</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        .note-card {
            transition: transform 0.3s ease;
        }
        .note-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .pinned-note {
            border-left: 4px solid #ffc107;
        }
        .archived-note {
            opacity: 0.7;
        }
        .tag {
            border-radius: 20px;
            font-size: 0.8rem;
            padding: 2px 10px;
        }
        
        /* Hide DataTables default search box */
        .dataTables_filter {
            display: none;
        }
        
        /* Dark mode styles */
        [data-bs-theme="dark"] {
            --bs-body-bg: #121212;
            --bs-body-color: #e0e0e0;
        }
        
        [data-bs-theme="dark"] .card {
            background-color: #1e1e1e;
            border-color: #333;
        }
        
        [data-bs-theme="dark"] .table {
            color: #e0e0e0;
            border-color: #333;
        }
        
        [data-bs-theme="dark"] .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        [data-bs-theme="dark"] .modal-content {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }
        
        [data-bs-theme="dark"] .form-control {
            background-color: #2c2c2c;
            border-color: #444;
            color: #e0e0e0;
        }
        
        [data-bs-theme="dark"] .pinned-note {
            border-left: 4px solid #ffc107;
        }
        
        /* Theme toggle button */
        .theme-toggle {
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            margin-left: 10px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .action-buttons {
                display: flex;
                flex-direction: column;
            }
            .action-buttons .btn {
                margin-bottom: 2px;
            }
        }
        
        @media (max-width: 576px) {
            .mobile-hide {
                display: none;
            }
            .action-icons .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
        
        /* Mobile card view for small screens */
        .note-mobile-card {
            display: none;
            margin-bottom: 15px;
            border-left: 4px solid transparent;
            transition: transform 0.3s ease;
        }
        
        .note-mobile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .note-mobile-card.pinned-card {
            border-left-color: #ffc107;
        }
        
        @media (max-width: 767px) {
            .note-table-container {
                display: none;
            }
            .note-mobile-view {
                display: block;
            }
            .note-mobile-card {
                display: block;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-sticky-note me-2"></i>iNotes
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo (!isset($_GET['view']) ? 'active' : ''); ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['view']) && $_GET['view'] == 'archived' ? 'active' : ''); ?>" href="index.php?view=archived">Archived</a>
                </li>
            </ul>
            <form class="d-flex mb-2 mb-lg-0 me-lg-3" id="searchForm">
                <input class="form-control me-2" type="search" placeholder="Search notes..." id="navbar-search">
                <button class="btn btn-outline-light d-none d-md-block" type="submit">Search</button>
            </form>
            
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="auth/profile.php"><i class="fas fa-id-card me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
                <!-- Theme Toggle Button -->
                <li class="nav-item">
                    <div class="theme-toggle btn btn-outline-light ms-lg-2 mt-2 mt-lg-0" id="themeToggle">
                        <i class="fas fa-sun me-1" id="lightIcon"></i>
                        <i class="fas fa-moon me-1 d-none" id="darkIcon"></i>
                        <span id="themeText">Dark</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container my-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>
                <?php
                if (isset($_GET['view']) && $_GET['view'] == 'archived') {
                    echo "Archived Notes";
                } else {
                    echo "My Notes";
                }
                ?>
            </h2>
        </div>
        <div class="col-md-6 text-md-end text-center mt-3 mt-md-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                <i class="fas fa-plus me-2"></i>Add Note
            </button>
        </div>
    </div>

    <!-- Notes Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Table View (for larger screens) -->
            <div class="note-table-container">
                <table id="notesTable" class="table table-hover dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Tag</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $is_archived = 0;
                        if (isset($_GET['view']) && $_GET['view'] == 'archived') {
                            $is_archived = 1;
                        }
                        
                        // Get user's notes
                        $sql = "SELECT * FROM notes WHERE user_id = ? AND is_archived = ? ORDER BY is_pinned DESC, created_at DESC";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "ii", $user_id, $is_archived);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $pinned_class = $row['is_pinned'] ? 'pinned-note' : '';
                                $pin_icon = $row['is_pinned'] ? 'fa-thumbtack' : 'fa-thumbtack text-muted';
                                
                                echo '<tr class="' . $pinned_class . '">';
                                echo '<td><strong>' . htmlspecialchars($row['title']) . '</strong></td>';
                                echo '<td>' . nl2br(htmlspecialchars($row['description'])) . '</td>';
                                echo '<td><span class="badge bg-secondary tag">' . htmlspecialchars($row['tag']) . '</span></td>';
                                echo '<td>' . date('M d, Y', strtotime($row['created_at'])) . '</td>';
                                echo '<td>
                                    <div class="btn-group">
                                        <a href="edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                        <a href="index.php?pin=' . $row['id'] . '" class="btn btn-sm btn-outline-warning"><i class="fas ' . $pin_icon . '"></i></a>
                                        <a href="index.php?archive=' . $row['id'] . '" class="btn btn-sm btn-outline-secondary"><i class="fas fa-archive"></i></a>
                                        <a href="index.php?delete=' . $row['id'] . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Are you sure you want to delete?\')"><i class="fas fa-trash-alt"></i></a>
                                    </div>
                                </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center">No notes found</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Mobile Card View (for smaller screens) -->
            <div class="note-mobile-view d-md-none">
                <?php
                // Reset result pointer
                mysqli_data_seek($result, 0);
                
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $pinned_class = $row['is_pinned'] ? 'pinned-card' : '';
                        $pin_icon = $row['is_pinned'] ? 'fa-thumbtack' : 'fa-thumbtack text-muted';
                        
                        echo '<div class="card note-mobile-card ' . $pinned_class . '">';
                        echo '<div class="card-header d-flex justify-content-between align-items-center">';
                        echo '<h5 class="mb-0">' . htmlspecialchars($row['title']) . '</h5>';
                        echo '<span class="badge bg-secondary tag">' . htmlspecialchars($row['tag']) . '</span>';
                        echo '</div>';
                        echo '<div class="card-body">';
                        echo '<p class="card-text">' . nl2br(htmlspecialchars($row['description'])) . '</p>';
                        echo '<p class="text-muted small mb-2"><i class="fas fa-calendar-alt me-1"></i> ' . date('M d, Y', strtotime($row['created_at'])) . '</p>';
                        echo '</div>';
                        echo '<div class="card-footer d-flex justify-content-between">';
                        echo '<div class="action-icons">';
                        echo '<a href="edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>';
                        echo '<a href="index.php?pin=' . $row['id'] . '" class="btn btn-sm btn-outline-warning me-1"><i class="fas ' . $pin_icon . '"></i></a>';
                        echo '<a href="index.php?archive=' . $row['id'] . '" class="btn btn-sm btn-outline-secondary me-1"><i class="fas fa-archive"></i></a>';
                        echo '<a href="index.php?delete=' . $row['id'] . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Are you sure you want to delete?\')"><i class="fas fa-trash-alt"></i></a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="alert alert-info text-center">No notes found</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Note</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="add.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tag" class="form-label">Tag</label>
                        <input type="text" class="form-control" id="tag" name="tag" placeholder="General">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_pinned" name="is_pinned" value="1">
                        <label class="form-check-label" for="is_pinned">
                            Pin this note
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-success">Save Note</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

<!-- Initialize DataTable -->
<script>
    $(document).ready(function() {
        // Initialize DataTable with responsive features
        var table = $('#notesTable').DataTable({
            "responsive": true,
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": true, // Enable searching for DataTable API
            "lengthChange": true,
            "language": {
                "emptyTable": "No notes available",
                "zeroRecords": "No matching notes found"
            },
            "columnDefs": [
                { responsivePriority: 1, targets: 0 }, // Title column
                { responsivePriority: 2, targets: 4 }, // Actions column
                { responsivePriority: 3, targets: 1 }, // Description column
                { responsivePriority: 4, targets: 2 }, // Tag column
                { responsivePriority: 5, targets: 3 }  // Created column
            ]
        });
        
        // Use navbar search to filter DataTable
        $('#navbar-search').on('keyup', function() {
            table.search(this.value).draw();
            
            // Also filter mobile cards
            const searchTerm = this.value.toLowerCase();
            $('.note-mobile-card').each(function() {
                const cardText = $(this).text().toLowerCase();
                if(cardText.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // Prevent form submission
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            
            // If you want to implement server-side search
            var searchTerm = $('#navbar-search').val();
            
            $.ajax({
                url: 'search.php',
                method: 'GET',
                data: { query: searchTerm },
                dataType: 'json',
                success: function(response) {
                    // Clear the table and add new data
                    table.clear().rows.add(response.data).draw();
                    
                    // Update mobile cards
                    $('.note-mobile-view').empty();
                    
                    if(response.data.length > 0) {
                        response.data.forEach(function(row) {
                            // Generate mobile card HTML based on response
                            let cardHtml = generateMobileCard(row);
                            $('.note-mobile-view').append(cardHtml);
                        });
                    } else {
                        $('.note-mobile-view').html('<div class="alert alert-info text-center">No notes found</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error searching notes:', error);
                }
            });
        });
        
        // Function to generate mobile card HTML
        function generateMobileCard(row) {
            // The server returns data as an array, we need to check if properties exist at numeric indexes
            // or as object properties
            const title = row[0] || row.title; // Title is at index 0 or as property
            const description = row[1] || row.description; // Description is at index 1 or as property
            const tag = row[2] || ('<span class="badge bg-secondary tag">' + row.tag + '</span>'); // Tag is at index 2 or as property
            const created = row[3] || row.created_at; // Created is at index 3 or as property
            
            // Get id and pinned status from object properties
            const id = row.id;
            const isPinned = row.is_pinned;
            const pinnedClass = isPinned ? 'pinned-card' : '';
            const pinIcon = isPinned ? 'fa-thumbtack' : 'fa-thumbtack text-muted';
            
            return `
                <div class="card note-mobile-card ${pinnedClass}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">${title}</h5>
                        <div>${tag}</div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">${description}</p>
                        <p class="text-muted small mb-2"><i class="fas fa-calendar-alt me-1"></i> ${created}</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <div class="action-icons">
                            <a href="edit.php?id=${id}" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                            <a href="index.php?pin=${id}" class="btn btn-sm btn-outline-warning me-1"><i class="fas ${pinIcon}"></i></a>
                            <a href="index.php?archive=${id}" class="btn btn-sm btn-outline-secondary me-1"><i class="fas fa-archive"></i></a>
                            <a href="index.php?delete=${id}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete?')"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Dark mode toggle functionality
        $('#themeToggle').on('click', function() {
            var htmlElement = document.documentElement;
            var currentTheme = htmlElement.getAttribute('data-bs-theme');
            var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            // Update HTML element
            htmlElement.setAttribute('data-bs-theme', newTheme);
            
            // Update the toggle button appearance
            if (newTheme === 'dark') {
                $('#lightIcon').addClass('d-none');
                $('#darkIcon').removeClass('d-none');
                $('#themeText').text('Light');
            } else {
                $('#darkIcon').addClass('d-none');
                $('#lightIcon').removeClass('d-none');
                $('#themeText').text('Dark');
            }
            
            // Save preference to localStorage
            localStorage.setItem('theme', newTheme);
        });
        
        // Check for saved theme preference
        var savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            // Apply saved theme
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            
            // Update toggle button to match saved theme
            if (savedTheme === 'dark') {
                $('#lightIcon').addClass('d-none');
                $('#darkIcon').removeClass('d-none');
                $('#themeText').text('Light');
            }
        }
    });
</script>

</body>
</html> 