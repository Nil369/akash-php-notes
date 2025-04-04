<?php
// Start session
session_start();

// Include auth helper and require login
include 'partials/auth_helper.php';
requireLogin();

// Include database connection
include 'partials/_dbConnect.php';

// Get categories for dropdown
$categories = [
    'Web Development',
    'Mobile Development',
    'DevOps',
    'Data Science',
    'Databases',
    'Cloud Computing',
    'UI/UX',
    'JavaScript',
    'PHP',
    'Python',
    'Java',
    'React',
    'Vue',
    'Angular',
    'Node.js',
    'Security',
    'Testing',
    'Tools & Productivity'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Blog - Dev Talks</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Prism.js CSS for syntax highlighting -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-okaidia.min.css" rel="stylesheet" />
    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
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
    <style>
        .ql-editor {
            min-height: 300px;
        }
        .ql-toolbar.ql-snow, .ql-container.ql-snow {
            border-color: var(--border-color);
        }
        .codeblock-wrapper {
            position: relative;
            margin-bottom: 1rem;
        }
        .language-select {
            position: absolute;
            right: 10px;
            top: 10px;
            z-index: 100;
        }
    </style>
</head>
<body>

    <?php 
        include "components/header.php"; 
        include "components/loginModal.php";
        include "components/signupModal.php";
    ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">Create New Blog Post</h3>
                    </div>
                    <div class="card-body">
                        <form action="partials/handleBlogCreate.php" method="post" enctype="multipart/form-data" id="blogForm">
                            <!-- Title -->
                            <div class="mb-3">
                                <label for="blogTitle" class="form-label">Title</label>
                                <input type="text" class="form-control" id="blogTitle" name="blogTitle" required placeholder="Enter a descriptive title">
                            </div>
                            
                            <!-- Category -->
                            <div class="mb-3">
                                <label for="blogCategory" class="form-label">Category</label>
                                <select class="form-select" id="blogCategory" name="blogCategory" required>
                                    <option value="" selected disabled>Select a category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <!-- Tags -->
                            <div class="mb-3">
                                <label for="blogTags" class="form-label">Tags</label>
                                <input type="text" class="form-control" id="blogTags" name="blogTags" placeholder="Enter tags separated by commas (e.g. javascript, react, web development)">
                                <div class="form-text">Tags help users find your content. Add up to 5 relevant tags.</div>
                            </div>
                            
                            <!-- Featured Image -->
                            <div class="mb-3">
                                <label for="featuredImage" class="form-label">Featured Image</label>
                                <input class="form-control" type="file" id="featuredImage" name="featuredImage" accept="image/*">
                                <div class="form-text">Recommended size: 1200x630 pixels. Max size: 2MB.</div>
                            </div>
                            
                            <!-- Excerpt/Summary -->
                            <div class="mb-3">
                                <label for="blogExcerpt" class="form-label">Excerpt/Summary</label>
                                <textarea class="form-control" id="blogExcerpt" name="blogExcerpt" rows="2" maxlength="250" placeholder="Write a brief summary of your post (max 250 characters)"></textarea>
                                <div class="form-text"><span id="excerptCharCount">0</span>/250 characters</div>
                            </div>
                            
                            <!-- Content Editor -->
                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <div id="editor-container"></div>
                                <input type="hidden" name="blogContent" id="blogContent">
                            </div>
                            
                            <!-- Add Code Block -->
                            <div class="mb-4">
                                <button type="button" class="btn btn-outline-secondary" id="addCodeBtn">
                                    <i class="bi bi-code-square"></i> Add Code Block
                                </button>
                            </div>
                            
                            <!-- Draft/Publish Options -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="saveAsDraft" name="saveAsDraft">
                                    <label class="form-check-label" for="saveAsDraft">
                                        Save as draft
                                    </label>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline-secondary me-2" onclick="window.location.href='index.php'">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Publish Blog</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Preview Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Preview</h4>
                    </div>
                    <div class="card-body">
                        <h2 id="previewTitle">Your Post Title</h2>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <span class="badge bg-primary me-2 category-badge" id="previewCategory">Category</span>
                            <span class="text-muted me-3">
                                <i class="bi bi-calendar me-1"></i>
                                <?php echo date('M d, Y'); ?>
                            </span>
                            <span class="text-muted me-3">
                                <i class="bi bi-person me-1"></i>
                                <?php echo $_SESSION['username']; ?>
                            </span>
                        </div>
                        <div class="mb-3" id="previewTags">
                            <a href="#" class="text-decoration-none me-1 small">#tag1</a>
                            <a href="#" class="text-decoration-none me-1 small">#tag2</a>
                        </div>
                        <div id="previewContent" class="blog-content">
                            <p>Your content will appear here...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Code Block Modal -->
    <div class="modal fade" id="codeBlockModal" tabindex="-1" aria-labelledby="codeBlockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="codeBlockModalLabel">Add Code Block</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="codeLanguage" class="form-label">Language</label>
                        <select class="form-select" id="codeLanguage">
                            <option value="javascript">JavaScript</option>
                            <option value="php">PHP</option>
                            <option value="html">HTML</option>
                            <option value="css">CSS</option>
                            <option value="sql">SQL</option>
                            <option value="python">Python</option>
                            <option value="java">Java</option>
                            <option value="csharp">C#</option>
                            <option value="cpp">C++</option>
                            <option value="ruby">Ruby</option>
                            <option value="bash">Bash</option>
                            <option value="json">JSON</option>
                            <option value="xml">XML</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="codeContent" class="form-label">Code</label>
                        <textarea class="form-control font-monospace" id="codeContent" rows="10" placeholder="Paste your code here..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="insertCodeBtn">Insert Code</button>
                </div>
            </div>
        </div>
    </div>

    <?php include "components/footer.php"; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>
    <!-- Quill Editor JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <!-- Prism.js for syntax highlighting -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
    <!-- Custom JS -->
    <script src="partials/js/script.js"></script>
    
    <script>
        // Initialize Quill editor
        var quill = new Quill('#editor-container', {
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['blockquote', 'code-block'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link', 'image'],
                    ['clean']
                ]
            },
            placeholder: 'Write your blog post content here...',
            theme: 'snow'
        });
        
        // Set up character counter for excerpt
        const excerptField = document.getElementById('blogExcerpt');
        const excerptCharCount = document.getElementById('excerptCharCount');
        
        excerptField.addEventListener('input', function() {
            excerptCharCount.textContent = this.value.length;
        });
        
        // Handle form submission
        document.getElementById('blogForm').addEventListener('submit', function(e) {
            // Get HTML content from Quill editor and store in hidden input
            document.getElementById('blogContent').value = quill.root.innerHTML;
        });
        
        // Live preview functionality
        const titleField = document.getElementById('blogTitle');
        const categoryField = document.getElementById('blogCategory');
        const tagsField = document.getElementById('blogTags');
        const previewTitle = document.getElementById('previewTitle');
        const previewCategory = document.getElementById('previewCategory');
        const previewTags = document.getElementById('previewTags');
        const previewContent = document.getElementById('previewContent');
        
        titleField.addEventListener('input', function() {
            previewTitle.textContent = this.value || 'Your Post Title';
        });
        
        categoryField.addEventListener('change', function() {
            previewCategory.textContent = this.value || 'Category';
        });
        
        tagsField.addEventListener('input', function() {
            const tags = this.value.split(',').map(tag => tag.trim()).filter(tag => tag);
            previewTags.innerHTML = '';
            
            tags.forEach(tag => {
                const tagLink = document.createElement('a');
                tagLink.href = '#';
                tagLink.className = 'text-decoration-none me-1 small';
                tagLink.textContent = '#' + tag;
                previewTags.appendChild(tagLink);
            });
            
            if (tags.length === 0) {
                previewTags.innerHTML = '<a href="#" class="text-decoration-none me-1 small">#tag1</a><a href="#" class="text-decoration-none me-1 small">#tag2</a>';
            }
        });
        
        quill.on('text-change', function() {
            previewContent.innerHTML = quill.root.innerHTML;
            
            // Re-apply Prism highlighting if there are code blocks
            if (typeof Prism !== 'undefined') {
                Prism.highlightAllUnder(previewContent);
            }
        });
        
        // Code Block Modal
        const codeBlockModal = new bootstrap.Modal(document.getElementById('codeBlockModal'));
        
        document.getElementById('addCodeBtn').addEventListener('click', function() {
            codeBlockModal.show();
        });
        
        document.getElementById('insertCodeBtn').addEventListener('click', function() {
            const language = document.getElementById('codeLanguage').value;
            const code = document.getElementById('codeContent').value;
            
            if (code.trim() === '') {
                alert('Please enter some code');
                return;
            }
            
            // Create code block HTML
            const codeBlockHTML = `
                <div class="code-block mb-3">
                    <div class="code-header">
                        <span>${language}</span>
                        <button class="copy-btn">Copy</button>
                    </div>
                    <pre><code class="language-${language}">${escapeHTML(code)}</code></pre>
                </div>
            `;
            
            // Insert code block at current cursor position
            const range = quill.getSelection(true);
            quill.clipboard.dangerouslyPasteHTML(range.index, codeBlockHTML);
            
            // Re-apply Prism highlighting
            if (typeof Prism !== 'undefined') {
                Prism.highlightAll();
            }
            
            // Clear the modal and hide it
            document.getElementById('codeContent').value = '';
            codeBlockModal.hide();
        });
        
        // Helper function to escape HTML
        function escapeHTML(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html> 