<?php
require "userLogss.php";



if(isset($_SESSION["userID"])) {
    if($_SESSION["userType"] === "admin" ) {
        // Redirect admins to admin dashboard
        header("Location: adminDashboard.php");
        exit();
    } 
}

if (!isset($_SESSION["userID"])) {
    header("location: login.php");
    exit();
}

// Get phone ID from URL parameter
$phoneId = isset($_GET['id']) ? $_GET['id'] : null;

// Check if phone ID is provided
if ($phoneId === null) {
    exit("Phone ID is not provided.");
}

// Fetch phone details from the database
$phoneDetails = getPhoneDetailsById($phoneId);
$imagePath = $phoneDetails['phoneImage'];

// Check if phone details are found
if ($phoneDetails === null) {
    exit("Phone details not found.");
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
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <!-- Template Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        .card.mb-3 .card-img-top {
            width: 400px;
            height: auto;
            margin: auto;
            display: block;
        }

        .footer {
            background-color: #333;
            color: #fff;
        }

        .footer a {
            color: #fff;
        }

        .footer a:hover {
            color: #bbb;
        }
    </style>

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

        <!-- ======= Breadcrumbs ======= -->
        <div class="breadcrumbs d-flex align-items-center" style="background-image: url('https://cdn.thewirecutter.com/wp-content/media/2023/10/androidphones-2048px-4856-2x1-1.jpg?auto=webp&quality=75&crop=2:1&width=1024');">
            <div class="container position-relative d-flex flex-column align-items-center" data-aos="fade">
                <h2>RESERVE PRODUCT</h2>
                <ol>
                    <li><a href="products.php">Products</a></li>
                    <li>Specifications</li>
                </ol>
            </div>
        </div>
        <!-- End Breadcrumbs -->

        <!-- ======= Project Details Section ======= -->
        <section id="project-details" class="project-details">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row">
                    <!-- Left side with phone image -->
                    <div class="col-lg-6">
                        <div class="card mb-3">
                        <img src="<?= $imagePath ?>" alt="<?= $phoneDetails['phoneModel'] ?>" class="card-img-top">
                        </div>
                    </div>

                    <!-- Right side with phone details -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title"><?= $phoneDetails['phoneBrand'] . ' ' . $phoneDetails['phoneModel'] ?></h2>
                                <p class="card-text">Price: ₱<?= $phoneDetails['phonePrice'] ?></p>
                                <p class="card-text">Storage: <?= $phoneDetails['phoneStorage'] ?></p>
                                <p class="card-text">Color: <?= $phoneDetails['phoneColor'] ?></p>
                                <p class="card-text">Description: <?= $phoneDetails['phoneDescription'] ?></p>

                                <!-- Check if phone is in stock -->
                                <?php
                                $inStock = ($phoneDetails['phoneQuantity'] > 3);
                                $stockStatus = $inStock ? "In Stock" : "Out of Stock";
                                ?>

                                <!-- In Stock and Quantity input section -->
                                <form id="reservationForm" action="reservePhone.php?id=<?= $phoneId ?>" method="post">
                                    <input type="hidden" name="phone_id" value="<?= $phoneId ?>">
                                    <div class="mb-3">
                                        <div class="quantity-input-section input-group p-2">
                                            <button class="btn btn-outline-secondary" type="button" id="minusBtn">-</button>
                                            <input type="text" class="form-control text-center" value="1" name="quantity" id="quantityInput" readonly style="width: 40px; max-width: 40px;">
                                            <button class="btn btn-outline-secondary" type="button" id="plusBtn">+</button>
                                        </div>
                                        <div class="stock-info p-2">
                                            <p class="card-text"><?= $stockStatus ?>: <?= $phoneDetails['phoneQuantity'] ?></p> 
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-muted mt-2">Note: Maximum of 3 phones allowed per reservation.</p>
                                    </div>
                                    <div class="mb-3">
                                    <div>
                                        <button type="button" class="btn btn-primary w3-button w3-right w3-blue" id="confirmReservationBtn">Reserve Now</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Project Details Section -->

    </main><!-- End #main -->

    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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
                                <strong>Phone:</strong> +63 9672059239<br>
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
                            <li><a href="index1.html">Home</a></li>
                            <li><a href="about1.php">About</a></li>
                            <li><a href="products.php">Products</a></li>
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
    <script>
       document.addEventListener("DOMContentLoaded", function () {
        // Get elements
            var quantityInput = document.getElementById("quantityInput");
            var plusBtn = document.getElementById("plusBtn");
            var minusBtn = document.getElementById("minusBtn");
            var reserveNowBtn = document.getElementById("confirmReservationBtn");
            var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            
            // Get the maximum quantity allowed from the server-side
            var maxQuantityAllowed = <?= $phoneDetails['phoneQuantity'] ?>;

            // Maximum phones user can reserve
            var maxPhonesToReserve = 3;

            // Event listener for the plus button
            plusBtn.addEventListener("click", function () {
                var currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity < maxQuantityAllowed && currentQuantity < maxPhonesToReserve) {
                    quantityInput.value = currentQuantity + 1;
                }
            });

            // Event listener for the minus button
            minusBtn.addEventListener("click", function () {
                var currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity > 1) {
                    quantityInput.value = currentQuantity - 1;
                }
            });

            // Event listener for the reserve button
            reserveNowBtn.addEventListener("click", function () {
                var selectedQuantity = parseInt(quantityInput.value);
                if (selectedQuantity > maxQuantityAllowed) {
                    alert("The selected quantity exceeds the available stock.");
                    return; // Prevent further execution
                }
                if (selectedQuantity > maxPhonesToReserve) {
                    alert("You can reserve a maximum of " + maxPhonesToReserve + " phones.");
                    return; // Prevent further execution
                }
                confirmationModal.show();
            });

            // Event listener for the confirmation modal
            document.getElementById("confirmReservation").addEventListener("click", function () {
                confirmationModal.hide();
                document.getElementById("reservationForm").submit();
            });
        });
    </script>
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="confirmationModalLabel">Confirm Reservation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            You have 3 days to claim your reserved product. Are you sure you want to reserve this product?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="confirmReservation">Confirm</button>
        </div>
        </div>
    </div>
    </div>

</body>

</html>
