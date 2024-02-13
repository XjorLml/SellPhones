<?php
// shoppingCartDelete.php

// Include the function definition
require_once "userLogss.php";

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sellphone";

// Create a new database connection
$dbData = [$servername, $username, $password, $dbname];
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if reserveID is set in GET parameters
if (isset($_GET['reserveID'])) {
    $reserveID = $_GET['reserveID'];

    // SQL query to fetch phone ID and reserved quantity for the given reserveID
    $getReservationSql = "SELECT phoneId, phoneCount FROM reservetbl WHERE reserveID = ?";
    $stmt = $conn->prepare($getReservationSql);
    $stmt->bind_param("i", $reserveID);
    $stmt->execute();
    $stmt->bind_result($phoneId, $reservedQuantity);

    // Fetch the results
    $stmt->fetch();
    $stmt->close();

    // Delete the reservation from the database
    $deleteSql = "DELETE FROM reservetbl WHERE reserveID = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $reserveID);
    $stmt->execute();
    $stmt->close();

    // Add back the reserved quantity to the available quantity of the corresponding phone
    addBackPhoneQuantityAfterDeletion($phoneId, $reservedQuantity);

    // Redirect the user back to the shopping cart page after successful deletion

    $activityLog = new ActivityLog(...$dbData);
    $activityLog->setAction($_SESSION['userID'], "User Canncelled an Order");
    header("Location: shoppingCart.php");
    exit();
} else {
    // If reserveID is not set in GET parameters, display an error message
    echo "reserveID not set in GET parameters.";
}

// Close the database connection
$conn->close();
?>
