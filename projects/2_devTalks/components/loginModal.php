<?php

include_once "button.php";

echo '
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="loginModalLabel">Login to DevTalks</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="partials/handleLogin.php" method="post">
          <div class="mb-3">
            <label for="loginEmail" class="form-label">Email address</label>
            <input type="email" class="form-control" id="loginEmail" name="loginEmail" required>
          </div>
          <div class="mb-3">
            <label for="loginPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
            <label class="form-check-label" for="rememberMe">Remember me</label>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="#" class="text-decoration-none">Forgot password?</a>
          </div>
        </form>
        
        <hr class="my-4">
        
        <div class="text-center">
          <p>Don\'t have an account?</p>
          <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#signupModal" data-bs-dismiss="modal">
            Create an account
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
';
?>
