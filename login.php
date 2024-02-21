<?php
require "userLogss.php";
if(isset($_POST['submit'])){
    $response = loginUser($_POST['email'], $_POST['password']);

}   


// Check if login attempts reached the limit and set a JavaScript variable accordingly
$mysqli = connect(); // Assuming you have a connect() function that returns a MySQLi object
$ip_address = getUserIpAddr();  
$time = time() - 30; // 30 sec  
$check_attmp = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT count(*) as total_count FROM `attempt_count` WHERE `time_count` > $time AND `ip_address`='$ip_address'"));  
$total_count = $check_attmp['total_count'];  

$maxAttempts = 3; // Set your maximum login attempts here
if ($total_count >= $maxAttempts) {
    $_SESSION['disableButton'] = true; // Set session variable to indicate button should remain disabled
    $_SESSION['disableTime'] = time(); // Store the time when the button was disabled
    $disableButton = true;
} else {
    $_SESSION['disableButton'] = false;
    $disableButton = false;
}
?>



<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
   

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

</head>

<body>

    <!-- Pills navs -->
    <header id="header" class="header d-flex align-items-center">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
    
          <a href="index.html" class="logo d-flex align-items-center">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <!-- <img src="assets/img/logo.png" alt=""> -->
            <h1>SELLPHONE<span>.</span></h1>
          </a>
          <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
          <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
          <nav id="navbar" class="navbar">
            <ul>
              <li><a href="index.html">Home</a></li>
              <li><a href="about.html">About</a></li>
              <li><a href="login.php" class="active">Log In</a></li>
            </ul>
          </nav><!-- .navbar -->
    
        </div>
      </header><!-- End Header -->
      <main id="main">
        <!-- ======= Breadcrumbs ======= -->
        <div class="breadcrumbs d-flex align-items-center" style="background-image: url('https://cdn.thewirecutter.com/wp-content/media/2023/10/androidphones-2048px-4856-2x1-1.jpg?auto=webp&quality=75&crop=2:1&width=1024');">
        </div>
   
    <section id="contact" class="contact">
        <div class="info d-flex align-items-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
    
                        <!-- Tabs content -->
                        <div class="container mt-5">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h2 class="text-center mb-4">Login</h2>

                                            <form action="" method="post" autocomplete="off">
                                                <!-- Email input -->
                                                <div class="mb-3">
                                                    <label for="loginEmail" class="form-label">Email</label>
                                                    <input type="text" name="email" id="loginEmail" class="form-control" value="<?php echo @$_POST['email']; ?>"/>
                                                </div>

                                                <!-- Password input -->
                                                <!-- Password input -->
                                                <div class="mb-3">
                                                    <label for="loginPassword" class="form-label">Password</label>
                                                    <div class="input-group">
                                                        <input type="password" name="password" id="loginPassword" class="form-control" value="<?php echo @$_POST['password']; ?>"/>
                                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('loginPassword', 'password')">Show</button>
                                                    </div>
                                                </div>

                                                <script>
                                                    function togglePasswordVisibility(targetId, otherId) {
                                                        var passwordField = document.getElementById(targetId);
                                                        var otherPasswordField = document.getElementById(otherId);

                                                        if (passwordField.type === "password") {
                                                            passwordField.type = "text";
                                                            otherPasswordField.type = "text";
                                                        } else {
                                                            passwordField.type = "password";
                                                            otherPasswordField.type = "password";
                                                        }
                                                    }
                                                </script>

                                                <!-- 2 column grid layout -->
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <!-- Checkbox -->
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="" id="loginCheck" checked />
                                                            <label class="form-check-label" for="loginCheck" style="font-size: 13px;"> Remember me </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <a href="forgot-password.php" class="d-block" style="font-size: 13px;">Forgot password?</a>
                                                    </div>
                                                </div>
                                                <!-- Submit button -->
                                                <button type="submit" name="submit" class="btn btn-primary btn-block" <?php if($disableButton) echo 'disabled'; ?>>Log In</button>

                                                    <p class="error"><?php echo @$response ?></p>

                                                    </form>

                                                    <div class="text-center mt-3">
                                                        <p>Not a member? <a href="signup.php">Sign Up</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-eAq2B5BzUjg3a0vTA2o49fgJrj2u9vAT9GTO4+m9PqEjLh6j8wtg1W6lgF5wo5JX" crossorigin="anonymous"></script>
    <script>
        // JavaScript to disable login button if login attempts limit is reached
        var disableButton = <?php echo json_encode($disableButton); ?>;
        if (disableButton) {
            document.querySelector('button[name="submit"]').setAttribute('disabled', 'disabled');
        }
    </script>

</body>
</html>