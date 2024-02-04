<?php
require "functions.php";

// Get phone ID from URL parameter
$phoneId = isset($_GET['id']) ? $_GET['id'] : null;

// Check if phone ID is provided
if ($phoneId === null) {
    echo "Phone ID is not provided.";
    exit;
}

// Fetch phone details from the database
$phoneDetails = getPhoneDetailsById($phoneId);

// Check if phone details are found
if ($phoneDetails === null) {
    echo "Phone details not found.";
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if form fields are set
    if (isset($_POST['phoneCount']) && isset($_POST['calendar'])) {
        // Get reservation details
        $phoneCount = $_POST['phoneCount'];
        $claimDate = $_POST['calendar'];

        // Check if the reservation date is not later than the current date and no more than 2 days in the future
        $currentDate = date('Y-m-d');
        $maxReservationDate = date('Y-m-d', strtotime($currentDate . ' + 2 days'));

        if ($claimDate < $currentDate || $claimDate > $maxReservationDate) {
            echo "Invalid reservation date. Please choose a date within the next 2 days.";
            exit;
        }

        // Call the reservePhone function
        $result = reservePhone($phoneId, $phoneCount, $claimDate);

        // Check if the reservation was successful
        if ($result === "Reservation successful") {
            // Store reservation details in session or database for later retrieval
            $_SESSION['reservation'] = [
                'phoneId' => $phoneId,
                'phoneCount' => $phoneCount,
                'claimDate' => $claimDate,
            ];

            // Redirect to shoppingCart.php
            header("Location: shoppingCart.php");
            exit;
        } else {
            // Output the result
            echo $result;
            exit;
        }
    }
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

  <!-- Custom Styles -->
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    
    #selectedPhoneDetails img {
      max-width: 100%;
      height: auto;
      max-height: 400px; /* Add a maximum height to control the size */
      margin: auto; /* Center the image */
      display: block; /* Remove extra space beneath the image */
      
    }

    #selectedPhoneDetails img {
      max-width: 100%;
      height: auto;
      
    }
    #selectedPhoneDetails {
      text-align: center;
      margin-top: 20px; /* Add some margin at the top for better spacing */
    }
    #reservationForm {
    max-width: 400px;
    margin: auto;
    background-color: #f8f9fa;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  #reservationForm h4 {
    text-align: center;
    color: #495057;
  }

  #reservationForm label {
    display: block;
    margin: 10px 0;
    color: #495057;
  }

  #reservationForm input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    box-sizing: border-box;
    border: 1px solid #ced4da;
    border-radius: 4px;
  }

  #reservationForm button {
    background-color: #007bff;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
  }

  #reservationForm button:hover {
    background-color: #0056b3;
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
          <li><a href="products.php">Products</a></li>
          <li><a href="shoppingCart.php">Reserved</a></li>
          <li><a href="login.php">Login</a></li>
        </ul>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs d-flex align-items-center" style="background-image: url('https://cdn.thewirecutter.com/wp-content/media/2023/10/androidphones-2048px-4856-2x1-1.jpg?auto=webp&quality=75&crop=2:1&width=1024');">
      <div class="container position-relative d-flex flex-column align-items-center" data-aos="fade">
        <h2>RESERVE PRODUCT</h2>
        <ol>
          <li><a href="products.html">Products</a></li>
          <li>Specifications</li>
        </ol>
      </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- ======= Projet Details Section ======= -->
    <section id="project-details" class="project-details">
      <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div id="selectedPhoneDetails">
          <?php
            // Display phone details
            echo '<img src="data:image/jpeg;base64,' . base64_encode($phoneDetails['phoneImage']) . '" alt="' . $phoneDetails['phoneModel'] . '" style="max-width: 100%; height: auto;">';
            echo '<h2>' . $phoneDetails['phoneBrand'] . ' ' . $phoneDetails['phoneModel'] . '</h2>';
            echo '<p>Price: ₱' . $phoneDetails['phonePrice'] . '</p>';
            echo '<p>Storage: ' . $phoneDetails['phoneStorage'] . '</p>';
            echo '<p>Color: ' . $phoneDetails['phoneColor'] . '</p>';
          ?>
      </div>
      <!-- Reservation Form -->
      <div class="row justify-content-between gy-4 mt-4">
        <div class="container">
          <form id="reservationForm" method="POST">
            <h4>Reservation Form</h4>
            <label for="phoneCount">Phone Count:</label>
            <input type="number" id="phoneCount" name="phoneCount" required>
            <label for="calendar">Reservation Date:</label>
            <input type="date" id="calendar" name="calendar" required>
            <button type="submit">Submit Reservation</button>
          </form>
        </div>
      </div>
        
    </section><!-- End Projet Details Section -->

  </main><!-- End #main -->

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

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
