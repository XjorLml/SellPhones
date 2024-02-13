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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
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

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
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
          <li><a href="adminReservation.php">Reservations</a></li>
          <li><a href="inventory.php" class="active">Inventory</a></li>
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
</header>

   <!-- ======= Breadcrumbs ======= -->
   <div class="breadcrumbs d-flex align-items-center" style="background-image: url('https://www.solidbackgrounds.com/images/2560x1440/2560x1440-davys-grey-solid-color-background.jpg');">
    </div><!-- End Breadcrumbs -->

  <div class="container my-5">
    <div class="container my-5">
        <h2>Inventory Managment</h2>
        <a class ="btn btn-primary" href="inventoryCreate.php" role="button">New Phone</a>
        <br>
        <table class= "table">
            <thead>
                <tr>
                    <th>phoneID</th>
                    <th>phoneBrand</th>
                    <th>phoneModel</th>
                    <th>phoneStorage</th>
                    <th>phoneColor</th>
                    <th>phoneStatus</th>
                    <th>phoneQuantity</th>
                    <th>phonePrice</th>
                    <th>phoneImage</th>
                    <th>phoneDescription</th>
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
                $activityLog->setAction($_SESSION['userID'], "accessed the Inventory Page");

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed". $conn->connect_error);
                }

                $sql = "SELECT * FROM phonetbl ";
                $result = $conn->query($sql);

                if (!$result) {
                    die("Invalid query". $conn->connect_error);
                }

                while ($row = $result->fetch_assoc()) {
                    $truncatedDescription = strlen($row['phoneDescription']) > 50 ? substr($row['phoneDescription'], 0, 50) . '...' : $row['phoneDescription'];
                    $imagePath = $row['phoneImage'];
                    echo "
                    <tr>
                        <td>$row[phoneID]</td>
                        <td>$row[phoneBrand]</td>
                        <td>$row[phoneModel]</td>
                        <td>$row[phoneStorage]</td>
                        <td>$row[phoneColor]</td>
                        <td>$row[phoneStatus]</td>
                        <td>$row[phoneQuantity]</td>
                        <td>$row[phonePrice]</td>
                        <td><img src = '$imagePath' alt='Phone Image' style='max-height: 50px; max-width: 50px;'></td>
                        <td title='{$row['phoneDescription']}'>$truncatedDescription</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href='inventoryEdit.php?phoneID=$row[phoneID]'>Edit</a>
                            <a class='btn btn-danger btn-sm' href='inventoryDelete.php?phoneID=$row[phoneID]'>Delete</a>
                        </td>
                    </tr>
                    ";
                }    
                ?>  
                
            </tbody>
        </table>
    </div>
</body>
</html>