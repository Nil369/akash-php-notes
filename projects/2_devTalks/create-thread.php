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
    'Tools & Productivity',
    'Career & Learning',
    'Help & Support'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Thread - Dev Talks</title>
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
    ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">Create New Discussion Thread</h3>
                    </div>
                    <div class="card-body">
                        <form action="partials/handleThreadCreate.php" method="post" id="threadForm">
                            <!-- Title -->
                            <div class="mb-3">
                                <label for="threadTitle" class="form-label">Title</label>
                                <input type="text" class="form-control" id="threadTitle" name="threadTitle" required placeholder="Enter a clear, specific question or topic">
                                <div class="form-text">A good title is specific and summarizes your question or topic.</div>
                            </div>
                            
                            <!-- Category -->
                            <div class="mb-3">
                                <label for="threadCategory" class="form-label">Category</label>
                                <select class="form-select" id="threadCategory" name="threadCategory" required>
                                    <option value="" selected disabled>Select a category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <!-- Tags -->
                            <div class="mb-3">
                                <label for="threadTags" class="form-label">Tags</label>
                                <input type="text" class="form-control" id="threadTags" name="threadTags" placeholder="Enter tags separated by commas (e.g. javascript, react, web development)">
                                <div class="form-text">Tags help users find your discussion. Add up to 5 relevant tags.</div>
                            </div>
                            
                            <!-- Content -->
                            <div class="mb-3">
                                <label for="threadContent" class="form-label">Description</label>
                                <textarea class="form-control" id="threadContent" name="threadContent" rows="6" required placeholder="Provide details about your question or topic. Be specific and include all relevant information."></textarea>
                            </div>
                            
                            <!-- Code Snippet -->
                            <div class="mb-3">
                                <label for="codeSnippet" class="form-label">Code Snippet (optional)</label>
                                <textarea class="form-control font-monospace" id="codeSnippet" name="codeSnippet" rows="6" placeholder="Paste your code here if applicable..."></textarea>
                            </div>
                            
                            <!-- Code Language -->
                            <div class="mb-3">
                                <label for="codeLanguage" class="form-label">Code Language</label>
                                <select class="form-select" id="codeLanguage" name="codeLanguage">
                                    <option value="" selected disabled>Select a language</option>
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
                            
                            <!-- Notification Options -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="notifyReplies" name="notifyReplies" checked>
                                <label class="form-check-label" for="notifyReplies">Notify me of new replies</label>
                            </div>
                            
                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="threadDraft" name="threadDraft">
                                    <label class="form-check-label" for="threadDraft">
                                        Save as draft
                                    </label>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline-secondary me-2" onclick="window.location.href='forum.php'">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Post Thread</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Tips Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Tips for a Great Thread</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="bi bi-check-circle-fill text-success me-2"></i>Do</h5>
                                <ul>
                                    <li>Be specific about your problem or question</li>
                                    <li>Include relevant code or error messages</li>
                                    <li>Explain what you've already tried</li>
                                    <li>Format your code for readability</li>
                                    <li>Check for similar threads before posting</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="bi bi-x-circle-fill text-danger me-2"></i>Don't</h5>
                                <ul>
                                    <li>Post vague, one-line questions</li>
                                    <li>Ask multiple unrelated questions in one thread</li>
                                    <li>Post homework questions without showing effort</li>
                                    <li>Post sensitive information or credentials</li>
                                    <li>Create duplicate threads</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Preview Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Preview</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="badge bg-primary category-badge me-2" id="previewCategory">Category</span>
                            </div>
                            <small class="text-muted"><?php echo date('M d, Y'); ?></small>
                        </div>
                        <h3 class="card-title" id="previewTitle">Your Thread Title</h3>
                        <div class="d-flex align-items-center mb-3">
                            <img src="partials/img/avatars/default.png" class="avatar me-2" alt="User avatar">
                            <div>
                                <h6 class="mb-0"><?php echo $_SESSION['username']; ?></h6>
                                <small class="text-muted">Thread Starter</small>
                            </div>
                        </div>
                        <div class="thread-content mb-4" id="previewContent">
                            <p>Your thread content will appear here...</p>
                        </div>
                        <div class="mb-3" id="previewTags">
                            <a href="#" class="text-decoration-none me-1 small">#tag1</a>
                            <a href="#" class="text-decoration-none me-1 small">#tag2</a>
                        </div>
                        <div id="previewCode" class="mt-3" style="display: none;">
                            <div class="code-block">
                                <div class="code-header">
                                    <span id="previewCodeLanguage">javascript</span>
                                    <button class="copy-btn">Copy</button>
                                </div>
                                <pre><code class="language-javascript" id="previewCodeContent">// Your code will appear here</code></pre>
                            </div>
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
    
    <script>
        // Live preview functionality
        const titleField = document.getElementById('threadTitle');
        const categoryField = document.getElementById('threadCategory');
        const tagsField = document.getElementById('threadTags');
        const contentField = document.getElementById('threadContent');
        const codeSnippetField = document.getElementById('codeSnippet');
        const codeLanguageField = document.getElementById('codeLanguage');
        
        const previewTitle = document.getElementById('previewTitle');
        const previewCategory = document.getElementById('previewCategory');
        const previewTags = document.getElementById('previewTags');
        const previewContent = document.getElementById('previewContent');
        const previewCode = document.getElementById('previewCode');
        const previewCodeLanguage = document.getElementById('previewCodeLanguage');
        const previewCodeContent = document.getElementById('previewCodeContent');
        
        // Title preview
        titleField.addEventListener('input', function() {
            previewTitle.textContent = this.value || 'Your Thread Title';
        });
        
        // Category preview
        categoryField.addEventListener('change', function() {
            previewCategory.textContent = this.value || 'Category';
        });
        
        // Tags preview
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
        
        // Content preview
        contentField.addEventListener('input', function() {
            previewContent.innerHTML = this.value.replace(/\n/g, '<br>') || 'Your thread content will appear here...';
        });
        
        // Code preview
        function updateCodePreview() {
            const code = codeSnippetField.value.trim();
            const language = codeLanguageField.value;
            
            if (code && language) {
                previewCode.style.display = 'block';
                previewCodeLanguage.textContent = language;
                previewCodeContent.textContent = code;
                previewCodeContent.className = 'language-' + language;
                
                // Re-apply Prism highlighting
                if (typeof Prism !== 'undefined') {
                    Prism.highlightElement(previewCodeContent);
                }
            } else {
                previewCode.style.display = 'none';
            }
        }
        
        codeSnippetField.addEventListener('input', updateCodePreview);
        codeLanguageField.addEventListener('change', updateCodePreview);
        
        // Copy button functionality
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', function() {
                const codeBlock = this.parentElement.nextElementSibling.querySelector('code');
                const codeText = codeBlock.textContent;
                
                navigator.clipboard.writeText(codeText).then(() => {
                    // Change button text temporarily
                    const originalText = this.textContent;
                    this.textContent = 'Copied!';
                    setTimeout(() => {
                        this.textContent = originalText;
                    }, 1500);
                }).catch(err => {
                    console.error('Could not copy code: ', err);
                });
            });
        });
    </script>
</body>
</html> 