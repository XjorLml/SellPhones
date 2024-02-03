<?php
// Start the session at the beginning of your PHP file


// Check if reservation data is in the session
if (isset($_SESSION['reservation'])) {
    $reservation = $_SESSION['reservation'];
    // Clear the reservation data from the session to avoid duplicates
    unset($_SESSION['reservation']);
} else {
    // No reservation data found, handle accordingly
    echo "No reservation data found.";
    exit(); // Stop execution if there's no reservation data
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>SELLPHONE - Reserve Product</title>

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Roboto:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Work+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- Custom Styles -->
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .reserved-item {
      border-bottom: 1px solid #ddd;
      padding: 15px 0;
      display: flex;
      align-items: center;
    }

    .reserved-item img {
      max-width: 100px;
      margin-right: 20px;
    }

    .item-details {
      flex: 1;
    }

    .total {
      text-align: right;
      font-weight: bold;
    }

    /* Styles for the editing container */
    #editContainer {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #fff;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    /* Close button for the editing container */
    .close-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      cursor: pointer;
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <h1>SELLPHONE<span>.</span></h1>
      </a>

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="contact.html">Contact</a></li>
          <li><a href="products.html">Products</a></li>
          <li><a href="shoppingCart.html">Reserved</a></li>
          <li><a href="login.php">Login</a></li>
        </ul>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs d-flex align-items-center" style="background-image: url('https://cdn.thewirecutter.com/wp-content/media/2023/10/androidphones-2048px-4856-2x1-1.jpg?auto=webp&quality=75&crop=2:1&width=1024');">
      <div class="container position-relative d-flex flex-column align-items-center" data-aos="fade">
        <h2>SHOPPING CART</h2>
        <ol>
          <li><a href="products.html">Products</a></li>
          <li>Reserve </li>
        </ol>
      </div>
    </div>
    <!-- End Breadcrumbs -->

    <section id="shopping-cart" class="shopping-cart">
      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <!-- Display the fetched reservation data -->
        <div id="reservationDetails">
          <h2>Reservation Details</h2>
          <p>Phone ID: <?php echo $reservation['phoneId']; ?></p>
          <p>Phone Count: <?php echo $reservation['phoneCount']; ?></p>
          <p>Claim Date: <?php echo $reservation['claimDate']; ?></p>
        </div>

        <!-- Display reserved items using getReservedItems() -->
        <div id="reservedItems">
          <!-- The JavaScript code to display reserved items as before... -->
        </div>
      </div>
    </section>
    <!-- End Shopping Cart Section -->


      <!-- Editing Container -->
  <div id="editContainer">
    <span class="close-btn" onclick="document.getElementById('editContainer').style.display='none'">&times;</span>
    <h2>Edit Reservation</h2>
    <form>
      
      <label for="editQuantity">Quantity:</label>
      <input type="number" id="editQuantity" required>
      <label for="editDate">Claim Date:</label>
      <input type="date" id="editDate" placeholder="YYYY-MM-DD" required>
      <button type="button" onclick="saveEditedData()">Save</button>
    </form>
  </div>

  </main><!-- End #main -->

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
            Designed by Power PUP Bois</a>
          </div>
        </div>
      </div>
  
    </footer>
    <!-- End Footer -->

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
