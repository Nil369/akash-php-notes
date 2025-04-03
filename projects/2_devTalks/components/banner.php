<?php

echo '
<div id="carouselExampleIndicators" class="carousel slide mb-3">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="/akash-php-notes/projects/2_devTalks/partials/img/b1.jpg" class="d-block" style="height: 50% !important; width:100%; margin: 0 auto;" alt="img1">
    </div>
    <div class="carousel-item">
      <img src="/akash-php-notes/projects/2_devTalks/partials/img/b2.png" class="d-block" style="height: 50% !important; width:100%; margin: 0 auto;" alt="img2">
    </div>
    <div class="carousel-item">
      <img src="/akash-php-notes/projects/2_devTalks/partials/img/b3.jpg" class="d-block" style="height: 50% !important; width:100%; margin: 0 auto;" alt="img3">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

';

?>