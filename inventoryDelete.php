<?php
require "userLogss.php";
if ( isset( $_GET['phoneID']) ) {
    $phoneID = $_GET['phoneID'];

    $servername= "localhost";
    $username= "root";
    $password= "";
    $dbname= "sellphone";


    $conn = new mysqli($servername, $username, $password, $dbname);
    $dbData = [$servername, $username, $password, $dbname];
              $activityLog = new ActivityLog(...$dbData);
              $activityLog->setAction($_SESSION['userID'], "accessed the Inventory Delete Page");

    $sql = "DELETE FROM phonetbl WHERE phoneID=$phoneID";
    $conn->query($sql);
}

header("location: inventory.php");
exit;
?>