<?php
    // shoppingCartDelete.php

    // Include the function definition
    require_once "functions.php";

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

        // Get the reserved phone ID and quantity before deleting the reservation
        $getReservationSql = "SELECT phoneId, phoneCount FROM reservetbl WHERE reserveID = ?";
        $stmt = $conn->prepare($getReservationSql);
        $stmt->bind_param("i", $reserveID);
        $stmt->execute();
        $stmt->bind_result($phoneId, $reservedQuantity);
        $stmt->fetch();
        $stmt->close();

        // Delete the reservation
        $deleteSql = "DELETE FROM reservetbl WHERE reserveID = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("i", $reserveID);
        $stmt->execute();
        $stmt->close();

        // Add back the reserved quantity to phoneQuantity
        addBackPhoneQuantityAfterDeletion($phoneId, $reservedQuantity);

        // Redirect to shoppingCart.php after successful deletion
        header("Location: shoppingCart.php");
        exit();
    } else {
        echo "reserveID not set in GET parameters.";
    }

    $conn->close();
?>
