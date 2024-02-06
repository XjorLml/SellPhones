<?php
require_once "functions.php";

// Check if the user is logged in
if (!isset($_SESSION["userID"])) {
    header("location: login.php");
    exit();
    }
  
  if (isset($_GET['logout'])) {
      logoutUser();
  }

// Check if the form is submitted via POST

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get phone ID from the form submission
    $phoneId = isset($_POST['phone_id']) ? $_POST['phone_id'] : null;

    // Check if phone ID is provided
    if ($phoneId === null) {
        echo "Phone ID is not provided.";
        exit;
    }

    // Fetch phone details from the database
    $phoneDetails = getPhoneDetailsById($phoneId);

    // Check if phone details are found
    if ($phoneDetails === null) {
        echo "Phone details not found.";
        exit;
    }

    // Get quantity from the form submission
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Validate quantity
    if ($quantity < 1 || $quantity > 3) {
        echo "Invalid quantity.";
        exit;
    }

    // Get current user ID
    $userId = $_SESSION['user_id'];

    // Perform the reservation and insert data into the database
    $currentTime = date("Y-m-d H:i:s");
    reservePhone($phoneId, $quantity, $currentTime);

    // Update phone quantity
    updatePhoneQuantity($phoneId, $quantity);

    // Redirect to a confirmation page or any other desired page
    header("Location: shoppingCart.php");
    exit;
} else {
    // If the form is not submitted via POST, redirect to the products page
    // Since you're not getting phone ID from the form submission in this case, it's better to redirect to the products page
    header("Location: products.php");
    exit;
}
?>
