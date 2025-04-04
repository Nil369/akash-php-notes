<?php

include_once "button.php";

echo '
<!-- Signup Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="signupModalLabel">Create a DevTalks account</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="partials/handleSignup.php" method="post">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
            <div class="form-text">Choose a unique username.</div>
          </div>
          <div class="mb-3">
            <label for="signupEmail" class="form-label">Email address</label>
            <input type="email" class="form-control" id="signupEmail" name="signupEmail" required>
            <div class="form-text">We\'ll never share your email with anyone else.</div>
          </div>
          <div class="mb-3">
            <label for="signupPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="signupPassword" name="signupPassword" required>
            <div class="form-text">Use at least 8 characters, including numbers and symbols.</div>
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="agreeTerms" name="agreeTerms" required>
            <label class="form-check-label" for="agreeTerms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Create Account</button>
          </div>
        </form>
        
        <hr class="my-4">
        
        <div class="text-center">
          <p>Already have an account?</p>
          <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">
            Login instead
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
';
?>
