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

if (isset($_GET['logout'])) {
  logoutUser();
}
  $phones = getPhoneData();  

  
  
 
  // Get the userID of the currently logged-in user
  $userID = $_SESSION["userID"];
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
</head>
<style>
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
          <li><a href="products.php">Products</a></li>
          <li><a href="shoppingCart.php" >Reserved</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle active" data-toggle="dropdown">Profile<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="profileUser.php" >Profile</a></li>
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
        <h2>USER PROFILE</h2>
        <ol>
          <li><a href="index1.php">Home</a></li>
          <li>Profile </li>
        </ol>
      </div>
    </div>
    <!-- End Breadcrumbs -->
    <div class="container my-5">
        <h2>PROFILE</h2>
        <table class= "table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>EDIT</th>
                </tr>
            </thead>
            <tbody>
                <?php
               $servername = "localhost";
               $username = "root";
               $password = "";
               $dbname = "sellphone";
               
               $conn = new mysqli($servername, $username, $password, $dbname);
               
               // Check connection
               if ($conn->connect_error) {
                   die("Connection failed: " . $conn->connect_error);
               }
               
               $fName = "";
               $lName = "";
               $email = "";
               $phoneNumber = "";
               
               // Check if userID is set in the session
               if (isset($_SESSION["userID"])) {
                   $userID = $_SESSION["userID"];
               
                   // Prepare and execute the SQL query
                   $sql = "SELECT * FROM usertbl WHERE userID = $userID";
                   $result = $conn->query($sql);
               
                   if ($result->num_rows > 0) {
                       // Fetch the user data
                       $row = $result->fetch_assoc();
                       $fName = $row["fName"];
                       $lName = $row["lName"];
                       $email = $row["email"];
                       $phoneNumber = $row["phoneNumber"];
                   } else {
                       // Redirect to user management page if user not found
                       header("location: userManagement.php");
                       exit;
                   }
               } else {
                   // Redirect to user management page if userID is not set in the session
                   header("location: userManagement.php");
                   exit;
               }
               
               $conn->close(); // Close the connection
               
               // Display the user data
               echo "
               <tr>
                   <td>$fName</td>
                   <td>$lName</td>
                   <td>$email</td>
                   <td>$phoneNumber</td>
                   <td>
                       <a class='btn btn-primary btn-sm' href='profileEditUser.php?userID=$userID'>Edit</a>
                   </td>
               </tr>
               ";
               ?>
                   
     
            </tbody>
        </table>
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
                <li><a href="index1.php">Home</a></li>
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
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        // Handle click event on Cancel buttons
        const cancelButtons = document.querySelectorAll('.cancel-reservation');
        cancelButtons.forEach(button => {
          button.addEventListener('click', function() {
            const reserveId = button.getAttribute('data-reserve-id');
            // Set the reserve ID in the confirmation modal
            document.getElementById('confirmCancelBtn').setAttribute('data-reserve-id', reserveId);
            // Show the confirmation modal
            const confirmCancelModal = new bootstrap.Modal(document.getElementById('confirmCancelModal'));
            confirmCancelModal.show();
          });
        });

        // Handle click event on Confirm button in the confirmation modal
        document.getElementById('confirmCancelBtn').addEventListener('click', function() {
          const reserveId = this.getAttribute('data-reserve-id');
          // Redirect to shoppingCartDelete.php with the reserveID parameter
          window.location.href = 'shoppingCartDelete.php?reserveID=' + reserveId;
        });
      });
    </script>

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
