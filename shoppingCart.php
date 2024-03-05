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

$phones_reserved = getReservationsByUserIDAndStatus($_SESSION["userID"], 0);
$phones_claimed = getReservationsByUserIDAndStatus($_SESSION["userID"], 1);



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
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Roboto:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Work+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
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
                    <li><a href="index1.php">Home</a></li>
                    <li><a href="about1.php">About</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="shoppingCart.php" class="active">Reserved</a></li>
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
        <div class="breadcrumbs d-flex align-items-center"
            style="background-image: url('https://cdn.thewirecutter.com/wp-content/media/2023/10/androidphones-2048px-4856-2x1-1.jpg?auto=webp&quality=75&crop=2:1&width=1024');">
            <div class="container position-relative d-flex flex-column align-items-center" data-aos="fade">
                <h2>SHOPPING CART</h2>
                <ol>
                    <li><a href="products.php">Products</a></li>
                    <li>Reserve </li>
                </ol>
            </div>
        </div>
        <!-- End Breadcrumbs -->
        <div class="container my-5">
            <h3>Reserved Items</h3>
            <a class="btn btn-primary" href="products.php" role="button">New Reservation</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>Phone Brand</th>
                        <th>Phone Model</th>
                        <th>Phone Storage</th>
                        <th>Phone Color</th>
                        <th>Reserve Count</th>
                        <th>Pickup Date Until</th>
                        <th>Total Price</th>
                        <th>Phone Image</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($phones_reserved as $reservation) {
                        // Display reserved item details
                        $imagePath = $reservation['phoneImage'];
                        echo "<tr>";
                        echo "<td>" . $reservation['phoneBrand'] . "</td>";
                        echo "<td>" . $reservation['phoneModel'] . "</td>";
                        echo "<td>" . $reservation['phoneStorage'] . "</td>";
                        echo "<td>" . $reservation['phoneColor'] . "</td>";
                        echo "<td>" . $reservation['phoneCount'] . "</td>";
                        echo "<td>" . $reservation['pickupDate'] . "</td>";
                        echo "<td>₱" . $reservation['totalPrice'] . "</td>";
                        echo "<td><img src='$imagePath' alt='Phone Image' style='max-height: 50px; max-width: 50px;'></td>";
                        echo "<td>Reserved</td>";
                        echo "<td><button class='btn btn-danger btn-sm cancel-reservation' data-reserve-id='" . $reservation['reserveID'] . "'>Cancel</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <h3>Claimed Items</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Phone Brand</th>
                        <th>Phone Model</th>
                        <th>Phone Storage</th>
                        <th>Phone Color</th>
                        <th>Reserve Count</th>
                        <th>Pickup Date Until</th>
                        <th>Total Price</th>
                        <th>Phone Image</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($phones_claimed as $reservation) {
                        // Display claimed item details
                        $imagePath = $reservation['phoneImage'];
                        echo "<tr>";
                        echo "<td>" . $reservation['phoneBrand'] . "</td>";
                        echo "<td>" . $reservation['phoneModel'] . "</td>";
                        echo "<td>" . $reservation['phoneStorage'] . "</td>";
                        echo "<td>" . $reservation['phoneColor'] . "</td>";
                        echo "<td>" . $reservation['phoneCount'] . "</td>";
                        echo "<td>" . $reservation['pickupDate'] . "</td>";
                        echo "<td>₱" . $reservation['totalPrice'] . "</td>";
                        echo "<td><img src='$imagePath' alt='Phone Image' style='max-height: 50px; max-width: 50px;'></td>";
                        echo "<td>Claimed</td>";
                        echo "<td><button class='btn btn-danger btn-sm delete-claimed' data-reserve-id='" . $reservation['reserveID'] . "'>Delete</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="confirmCancelModal" tabindex="-1" aria-labelledby="confirmCancelModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmCancelModalLabel">Confirm Cancellation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to cancel this reservation?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmCancelBtn">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this claimed item?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Confirm</button>
                    </div>
                </div>
            </div>
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
                                <a href="#" class="d-flex align-items-center justify-content-center"><i
                                        class="bi bi-twitter"></i></a>
                                <a href="#" class="d-flex align-items-center justify-content-center"><i
                                        class="bi bi-facebook"></i></a>
                                <a href="#" class="d-flex align-items-center justify-content-center"><i
                                        class="bi bi-instagram"></i></a>
                                <a href="#" class="d-flex align-items-center justify-content-center"><i
                                        class="bi bi-linkedin"></i></a>
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
                            Welcome to SellPhone, your go-to destination for streamlined phone reservation and
                            monitoring. At SellPhone, we're dedicated to simplifying your journey to secure the
                            latest smartphones. Discover, reserve, and stay updated on phone availability effortlessly.
                            Whether you're a tech enthusiast or seeking a reliable device, SellPhone is your one-click
                            solution. Innovation meets simplicity at SellPhone – where your dream phone is just a click
                            away.</p>
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
                    Published by Power PUP Bois</a>
                </div>
            </div>
        </div>

    </footer>
    <!-- End Footer -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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

    // Handle click event on Confirm button in the cancellation confirmation modal
    document.getElementById('confirmCancelBtn').addEventListener('click', function() {
        const reserveId = this.getAttribute('data-reserve-id');
        // Redirect to shoppingCartDelete.php with the reserveID parameter
        window.location.href = 'shoppingCartDelete.php?reserveID=' + reserveId;
    });

    // Handle click event on Delete buttons for claimed items
    const deleteButtons = document.querySelectorAll('.delete-claimed');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log("Delete button clicked"); // Check if this message is logged when you click the delete button
            const reserveId = button.getAttribute('data-reserve-id');
            // Show the confirmation modal
            const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            confirmDeleteModal.show();
            // Set the reserve ID in the confirmation modal
            document.getElementById('confirmDeleteBtn').setAttribute('data-reserve-id', reserveId);
        });
    });

    // Set event listener for Confirm button in the confirmation modal
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        console.log("Confirm deletion button clicked"); // Check if this message is logged when you click the confirm deletion button
        const reserveId = this.getAttribute('data-reserve-id');
        // Send an AJAX request to delete the claimed item
        $.ajax({
            url: 'shoppingCartDelete.php',
            method: 'POST', // Use POST method to send parameters
            data: { reserveID: reserveId, action: 'deleteClaimed' }, // Include reserveID and action
            success: function(response) {
                // Reload the page to reflect the changes
                window.location.href = 'shoppingCartDelete.php?reserveID=' + reserveId;
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Handle error, such as displaying an alert to the user
                alert('An error occurred while deleting the claimed item.');
            }
        });
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
