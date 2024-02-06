<?php
  require "functions.php";
  $phones = getPhoneData();  

  if (!isset($_SESSION["userID"])) {
    header("location: login.php");
    exit();
    }
  
  if (isset($_GET['logout'])) {
      logoutUser();
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>SELLPHONE</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Roboto:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Work+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">


  <style>
    .phone-image {
      max-height: 300px; /* Set the maximum height for the images */
      object-fit: contain; /* Use 'contain' to maintain aspect ratio without cropping */
      width: 100%; /* Ensure the image takes the full width of its container */
    }
    .footer {
      background-color: #333; /* Change this to your desired background color */
      color: #fff; /* Change this to your desired font color */
    }

    .footer a {
      color: #fff; /* Change this to your desired link color */
    }

    .footer a:hover {
      color: #bbb; /* Change this to your desired link color on hover */
    }

  </style>
  <script>
  function redirectToDetails(phoneData) {
    const form = document.createElement('form');
    form.method = 'post';
    form.action = 'reserveProduct.html';

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'phoneData';
    input.value = phoneData;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
  }
</script>
</head>

<body>

  <header id="header" class="header d-flex align-items-center">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index1.php" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1>SELLPHONE<span>.</span></h1>
      </a>

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="index1.php" >Home</a></li>
          <li><a href="about1.php">About</a></li>
          <li><a href="products.php" class="active">Products</a></li>
          <li><a href="shoppingCart.php">Reserved</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Profile<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="profileUser.php">Profile</a></li>
              <li><a href="?logout">Logout</a></li>
            </ul>
          </li>
        </ul>
      </nav><!-- .navbar -->

    </div>
  </header>

  <main id="main">
    <div class="breadcrumbs d-flex align-items-center" style="background-image: url('https://cdn.thewirecutter.com/wp-content/media/2023/10/androidphones-2048px-4856-2x1-1.jpg?auto=webp&quality=75&crop=2:1&width=1024');">
      <div class="container position-relative d-flex flex-column align-items-center" data-aos="fade">
        <h2>PRODUCTS</h2>
        <ol>
          <li><a href="index1.php">Home</a></li>
          <li>Cellphones</li>
        </ol>
      </div>
    </div>
    <section id="projects" class="projects">
    <div class="container" data-aos="fade-up">
      <div class="portfolio-isotope" data-portfolio-filter="*" data-portfolio-layout="masonry" data-portfolio-sort="original-order">
        <ul class="portfolio-flters" data-aos="fade-up" data-aos-delay="100">
        <li data-filter="*" class="filter-active">All</li>
                  <li data-filter=".filter-apple">Apple</li>
                  <li data-filter=".filter-samsung">Samsung</li>
                  <li data-filter=".filter-xiaomi">Xiaomi</li>
                  <li data-filter=".filter-nokia">Nokia</li>
                  <li data-filter=".filter-lenovo">Lenovo</li>
                  <li data-filter=".filter-realme">Realme</li>
                  <li data-filter=".filter-huawei">Huawei</li>
                  <li data-filter=".filter-vivo">Vivo</li>
        </ul>
        <div class="row gy-4 portfolio-container" data-aos="fade-up" data-aos-delay="200">
          <?php
            foreach ($phones as $phone) {
              echo '<div class="col-lg-4 col-md-6 portfolio-item filter-' . strtolower($phone['phoneBrand']) . '">';
              echo '<div class="portfolio-content h-100">';
              echo '<img src="data:image/jpeg;base64,' . base64_encode($phone['phoneImage']) . '" class="img-fluid phone-image" alt="">';
              echo '<div class="portfolio-info">';
              echo '<h4>' . $phone['phoneModel'] . '</h4>';
              echo '<p>₱' . $phone['phonePrice'] . ' | ' . $phone['phoneStorage'] .  ' | ' . $phone['phoneColor'] . '</p>';
              echo '<a href="data:image/jpeg;base64,' . base64_encode($phone['phoneImage']) . '" title="' . $phone['phoneModel'] . '" data-gallery="portfolio-gallery-' . strtolower($phone['phoneBrand']) . '" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>';
              echo '<a href="reserveProduct.php? id=' . $phone['phoneID'] . '" title="More Details" class="details-link" onclick="redirectToDetails(' . htmlspecialchars(json_encode($phone), ENT_QUOTES, 'UTF-8') . ')"><i class="bi bi-cart"></i></a>';
              echo '</div></div></div>';
            }
          ?>
        </div>
      </div>
    </div>
  </section>
   
  </main>

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">

    <div class="footer-content position-relative">
      <div class="container">
        <div class="row">

          <div class="col-lg-4 col-md-6">
            <div class="footer-info">
              <h3>SellPhone</h3>
              <p>
                Vicente Cruz Street, Sampaloc <br>
                Manila, Philippines<br><br>
                <strong>Phone:</strong> +1 5589 55488 55<br>
                <strong>Email:</strong> sellphone@gmail.com<br>
              </p>
              <div class="social-links d-flex mt-3">
                <a href="#" class="d-flex align-items-center justify-content-center"><i class="bi bi-twitter"></i></a>
                <a href="#" class="d-flex align-items-center justify-content-center"><i class="bi bi-facebook"></i></a>
                <a href="#" class="d-flex align-items-center justify-content-center"><i class="bi bi-instagram"></i></a>
                <a href="#" class="d-flex align-items-center justify-content-center"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div><!-- End footer info column-->

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><a href="index.html">Home</a></li>
              <li><a href="contact.html">Contact</a></li>
              <li><a href="products.html">Products</a></li>
              <li><a href="#">Terms of service</a></li>
              <li><a href="#">Privacy policy</a></li>
            </ul>
          </div><!-- End footer links column-->

          <div class="col-lg-6 col-md-3 footer-links">
            <h4>About Us</h4>
            <p>
              Welcome to SellPhone, your go-to destination for streamlined phone reservation and monitoring. At SellPhone, we're dedicated to simplifying your journey to secure the latest smartphones. Discover, reserve, and stay updated on phone availability effortlessly. Whether you're a tech enthusiast or seeking a reliable device, SellPhone is your one-click solution. Innovation meets simplicity at SellPhone – where your dream phone is just a click away.</p>
          </div><!-- End footer links column-->
        </div>
      </div>
    </div>

    <div class="footer-legal text-center position-relative">
      <div class="container">
        <div class="copyright">
          &copy; Copyright <strong><span>SellPhone</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
          <!-- All the links in the footer should remain intact. -->
          <!-- You can delete the links only if you purchased the pro version. -->
          <!-- Licensing information: https://bootstrapmade.com/license/ -->
          <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/upconstruction-bootstrap-construction-website-template/ -->
          Published by Power PUP Bois</a>
        </div>
      </div>
    </div>

  </footer>
  <!-- End Footer -->
  

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>
  <!-- ... (scroll-top, preloader, and other HTML content remains unchanged) ... -->

  <!-- ... (vendor JS files and main JS file remain unchanged) ... -->

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/js/main.js"></script>
</body>

</html>
