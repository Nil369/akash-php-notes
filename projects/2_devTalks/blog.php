<?php
// Include database connection
include 'partials/_dbConnect.php';

// Get blog ID from URL parameter
$blogId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Mock data - in a real application, this would come from the database
$blog = [
    'id' => 1,
    'title' => 'Getting Started with React Hooks',
    'content' => '<p>React Hooks were introduced in React 16.8 as a way to use state and other React features without writing a class component. They\'ve revolutionized how we write React components by allowing us to reuse stateful logic between components.</p>
    <h3>The useState Hook</h3>
    <p>The <code>useState</code> hook lets you add state to functional components. Here\'s a simple counter example:</p>',
    'author' => 'Jane Dev',
    'date' => '2023-04-01',
    'category' => 'React',
    'tags' => ['React', 'Hooks', 'JavaScript', 'Frontend'],
    'image' => 'partials/img/blog1.jpg',
    'likes' => 24,
    'comments' => [
        [
            'id' => 1,
            'author' => 'React Enthusiast',
            'content' => 'Great introduction to hooks! I\'ve been using them for a while and they\'ve made my code much cleaner.',
            'date' => '2023-04-01 15:30:00'
        ],
        [
            'id' => 2,
            'author' => 'Newbie Coder',
            'content' => 'Thanks for explaining this so clearly. I was confused about the dependency array in useEffect but now it makes sense.',
            'date' => '2023-04-01 16:15:00'
        ]
    ]
];

// Code examples
$useStateExample = <<<'CODE'
import React, { useState } from 'react';

function Counter() {
  // Declare a state variable called "count" with initial value 0
  const [count, setCount] = useState(0);

  return (
    <div>
      <p>You clicked {count} times</p>
      <button onClick={() => setCount(count + 1)}>
        Click me
      </button>
    </div>
  );
}
CODE;

$useEffectExample = <<<'CODE'
import React, { useState, useEffect } from 'react';

