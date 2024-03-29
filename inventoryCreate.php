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


$phoneBrand = "";
$phoneModel = "";
$phoneStorage = "";
$phoneColor = "";
$phoneStatus = "";
$phoneQuantity = "";
$phonePrice = "";
$phoneImage = "";
$phoneDescription = "";

$errorMessage = "";
$successMessage = "";

if ( $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $phoneBrand = $_POST["phoneBrand"];
    $phoneModel = $_POST["phoneModel"];
    $phoneStorage = $_POST["phoneStorage"];
    $phoneColor = $_POST["phoneColor"];
    $phoneStatus = $_POST["phoneStatus"];
    $phoneQuantity = $_POST["phoneQuantity"];
    $phonePrice = $_POST["phonePrice"];
    $phoneImage = $_POST["phoneImage"];
    $phoneDescription = $_POST["phoneDescription"];

    do {
        if ( empty($phoneBrand) || empty($phoneModel) || empty($phoneStorage) || empty($phoneColor) ) {
            $errorMessage = "first four fields are required";
            break;
        }

        if ($_FILES['phoneImage']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['phoneImage']['tmp_name'];
            $img_name = $_FILES['phoneImage']['name'];
            $img_path = "assets/" . $img_name;
            move_uploaded_file($tmp_name, $img_path);
        }

        $sql = "INSERT INTO phonetbl (phoneBrand, phoneModel, phoneStorage, phoneColor, phoneStatus, phoneQuantity, phonePrice, phoneImage, phoneDescription) " .
            "VALUES ('$phoneBrand','$phoneModel', '$phoneStorage', '$phoneColor' , '$phoneStatus' , '$phoneQuantity' , '$phonePrice' , '$img_path' , '$phoneDescription')";
        $result = $conn->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $conn->error;
            break;
        }

        $phoneBrand = "";
        $phoneModel = "";
        $phoneStorage = "";
        $phoneColor = "";
        $phoneStatus = "";
        $phoneQuantity = "";
        $phonePrice = "";
        $phoneImage = "";
        $phoneDescription = "";

        $activityLog = new ActivityLog(...$dbData);
        $activityLog->setAction($_SESSION['userID'], "created an inventory");
        $successMessage = "Client added correctly";
        header("location: inventory.php");
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
        <h2>Add Phone</h2>

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

        <form method="post" enctype="multipart/form-data">  
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">phoneBrand</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phoneBrand" value="<?php echo $phoneBrand; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">phoneModel</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phoneModel" value="<?php echo $phoneModel; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">phoneStorage</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phoneStorage" value="<?php echo $phoneStorage; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">phoneColor</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phoneColor" value="<?php echo $phoneColor; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">phoneStatus</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phoneStatus" value="<?php echo $phoneStatus; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">phoneQuantity</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phoneQuantity" value="<?php echo $phoneQuantity; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">phonePrice</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phonePrice" value="<?php echo $phonePrice; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">phoneImage</label>
                <div class="col-sm-6">
                    <input type="file" class="form-control" name="phoneImage">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">phoneDescription</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phoneDescription" value="<?php echo $phoneDescription; ?>">
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
                <a class="btn btn-outline-primary" href="inventory.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>