<?php
// shoppingCartDelete.php

require_once "userLogss.php";



// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sellphone";

$dbData = [$servername, $username, $password, $dbname];

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to delete a reservation by reserveID
function deleteReservation($reserveID) {
    global $conn;
    $deleteSql = "DELETE FROM reservetbl WHERE reserveID = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $reserveID);
    $stmt->execute();
    $stmt->close();
}

// Function to delete a claimed item by reserveID
function deleteClaimedItem($reserveID) {
    global $conn;

    echo "Deleting claimed item with reserveID: $reserveID<br>";
    
    $deleteSql1 = "DELETE FROM reservetbl WHERE reserveID = ?";
    $stmt = $conn->prepare($deleteSql1);
    $stmt->bind_param("i", $reserveID);
    if ($stmt->execute()) {
        // Check if any rows were affected (item was deleted)
        if ($stmt->affected_rows > 0) {
            echo "Claimed item deleted successfully.";
        } else {
            echo "No claimed item found with the provided reserveID.";
        }
    } else {
        echo "Error deleting claimed item: " . $stmt->error;
    }
    $stmt->close();
}

// Check if reserveID is set in GET parameters
if (isset($_GET['reserveID'])) {
    $reserveID = $_GET['reserveID'];

    // Check the status of the reservation
    $getReservationSql = "SELECT reservationStatus, phoneId, phoneCount FROM reservetbl WHERE reserveID = ?";
    $stmt = $conn->prepare($getReservationSql);
    $stmt->bind_param("i", $reserveID);
    $stmt->execute();
    $stmt->bind_result($reservationStatus, $phoneId, $phoneCount);
    $stmt->fetch();
    $stmt->close();

    // Delete the reservation or claimed item based on its status
    if ($reservationStatus == 0) {
        // Delete reservation
        deleteReservation($reserveID);
        // Add back the reserved quantity to the available quantity of the corresponding phone
        // Assuming this function is defined elsewhere
        // addBackPhoneQuantityAfterDeletion($phoneId, $phoneCount);
        $activityLog = new ActivityLog(...$dbData);
        $activityLog->setAction($_SESSION['userID'], "User Cancelled an Order");
    } elseif ($reservationStatus == 1) {
        // Delete claimed item
        deleteClaimedItem($reserveID);
        $activityLog = new ActivityLog(...$dbData);
        $activityLog->setAction($_SESSION['userID'], "User Deleted Claimed Item");
    } else {
        // Handle error: Invalid reservation status
        echo "Invalid reservation status.";
    }

    // Redirect to shopping cart
    header("Location: shoppingCart.php");
    exit();
} else {
    // If reserveID is not set in GET parameters, display an error message
    echo "reserveID not set in GET parameters.";
}

// Close the database connection
$conn->close();
?>
