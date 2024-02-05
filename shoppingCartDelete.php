<?php
// shoppingCartDelete.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sellphone";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['reserveID'])) {
    $reserveID = $_GET['reserveID'];

    // Use prepared statements to prevent SQL injection
    $deleteSql = "DELETE FROM reservetbl WHERE reserveID = ?";

    $stmt = $conn->prepare($deleteSql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $reserveID);

    if ($stmt->execute()) {
        echo "Record deleted successfully from reservetbl";

        // Redirect to shoppingCart.php after successful deletion
        header("Location: shoppingCart.php");
        exit(); // Ensure that no further code is executed after the redirect
    } else {
        echo "Error deleting record from reservetbl: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "reserveID not set in GET parameters.";
}

$conn->close();
?>
