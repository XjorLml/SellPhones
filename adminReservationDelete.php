<?php
if ( isset( $_GET['reserveID']) ) {
    $reserveID = $_GET['reserveID'];

    $servername= "localhost";
    $username= "root";
    $password= "";
    $dbname= "sellphone";


    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "DELETE FROM reservetbl WHERE reserveID=$reserveID";
    $conn->query($sql);
}

header("location: adminReservation.php");
exit;
?>