<?php
    require "functions.php";

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
                                <div class="col-md-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <h2 class="text-center mb-5">SignUp</h2>
                                            <form action="signup.php" method="post" autocomplete="off">

    
                                                <!-- First Column -->
                                                <div class="row mb-3">
                                                    
                                                    <!-- First Name input -->
                                                    <div class="col-md-6">
                                                        <label for="fName" class="form-label">First Name</label>
                                                        <input type="text" id="fName" name="fName" class="form-control" value="<?php echo @$_POST['fName']; ?>" />
                                                    </div>
    
                                                    <!-- Last Name input -->
                                                    <div class="col-md-6">
                                                        <label for="lName" class="form-label">Last Name</label>
                                                        <input type="text" id="lName" name="lName" class="form-control" value="<?php echo @$_POST['lName']; ?>" />
                                                    </div>
                                                </div>
    
                                                <!-- Second Column -->
                                                <div class="row mb-3">
    
                                                    <!-- Email input -->
                                                    <div class="col-md-4">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" id="email" name="email" class="form-control" value="<?php echo @$_POST['email']; ?>" />
                                                    </div>
    
                                                    <!-- Mobile Number input -->
                                                    <div class="col-md-4">
                                                        <label for="phoneNumber" class="form-label">Mobile Number</label>
                                                        <input type="text" id="phoneNumber" name="phoneNumber" class="form-control text-center" placeholder="09*-****-****" value="<?php echo @$_POST['phoneNumber']; ?>" />
                                                    </div>
                                                </div>
    
                                                <!-- Third Column -->
                                                <div class="row mb-3">
                                                    <!-- Password input -->
                                                    <div class="col-md-6">
                                                        <label for="password" class="form-label">Password</label>
                                                        <input type="password" id="password" name="password" class="form-control" value="<?php echo @$_POST['password']; ?>"/>
                                                    </div>
    
                                                    <!-- Re-type Password input -->
                                                    <div class="col-md-6">
                                                        <label for="registerRepeatPassword" class="form-label">Re-type Password</label>
                                                        <input type="password" id="registerRepeatPassword" name="registerRepeatPassword" class="form-control"  />
                                                    </div>
                                                </div>
    
                                                <!-- Checkbox -->
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" value="" id="registerCheck" checked />
                                                    <label class="form-check-label" for="registerCheck">
                                                        I have read and agree to the terms
                                                    </label>
                                                </div>
    
                                                <!-- Submit button -->
                                                <button type="submit" name="submit" class="btn btn-primary btn-block">Sign Up</button>

                                        

                                            </form>
                                            <?php 
                                            if (!empty($response) && $response !== "User registered successfully") {
                                            echo '<div class="error-message">' . $response . '</div>';
                                            }
                                            ?>
    
                                            <div class="text-center mt-3">
                                                <p>Already a member? <a href="signup.php">Log In</a></p>
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