<?php include("header.php"); ?>
<!--/ Intro Skew Star /-->
<!-- Carousel Sldier-->

<div id="demo" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ul class="carousel-indicators">
    <li data-target="#demo" data-slide-to="0" class="active"></li>
    <li data-target="#demo" data-slide-to="1"></li>
    <li data-target="#demo" data-slide-to="2"></li>
  </ul>
  <!-- The slideshow -->
  <div class="carousel-inner">

    <div class="carousel-item active">
      <div id="home" class="intro route bg-image" style="background-image: url(img/intro-bg.jpg)">
        <div class="overlay-itro"></div>
        <div class="intro-content display-table">
          <div class="table-cell">
            <div class="container">
              <!--<p class="display-6 color-d">Hello, world!</p>-->
              <h1 class="intro-title mb-4">Düşüncelerini hayata geçiren ekip.</h1>
              <p class="intro-subtitle"><span class="text-slider-items">Process,Build Engine,Solutions,Inovation,Graphical View</span><strong class="text-slider"></strong></p>
              <!-- <p class="pt-3"><a class="btn btn-primary btn js-scroll px-4" href="#about" role="button">Learn More</a></p> -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="carousel-item">
      <div class="intro bg-image" style ="background-image:url(img/work-5.jpg)">
      </div>
    </div>
    <div class="carousel-item">
      <img src="img/work-4.jpg" alt="New York">
    </div>
  </div>
  <!-- Left and right controls -->
  <a class="carousel-control-prev" href="#demo" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#demo" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </a>
  <!-- End carousel Slider -->
</div>

<!--/ Intro Skew End /-->
<?php include("footer.php"); ?>
