<?php
    require "functions.php";

    if(isset($_SESSION["userID"])) {
        if($_SESSION["userType"] === "admin" ) {
            // Redirect admins to admin dashboard
            header("Location: adminDashboard.php");
            exit();
        } elseif ($_SESSION["userType"] === "user") {
            // Redirect users to products page
            header("Location: products.php");
            exit();
        }
    }

    if(isset($_POST['submit'])){
        $response = registerUser($_POST['email'], $_POST['fName'], $_POST['lName'], $_POST['phoneNumber'], $_POST['password'], $_POST['registerRepeatPassword']);
        
        }

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

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
    <link href="assets/css/main.css" rel="stylesheet">

    <!-- JavaScript for password visibility toggle -->
<script>
    function togglePasswordVisibility(inputId) {
        var passwordInput = document.getElementById(inputId);
        var visibilityButton = document.querySelector(`#${inputId} + .input-group .btn`);

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            visibilityButton.textContent = "Hide";
        } else {
            passwordInput.type = "password";
            visibilityButton.textContent = "Show";
        }
    }
</script>

</head>
<body>

    <!-- Navigation -->
    <header id="header" class="header d-flex align-items-center">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <h1>SELLPHONE<span>.</span></h1>
            </a>
            <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
            <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
        </div>
    </header>

    <div class="breadcrumbs d-flex align-items-center" style="background-image: url('https://cdn.thewirecutter.com/wp-content/media/2023/10/androidphones-2048px-4856-2x1-1.jpg?auto=webp&quality=75&crop=2:1&width=1024');">
    </div>

    <!-- Registration Section -->
    <section id="contact" class="contact">
    <div class="info d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="container mt-5">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h2 class="text-center mb-5">SignUp</h2>
                                        <!-- Alert for Empty Mobile Number -->
                                        <?php
                                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                            $phoneNumber = $_POST["phoneNumber"];

                                            if (empty($phoneNumber)) {
                                                echo '<div class="alert alert-danger" role="alert">Mobile Number field is empty...!!!!!!</div>';
                                            } elseif (!preg_match("/^09\d{9}$/", $phoneNumber)) {
                                                echo '<div class="alert alert-danger" role="alert">Invalid Mobile Number. Please enter a valid 11-digit number starting with 09</div>';
                                            }
                                        }
                                        ?>
                                        <form action="signup.php" method="post" autocomplete="off" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>

                                            <!-- First Name and Last Name inputs -->
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="fName" class="form-label">First Name</label>
                                                    <input type="text" id="fName" name="fName" class="form-control" value="<?php echo @$_POST['fName']; ?>" />
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="lName" class="form-label">Last Name</label>
                                                    <input type="text" id="lName" name="lName" class="form-control" value="<?php echo @$_POST['lName']; ?>" />
                                                </div>
                                            </div>

                                            <!-- Email and Mobile Number inputs -->
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" id="email" name="email" class="form-control" value="<?php echo @$_POST['email']; ?>" />
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="phoneNumber" class="form-label">Mobile Number</label>
                                                    <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" placeholder="09*-****-****" pattern="\d{11}" title="Please enter a valid 11-digit number starting with 09" value="<?php echo @$_POST['phoneNumber']; ?>" required />
                                                  </div>

                                            </div>

                                            <!-- Password and Re-type Password inputs -->
                                            <!-- Password input with visibility toggle -->
                                           <!-- Password and Re-type Password inputs with visibility toggle -->
                                           <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="password" class="form-label">Password</label>
                                                <div class="input-group">
                                                    <input type="password" id="password" name="password" class="form-control" value="<?php echo @$_POST['password']; ?>"/>
                    
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="registerRepeatPassword" class="form-label">Re-type Password</label>
                                                <div class="input-group">
                                                    <input type="password" id="registerRepeatPassword" name="registerRepeatPassword" class="form-control" value="<?php echo @$_POST['registerRepeatPassword']; ?>" />
                                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('registerRepeatPassword', 'password')">Show</button>
                                                </div>
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

                                            <!-- Submit button -->
                                            <button type="submit" name="submit" class="btn btn-primary btn-block">Sign Up</button>

                                            <?php 
                                            if(@$response == "success"){
                                            ?>
                                            <p class="success"> Registered Successfully</p>
                                            <?php
                                            }else{
                                            ?>
                                            <p class="error"><?php echo@$response; ?></p>
                                            <?php
                                            }
                                            ?>
                                        </form>

                                        <div class="text-center mt-3">
                                            <p>Already a member? <a href="login.php">Log In</a></p>
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

