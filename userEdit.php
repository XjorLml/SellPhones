<?php

require "userLogss.php";

if (!isset($_SESSION["userID"]) || $_SESSION["userID"] !== 1) {
  header("location: login.php");
  exit();
  }

if (isset($_GET['logout'])) {
    logoutUser();
}

$servername= "localhost";
$username= "root";
$password= "";
$dbname= "sellphone";

$dbData = [$servername, $username, $password, $dbname];
$conn = new mysqli($servername, $username, $password, $dbname);

$userID = "";
$fName = "";
$lName = "";
$email = "";
$phoneNumber = "";

$errorMessage = "";
$successMessage = "";

if ( $_SERVER['REQUEST_METHOD'] == 'GET'){
    if ( !isset( $_GET['userID'] ) ) {
        header("location: /phpxampptutorial/userManagement.php");
        exit;
    }

    $userID = $_GET["userID"];

    $sql = "SELECT * FROM usertbl WHERE userID=$userID";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /phpxampptutorial/userManagement.php");
        exit;
    }

    $fName = $row["fName"];
    $lName = $row["lName"];
    $email = $row["email"];
    $phoneNumber = $row["phoneNumber"];
}
else {

    $userID = $_POST["userID"];
    $fName = $_POST["fName"];
    $lName = $_POST["lName"];
    $email = $_POST["email"];
    $phoneNumber = $_POST["phoneNumber"];

    do {
        if ( empty($fName) || empty($lName) || empty($email) || empty($phoneNumber) ) {
            $errorMessage = "All the fields are required";
            break;
        }

        $sql =  "UPDATE usertbl " .
                "SET fName = '$fName', lName = '$lName', email = '$email', phoneNumber = '$phoneNumber' " .
                "WHERE userID = $userID";
        
        $result = $conn->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $conn->error;
            break;
        }

        $successMessage = "Client added correctly";
        $activityLog = new ActivityLog(...$dbData);
        $activityLog->setAction($_SESSION['userID'], "Admin Edited a user's profile");
        header("location: userManagement.php");
        exit;

    } while (false);
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
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
          <li><a href="inventory.php">Inventory</a></li>
          <li><a href="userManagement.php" class="active">User Management</a></li>
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
        <h2>Edit User</h2>

        <?php
        if ( !empty($errorMessage) ) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>

        <form method="post">
            <input type="hidden" name="userID" value="<?php echo $userID; ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="fName" value="<?php echo $fName; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Last Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="lName" value="<?php echo $lName; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="email" value="<?php echo $email; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">phoneNumber</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phoneNumber" value="<?php echo $phoneNumber; ?>">
                </div>
            </div>

            <?php
            if ( !empty($successMessage) ) {
                echo "
                <div class='row mb-3'>
                    <div class='offset-sm-3 col-sm-6'>
                        <div class='alert alert-success alert dismissible fade show' role='alert'>
                        <strong>$successMessage</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                </div>
                ";
            }    
            ?>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                <a class="btn btn-outline-primary" href="userManagement.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>