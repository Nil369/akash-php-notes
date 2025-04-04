<?php
echo '
<footer class="bg-dark text-light py-4 mt-5 shadow-lg">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-3">
        <h5>DevTalks</h5>
        <p class="small">A community-driven platform for developers to share knowledge, discuss ideas, and grow together.</p>
        
        <!-- Dark/Light Mode Toggle -->
        <div class="d-flex align-items-center mt-3">
          <span class="me-2"><i class="bi bi-sun"></i></span>
          <label class="theme-switch">
            <input type="checkbox" class="theme-toggle" id="footer-theme-toggle">
            <span class="theme-slider"></span>
          </label>
          <span class="ms-2"><i class="bi bi-moon"></i></span>
        </div>
      </div>
      
      <div class="col-md-2 mb-3">
        <h5>Links</h5>
        <ul class="list-unstyled">
          <li><a href="index.php" class="text-decoration-none text-light">Home</a></li>
          <li><a href="blogs.php" class="text-decoration-none text-light">Blogs</a></li>
          <li><a href="forum.php" class="text-decoration-none text-light">Forum</a></li>
          <li><a href="about.php" class="text-decoration-none text-light">About</a></li>
        </ul>
      </div>
      
      <div class="col-md-3 mb-3">
        <h5>Categories</h5>
        <ul class="list-unstyled">
          <li><a href="category.php?cat=web-development" class="text-decoration-none text-light">Web Development</a></li>
          <li><a href="category.php?cat=mobile-apps" class="text-decoration-none text-light">Mobile Apps</a></li>
          <li><a href="category.php?cat=data-science" class="text-decoration-none text-light">Data Science</a></li>
          <li><a href="category.php?cat=devops" class="text-decoration-none text-light">DevOps</a></li>
        </ul>
      </div>
      
      <div class="col-md-3 mb-3">
        <h5>Connect</h5>
        <div class="d-flex gap-3 fs-4">
          <a href="#" class="text-light"><i class="bi bi-github"></i></a>
          <a href="#" class="text-light"><i class="bi bi-twitter"></i></a>
          <a href="#" class="text-light"><i class="bi bi-linkedin"></i></a>
          <a href="#" class="text-light"><i class="bi bi-discord"></i></a>
        </div>
        <p class="small mt-2">Join our Discord community!</p>
      </div>
    </div>
    
    <hr class="my-3 bg-secondary">
    
    <div class="row">
      <div class="col-md-6 small">
        &copy; ' . date("Y") . ' DevTalks. All rights reserved.
      </div>
      <div class="col-md-6 text-md-end small">
        <a href="#" class="text-decoration-none text-light me-3">Privacy Policy</a>
        <a href="#" class="text-decoration-none text-light me-3">Terms of Service</a>
        <a href="#" class="text-decoration-none text-light">Contact</a>
      </div>
    </div>
  </div>
</footer>
';
?>
