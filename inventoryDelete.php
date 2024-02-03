<?php
if ( isset( $_GET['phoneID']) ) {
    $phoneID = $_GET['phoneID'];

    $servername= "localhost";
    $username= "root";
    $password= "";
    $dbname= "sellphone";


    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "DELETE FROM phonetbl WHERE phoneID=$phoneID";
    $conn->query($sql);
}

header("location: inventory.php");
exit;
?>