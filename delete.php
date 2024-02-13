<?php
require "userLogss.php";
if ( isset( $_GET['userID']) ) {
    $userID = $_GET['userID'];

    $servername= "localhost";
    $username= "root";
    $password= "";
    $dbname= "sellphone";

    $dbData = [$servername, $username, $password, $dbname];
    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "DELETE FROM usertbl WHERE userID=$userID";
    $conn->query($sql);
    $activityLog = new ActivityLog(...$dbData);
    $activityLog->setAction($_SESSION['userID'], "Admin Deleted a user");
}

header("location: userManagement.php");
exit;
?>