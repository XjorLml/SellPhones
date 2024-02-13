<?php 

require "userLogss.php";

if (!isset($_SESSION["userID"]) || $_SESSION["userID"] !== 1) {
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

  <!-- =======================================================
  * Template Name: UpConstruction
  * Updated: Jan 09 2024 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/upconstruction-bootstrap-construction-website-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="adminDashboard.php" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1>SELLPHONE<span>.</span></h1>
      </a>
      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="adminDashboard.php">Home</a></li>
          <li><a href="adminReservation.php" class="active">Reservations</a></li>
          <li><a href="inventory.php">Inventory</a></li>
          <li><a href="userManagement.php">User Management</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Profile<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="profile.php">Profile</a></li>
              <li><a href="?logout">Logout</a></li>
            </ul>
          </li>
        </ul>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->
    <!-- ======= Breadcrumbs ======= -->
   <div class="breadcrumbs d-flex align-items-center" style="background-image: url('https://www.solidbackgrounds.com/images/2560x1440/2560x1440-davys-grey-solid-color-background.jpg');">
    </div><!-- End Breadcrumbs -->

  <div class="container my-5">
    <div class="container my-5">
        <h2>Reservation Management</h2>
        <h3>Reservations:</h3>
        <table class= "table">
            <thead>
                <tr>
                    <th>reserveID</th>
                    <th>phoneID</th>
                    <th>UserID</th>
                    <th>reserveDate</th>
                    <th>pickupDate</th>
                    <th>phoneCount</th>
                    <th>reservationStatus</th>
                    <th>totalPrice</th>
                </tr>
            </thead>
            <tbody>
              <?php
              $servername = "localhost";
              $username = "root";
              $password = "";
              $dbname = "sellphone";
              $dbData = [$servername, $username, $password, $dbname];
              $activityLog = new ActivityLog(...$dbData);
              $activityLog->setAction($_SESSION['userID'], "accessed the Reservation Page");
              $conn = new mysqli($servername, $username, $password, $dbname);

              if ($conn->connect_error) {
                  die("Connection failed" . $conn->connect_error);
              }

              if ($_SERVER["REQUEST_METHOD"] == "POST") {
                  $reserveID = $_POST['reserveID'];
                  $currentStatus = $_POST['currentStatus'];
                  $newStatus = $currentStatus + 1;

                  $sql = "UPDATE reservetbl SET reservationStatus=$newStatus WHERE reserveID=$reserveID";

                  if ($conn->query($sql) === TRUE) {
                      echo "Reservation status updated successfully";
                  } else {
                      echo "Error updating reservation status: " . $conn->error;
                  }
              }

              $sql = "SELECT * FROM reservetbl ";
              $result = $conn->query($sql);

              if (!$result) {
                  die("Invalid query" . $conn->connect_error);
              }

              while ($row = $result->fetch_assoc()) {
                  if ($row["reservationStatus"] === '0') {
                    $pickup = "For pick up";
                      echo "
                          <tr>
                              <td>$row[reserveID]</td>
                              <td>$row[phoneID]</td>
                              <td>$row[userID]</td>
                              <td>$row[reserveDate]</td>
                              <td>$row[pickupDate]</td>
                              <td>$row[phoneCount]</td>
                              <td>$pickup</td>
                              <td>$row[totalPrice]</td>
                              <td>
                                  <form method='post'>
                                      <input type='hidden' name='reserveID' value='$row[reserveID]'>
                                      <input type='hidden' name='currentStatus' value='$row[reservationStatus]'>
                                      <button type='submit' class='btn btn-primary btn-sm'>Claimed</button>
                                  </form>
                                  <a class='btn btn-danger btn-sm' href='adminReservationDelete.php?reserveID=$row[reserveID]'>Delete</a>
                              </td>
                          </tr>
                      ";
                  }
              }

              ?>
            </tbody>
        </table>
        <table class= "table">
          <h3>Claimed:</h3>
          <thead>
                <tr>
                    <th>reserveID</th>
                    <th>phoneID</th>
                    <th>UserID</th>
                    <th>reserveDate</th>
                    <th>pickupDate</th>
                    <th>phoneCount</th>
                    <th>reservationStatus</th>
                    <th>totalPrice</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result->data_seek(0); // Reset the pointer to the beginning of the result set
                
                while ($row = $result->fetch_assoc()) {
                    if ($row["reservationStatus"] === '1') {
                      $claim = "Claimed";
                        echo "
                            <tr>
                                <td>$row[reserveID]</td>
                                <td>$row[phoneID]</td>
                                <td>$row[userID]</td>
                                <td>$row[reserveDate]</td>
                                <td>$row[pickupDate]</td>
                                <td>$row[phoneCount]</td>
                                <td>$claim</td>
                                <td>$row[totalPrice]</td>
                                <td>
                                    <a class='btn btn-danger btn-sm' href='adminReservationDelete.php?reserveID=$row[reserveID]'>Delete</a>
                                </td>
                            </tr>
                        ";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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