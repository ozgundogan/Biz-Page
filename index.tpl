<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>DevFolio Bootstrap Template</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">
  <!-- =======================================================
  Theme Name: DevFolio
  Theme URL: https://bootstrapmade.com/devfolio-bootstrap-portfolio-html-template/
  Author: BootstrapMade.com
  License: https://bootstrapmade.com/license/
  ======================================================= -->
</head>

<body id="page-top">

  <!--/ Nav Star /-->
  <nav class="navbar navbar-b navbar-trans navbar-expand-md fixed-top" id="mainNav">
    <div class="container">
      <a class="navbar-brand js-scroll" href="#page-top">HURİS</a>
      <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarDefault"
      aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <div class="navbar-collapse collapse justify-content-end" id="navbarDefault">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link normal js-scroll" href="index.php">Anasayfa</a>
        </li>
        <li class="nav-item">
          <a class="nav-link normal js-scroll" href="hakkinda.php">Hakkında</a>
        </li>
        <li class="nav-item">
          <a class="nav-link normal js-scroll" href="hizmetler.php">Hizmetler</a>
        </li>
        <li class="nav-item">
          <a class="nav-link normal js-scroll" href="calismalar.php">Çalışmalar</a>
        </li>
        <li class="nav-item">
          <a class="nav-link blog js-scroll" href="blog.php">Blog</a>
        </li>
        <li class="nav-item">
          <a class="nav-link iletisim js-scroll" href="iletisim.php">İletİşİm</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!--/ Nav End /-->
<!--/ Intro Skew Star /-->
<!-- Carousel Sldier-->

<div id="carousel-slider" class="carousel slide" data-ride="carousel" data-interval="6000">
  <!-- Indicators -->
  <ul class="carousel-indicators">
    <li data-target="#carousel-slider" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-slider" data-slide-to="1"></li>
    <li data-target="#carousel-slider" data-slide-to="2"></li>
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
      <div class="intro bg-image" style="background-image:url(img/work-4.jpg)"></div>
    </div>
  </div>
  <!-- Left and right controls -->
  <a class="carousel-control-prev" href="#carousel-slider" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#carousel-slider" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </a>
  <!-- End carousel Slider -->
</div>

<!--/ Intro Skew End /-->
<?php include("footer.php"); ?>
