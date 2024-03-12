<?php
require "userLogss.php";

if (!isset($_SESSION["userID"]) && $_SESSION["userUFID"] !== "2024-PUI149206-1") {
    header("location: login.php");
    exit();
    }
    
  if (isset($_GET['logout'])) {
      logoutUser();
  }
  

if (isset($_GET['phoneID'])) {
    $phoneID = $_GET['phoneID'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sellphone";

    $dbData = [$servername, $username, $password, $dbname];
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the phoneID is being used in another table
    $sql_check = "SELECT * FROM reservetbl WHERE phoneID=$phoneID";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        // PhoneID is being used, show a prompt
        echo "<script>alert('Cannot delete inventory. PhoneID is being used.');</script>";
    } else {
        // PhoneID is not being used, proceed with deletion
        $sql = "DELETE FROM phonetbl WHERE phoneID=$phoneID";
        $conn->query($sql);
        $activityLog = new ActivityLog(...$dbData);
        $activityLog->setAction($_SESSION['userID'], "Deleted Inventory ");
    }
}

header("location: inventory.php");
exit;

?>