function Example() {
  const [count, setCount] = useState(0);

  // Similar to componentDidMount and componentDidUpdate
  useEffect(() => {
    // Update the document title using the browser API
    document.title = `You clicked ${count} times`;
    
    // Optional cleanup function (similar to componentWillUnmount)
    return () => {
      document.title = 'React App';
    };
  }, [count]); // Only re-run the effect if count changes

  return (
    <div>
      <p>You clicked {count} times</p>
      <button onClick={() => setCount(count + 1)}>
        Click me
      </button>
    </div>
  );
}
CODE;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $blog['title']; ?> - Dev Talks</title>
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
        include "components/cards.php";
    ?>

    <div class="container mt-4">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Blog Post -->
                <article>
                    <!-- Title and Meta Information -->
                    <header class="mb-4">
                        <h1 class="fw-bold"><?php echo $blog['title']; ?></h1>
                        <div class="d-flex flex-wrap align-items-center mb-2">
                            <span class="badge bg-primary me-2 category-badge"><?php echo $blog['category']; ?></span>
                            <span class="text-muted me-3">
                                <i class="bi bi-calendar me-1"></i>
                                <?php echo date('M d, Y', strtotime($blog['date'])); ?>
                            </span>
                            <span class="text-muted me-3">
                                <i class="bi bi-person me-1"></i>
                                <?php echo $blog['author']; ?>
                            </span>
                            <span class="text-muted">
                                <i class="bi bi-chat me-1"></i>
                                <?php echo count($blog['comments']); ?> Comments
                            </span>
                        </div>
                        <div class="mb-3">
                            <?php
                            foreach ($blog['tags'] as $tag) {
                                echo '<a href="tag.php?tag=' . urlencode($tag) . '" class="text-decoration-none me-1 small">#' . $tag . '</a>';
                            }
                            ?>
                        </div>
                    </header>

                    <!-- Featured Image -->
                    <?php if (!empty($blog['image'])): ?>
                    <figure class="mb-4">
                        <img class="img-fluid rounded" src="<?php echo $blog['image']; ?>" alt="<?php echo $blog['title']; ?>">
                    </figure>
                    <?php endif; ?>

                    <!-- Post Content -->
                    <section class="blog-content mb-5">
                        <?php echo $blog['content']; ?>
                        
                        <?php echo CodeBlockCard($useStateExample, 'jsx'); ?>
                        
                        <h3>The useEffect Hook</h3>
                        <p>The <code>useEffect</code> hook lets you perform side effects in function components. It serves the same purpose as <code>componentDidMount</code>, <code>componentDidUpdate</code>, and <code>componentWillUnmount</code> in React classes.</p>
                        
                        <?php echo CodeBlockCard($useEffectExample, 'jsx'); ?>
                        
                        <h3>Rules of Hooks</h3>
                        <p>There are two important rules to follow when using Hooks:</p>
                        <ol>
                            <li>Only call Hooks at the top level. Don't call Hooks inside loops, conditions, or nested functions.</li>
                            <li>Only call Hooks from React function components or custom Hooks. Don't call Hooks from regular JavaScript functions.</li>
                        </ol>
                        
                        <h3>Conclusion</h3>
                        <p>React Hooks provide a more direct API to React concepts you already know: props, state, context, refs, and lifecycle. They offer a powerful way to reuse stateful logic between components without introducing unnecessary nesting in your component tree.</p>
                    </section>

                    <!-- Tags and Share -->
                    <div class="d-flex justify-content-between align-items-center mb-4 p-4 bg-light rounded">
                        <div>
                            <button class="btn btn-sm btn-outline-primary like-btn me-2" data-post-id="<?php echo $blog['id']; ?>">
                                <i class="bi bi-heart"></i> <span class="count"><?php echo $blog['likes']; ?></span>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary bookmark-btn" data-post-id="<?php echo $blog['id']; ?>">
                                <i class="bi bi-bookmark"></i> Save
                            </button>
                        </div>
                        <div>
                            <span class="me-2">Share:</span>
                            <a href="#" class="text-decoration-none me-2 fs-5"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="text-decoration-none me-2 fs-5"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="text-decoration-none fs-5"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>

                    <!-- Author Bio -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="partials/img/avatars/default.png" class="avatar avatar-lg me-3" alt="Author avatar">
                                <div>
                                    <h5 class="mb-1"><?php echo $blog['author']; ?></h5>
                                    <p class="mb-2"><small class="text-muted">Frontend Developer & React Enthusiast</small></p>
                                    <p class="mb-0">Jane is a passionate frontend developer who loves sharing her knowledge about modern JavaScript frameworks and libraries.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <section class="mb-5">
                        <h3 class="mb-4">Comments (<?php echo count($blog['comments']); ?>)</h3>
                        
                        <!-- Comment Form -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="partials/handleComment.php" method="post" id="commentForm">
                                    <input type="hidden" name="blogId" value="<?php echo $blog['id']; ?>">
                                    <div class="mb-3">
                                        <textarea class="form-control" id="commentText" name="commentText" rows="3" placeholder="Join the discussion..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Post Comment</button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Comments List -->
                        <div class="comments-container">
                            <?php
                            foreach ($blog['comments'] as $comment) {
                                echo CommentCard(
                                    $comment['id'],
                                    $comment['author'],
                                    $comment['content'],
                                    $comment['date']
                                );
                            }
                            ?>
                        </div>
                    </section>
                </article>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Search Widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Search</h5>
                    </div>
                    <div class="card-body">
                        <form action="search.php" method="get">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search..." name="q">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Categories Widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="category.php?cat=web-development" class="btn btn-sm btn-outline-primary">Web Development</a>
                            <a href="category.php?cat=mobile" class="btn btn-sm btn-outline-success">Mobile</a>
                            <a href="category.php?cat=database" class="btn btn-sm btn-outline-danger">Database</a>
                            <a href="category.php?cat=devops" class="btn btn-sm btn-outline-info">DevOps</a>
                            <a href="category.php?cat=cloud" class="btn btn-sm btn-outline-warning">Cloud</a>
                            <a href="categories.php" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                    </div>
                </div>
                
                <!-- Related Posts Widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Related Posts</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="partials/img/blog1.jpg" style="width: 80px; height: 60px; object-fit: cover;" class="rounded" alt="Related post image">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><a href="blog.php?id=2" class="text-decoration-none">Advanced React Patterns</a></h6>
                                        <small class="text-muted">Mar 25, 2023</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="partials/img/blog1.jpg" style="width: 80px; height: 60px; object-fit: cover;" class="rounded" alt="Related post image">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><a href="blog.php?id=3" class="text-decoration-none">Understanding React Context API</a></h6>
                                        <small class="text-muted">Mar 15, 2023</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="partials/img/blog1.jpg" style="width: 80px; height: 60px; object-fit: cover;" class="rounded" alt="Related post image">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><a href="blog.php?id=4" class="text-decoration-none">React Performance Optimization</a></h6>
                                        <small class="text-muted">Mar 5, 2023</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Popular Tags Widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Popular Tags</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="tag.php?tag=JavaScript" class="btn btn-sm btn-outline-secondary">JavaScript</a>
                            <a href="tag.php?tag=PHP" class="btn btn-sm btn-outline-secondary">PHP</a>
                            <a href="tag.php?tag=React" class="btn btn-sm btn-outline-secondary">React</a>
                            <a href="tag.php?tag=Vue" class="btn btn-sm btn-outline-secondary">Vue</a>
                            <a href="tag.php?tag=Database" class="btn btn-sm btn-outline-secondary">Database</a>
                            <a href="tag.php?tag=API" class="btn btn-sm btn-outline-secondary">API</a>
                            <a href="tag.php?tag=NodeJS" class="btn btn-sm btn-outline-secondary">Node.js</a>
                            <a href="tag.php?tag=Security" class="btn btn-sm btn-outline-secondary">Security</a>
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
</body>
</html> 