<?php

include_once "button.php";

echo '
<nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Dev Talks</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Categories
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link">Contacts</a>
        </li>
      </ul>

      <!-- Right-aligned Search and Buttons -->
      <div class="d-flex flex-column flex-lg-row align-items-center ms-auto">
        <form class="d-flex me-3 w-100 w-lg-auto">
          <input class="form-control mt-2" type="search" placeholder="🔍 | Search..." aria-label="Search" style="height: 38px; min-width: 200px;">
          '.Button("success","Search","outline").'
        </form>

        <!-- Login & Signup buttons -->
        <div class="d-flex flex-lg-row">
          '.Button("primary", "Login", "").'
          '.Button("secondary", "Signup", "").'
        </div>
      </div>
      
    </div>
  </div>
</nav>
';
?>
