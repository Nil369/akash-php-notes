<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include 'partials/_dbConnect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Dev Talks</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
        <!-- About Platform -->
        <section class="mb-5">
            <div class="card border-0 shadow">
                <div class="card-body p-4 p-md-5">
                    <h1 class="display-4 mb-4">About Dev Talks</h1>
                    <div class="row align-items-center">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <h3>A community-driven platform for developers</h3>
                            <p class="lead mb-4">
                                Dev Talks is a platform where developers can connect, share knowledge, and grow together.
                            </p>
                            <p>
                                Our mission is to create a vibrant community where programmers of all skill levels can ask questions, share insights, and collaborate on projects. Whether you're a seasoned developer or just starting your coding journey, Dev Talks provides the resources and support you need to succeed.
                            </p>
                            <p>
                                From forum discussions to blog posts, Dev Talks offers multiple ways to engage with fellow developers and stay updated on the latest in technology.
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <img src="partials/img/community.jpg" alt="Developer Community" class="img-fluid rounded shadow-sm" onerror="this.src='https://via.placeholder.com/600x400?text=Dev+Talks+Community'">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Platform Features -->
        <section class="mb-5">
            <h2 class="mb-4">Platform Features</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3">
                                <i class="bi bi-chat-left-text fs-1 p-3"></i>
                            </div>
                            <h3 class="fs-4">Forums</h3>
                            <p>Engage in discussions, ask questions, and share solutions with developers from around the world.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-success bg-gradient text-white rounded-circle mb-3">
                                <i class="bi bi-journal-text fs-1 p-3"></i>
                            </div>
                            <h3 class="fs-4">Blogs</h3>
                            <p>Read and write in-depth articles about programming, technology trends, and best practices.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-info bg-gradient text-white rounded-circle mb-3">
                                <i class="bi bi-people fs-1 p-3"></i>
                            </div>
                            <h3 class="fs-4">Community</h3>
                            <p>Connect with like-minded developers, build your network, and collaborate on projects.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- About the Developer -->
        <section class="mb-5">
            <div class="card border-0 shadow">
                <div class="card-body p-4 p-md-5">
                    <h2 class="mb-4">About the Developer</h2>
                    <div class="row align-items-center">
                        <div class="col-lg-4 mb-4 mb-lg-0 text-center">
                            <img src="https://akash-halder-portfolio.vercel.app/profile.jpg" alt="Akash Halder" class="rounded-circle img-fluid mb-3" style="max-width: 200px; border: 5px solid #f8f9fa;" onerror="this.src='https://via.placeholder.com/200?text=Akash+Halder'">
                            <h3>Akash Halder</h3>
                            <p class="text-muted">Full Stack Developer</p>
                            <div class="d-flex justify-content-center gap-3 fs-4">
                                <a href="https://github.com/Akashh2151" class="text-decoration-none" target="_blank"><i class="bi bi-github"></i></a>
                                <a href="https://www.linkedin.com/in/akash-halder-26820a267/" class="text-decoration-none" target="_blank"><i class="bi bi-linkedin"></i></a>
                                <a href="mailto:akashhalder2151@gmail.com" class="text-decoration-none"><i class="bi bi-envelope"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <p>
                                Hello! I'm Akash Halder, a passionate Full Stack Developer with expertise in building modern web applications. I focus on creating intuitive user experiences with clean, efficient code.
                            </p>
                            <h4 class="mt-4">Skills</h4>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge bg-primary">HTML</span>
                                <span class="badge bg-primary">CSS</span>
                                <span class="badge bg-primary">JavaScript</span>
                                <span class="badge bg-primary">React</span>
                                <span class="badge bg-primary">Node.js</span>
                                <span class="badge bg-primary">Express</span>
                                <span class="badge bg-primary">MongoDB</span>
                                <span class="badge bg-primary">PHP</span>
                                <span class="badge bg-primary">MySQL</span>
                                <span class="badge bg-primary">Bootstrap</span>
                                <span class="badge bg-primary">Tailwind CSS</span>
                                <span class="badge bg-primary">Git</span>
                            </div>
                            <p>
                                I developed Dev Talks as a platform for developers to connect, share knowledge, and help each other grow. The project showcases my skills in PHP, MySQL, and modern front-end development.
                            </p>
                            <p>
                                I'm always interested in new technologies and challenges. Feel free to reach out if you have any questions about Dev Talks or if you'd like to collaborate on a project.
                            </p>
                            <div class="mt-4">
                                <a href="https://akash-halder-portfolio.vercel.app/" target="_blank" class="btn btn-primary">Visit My Portfolio</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- FAQ Section -->
        <section class="mb-5">
            <h2 class="mb-4">Frequently Asked Questions</h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            How do I create an account?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You can create an account by clicking the "Signup" button in the navigation bar. Fill out the registration form with your username, email, and password. Once submitted, you'll receive a confirmation and can start participating in the community.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            How do I create a new forum thread?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            To create a new forum thread, you need to be logged in. Navigate to the Forums page and click the "New Thread" button. Select a category, add a descriptive title, and compose your content. You can format your post with Markdown for code snippets and other formatting.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Can I write blog posts on Dev Talks?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes! Registered users can write and publish blog posts. Head to the Blogs section and click "Write New Post". You can add a title, content, select a category, and even add featured images. Blog posts are a great way to share in-depth knowledge and tutorials with the community.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            How can I customize my profile?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            After logging in, click on your username in the navigation bar and select "Settings". From there, you can update your profile information, change your avatar, add a bio, and set your preferences for notifications and display settings.
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Contact Section -->
        <section class="mb-5">
            <div class="card border-0 shadow">
                <div class="card-body p-4">
                    <h2 class="mb-4">Contact Us</h2>
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <form id="contactForm" onsubmit="handleContactSubmit(event)">
                                <div class="mb-3">
                                    <label for="contactName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="contactName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="contactEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="contactEmail" required>
                                </div>
                                <div class="mb-3">
                                    <label for="contactSubject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="contactSubject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="contactMessage" class="form-label">Message</label>
                                    <textarea class="form-control" id="contactMessage" rows="5" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </form>
                            <div id="contactSuccess" class="alert alert-success mt-3 d-none">
                                Your message has been sent successfully. We'll get back to you soon!
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Get in Touch</h4>
                            <p>We'd love to hear from you! Whether you have questions about the platform, feedback on your experience, or ideas for improvements, please don't hesitate to reach out.</p>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-envelope me-2"></i> <a href="mailto:contact@devtalks.com" class="text-decoration-none">contact@devtalks.com</a>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-discord me-2"></i> <a href="#" class="text-decoration-none">Join our Discord</a>
                                </li>
                                <li>
                                    <i class="bi bi-github me-2"></i> <a href="https://github.com/Akashh2151" class="text-decoration-none" target="_blank">GitHub</a>
                                </li>
                            </ul>
                            <p class="mt-3">
                                <strong>Response Time:</strong> We typically respond to inquiries within 24-48 hours during business days.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include "components/footer.php"; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>
    <!-- Custom JS -->
    <script src="partials/js/script.js"></script>
</body>
</html> 