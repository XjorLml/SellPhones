<?php
require "userLogss.php";

if (!isset($_SESSION["userID"]) || $_SESSION["userID"] !== 1) {
    header("location: login.php");
    exit();
    }
  
  if (isset($_GET['logout'])) {
      logoutUser();
  }
  
if ( isset( $_GET['userID']) ) {
    $userID = $_GET['userID'];

    $servername= "localhost";
    $username= "root";
    $password= "";
    $dbname= "sellphone";

    $dbData = [$servername, $username, $password, $dbname];
    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "DELETE FROM userTbl WHERE userID=$userID";
    $conn->query($sql);
    $activityLog = new ActivityLog(...$dbData);
    $activityLog->setAction($_SESSION['userID'], "Admin Deleted a user");
}

header("location: userManagement.php");
exit;
?>