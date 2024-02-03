<?php
if ( isset( $_GET['userID']) ) {
    $userID = $_GET['userID'];

    $servername= "localhost";
    $username= "root";
    $password= "";
    $dbname= "sellphone";


    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "DELETE FROM usertbl WHERE userID=$userID";
    $conn->query($sql);
}

header("location: userManagement.php");
exit;
?>