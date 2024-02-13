<?php
require "userLogss.php";

if ( isset( $_GET['reserveID']) ) {
    $reserveID = $_GET['reserveID'];

    $servername= "localhost";
    $username= "root";
    $password= "";
    $dbname= "sellphone";

    $dbData = [$servername, $username, $password, $dbname];
    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "DELETE FROM reservetbl WHERE reserveID=$reserveID";
    $conn->query($sql);

    $activityLog = new ActivityLog(...$dbData);
    $activityLog->setAction($_SESSION['userID'], "Deleted reservation");
}

header("location: adminReservation.php");
exit;
?>