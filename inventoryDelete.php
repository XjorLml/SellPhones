<?php
require "userLogss.php";
if ( isset( $_GET['phoneID']) ) {
    $phoneID = $_GET['phoneID'];

    $servername= "localhost";
    $username= "root";
    $password= "";
    $dbname= "sellphone";

    $dbData = [$servername, $username, $password, $dbname];
    $conn = new mysqli($servername, $username, $password, $dbname);


    $sql = "DELETE FROM phonetbl WHERE phoneID=$phoneID";
    $conn->query($sql);
    $activityLog = new ActivityLog(...$dbData);
    $activityLog->setAction($_SESSION['userID'], "Deleted Inventory ");
}

header("location: inventory.php");
exit;
?>