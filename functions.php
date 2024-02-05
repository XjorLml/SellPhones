<?php  
require "config.php";

function connect() {
    $mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);
    if ($mysqli->connect_errno != 0) {
        $error = $mysqli->connect_error;
        $error_date = date("F j, Y, g:i a");
        $message = "{$error} | {$error_date} \r\n";
        file_put_contents("db-log.txt", $message, FILE_APPEND);
        return false;
    } else {
        return $mysqli;
    }
}

function getPhoneData() {
    $mysqli = connect();

    $sql = "SELECT * FROM phonetbl";
    $result = $mysqli->query($sql);

    if ($result) {
        $phoneData = array();
        while ($row = $result->fetch_assoc()) {
            $phoneData[] = $row;
        }
        return $phoneData;
    } else {
        return "Error: " . $mysqli->error;
    }
}

function getPhoneDetailsById($phoneId) {
    $mysqli = connect(); // Assuming you have a connect() function for database connection

    $sql = "SELECT * FROM phonetbl WHERE phoneID = $phoneId";
    $result = $mysqli->query($sql);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // Or handle the error as needed
    }
}
function reservePhone($phoneId, $quantity, $reserveDate) {
    $mysqli = connect();

    // Fetch phone details from the database
    $phoneDetails = getPhoneDetailsById($phoneId);

    // Check if phone details are found
    if ($phoneDetails === null) {
        echo "Phone details not found.";
        exit;
    }

    // Calculate total price
    $totalPrice = $phoneDetails['phonePrice'] * $quantity;

    // Calculate claim date (reserveDate + 3 days)
    $claimDate = date('Y-m-d H:i:s', strtotime($reserveDate . ' + 3 days'));

    // Now, you can use $totalPrice and $claimDate in your database insertion logic

    // For example, you can use an SQL query to insert data into your 'reservetbl' table
    $sql = "INSERT INTO reservetbl (phoneID, phoneCount, totalPrice, reserveDate, pickupDate) VALUES (?, ?, ?, ?, ?)";
    
    // Use prepared statement to prevent SQL injection
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iiiss", $phoneId, $quantity, $totalPrice, $reserveDate, $claimDate);
    
    // Execute the statement
    $stmt->execute();
    
    // Close the statement
    $stmt->close();
}
function updatePhoneQuantity($phoneId, $reservedQuantity) {
    $mysqli = connect();

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // SQL query to update phone quantity
    $sql = "UPDATE phonetbl SET phoneQuantity = phoneQuantity - $reservedQuantity WHERE phoneId = $phoneId";

    if ($mysqli->query($sql) === TRUE) {
        echo "Phone quantity updated successfully";
    } else {
        echo "Error updating phone quantity: " . $mysqli->error;
    }

    $mysqli->close();
}
function addBackPhoneQuantityAfterDeletion($phoneId, $reservedQuantity) {
    $mysqli = connect();
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Update phone quantity
    $sql = "UPDATE phonetbl SET phoneQuantity = phoneQuantity + $reservedQuantity WHERE phoneId = $phoneId";
    $result = $mysqli->query($sql);

    // Check for errors
    if (!$result) {
        echo "Error adding back phone quantity: " . $mysqli->error;
    }

    // Close connection
    $mysqli->close();
}
function registerUser($email, $fname, $lname, $phoneNumber, $password, $registerRepeatPassword){

    $mysqli = connect();
    $args = func_get_args();

    $args = array_map(function($value){
        return trim($value);
    }, $args);
    
    foreach ($args as $value){
        if(empty($value)){
            return "All Fields are required";
        }
    }

    foreach ($args as $value) {
        if(preg_match("/[<|>]/", $value)){
            return "<> characters are not allowed";
        }
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return "Email is not Valid";
    }

    $stmt = $mysqli->prepare("SELECT email FROM userTbl WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    if($data != NULL){
        return "Email already exists, please use a different Email";
    }

    if(strlen($password) > 50){
        return "Password too long";      
    }

    if($password != $registerRepeatPassword){
        return "Passwords don't match";
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("INSERT INTO userTbl (password, fName, lName, email, phoneNumber, userType) VALUES (?, ?, ?, ?, ?, 'user')");
    $stmt->bind_param("sssss", $hashed_password,$fname, $lname, $email,$phoneNumber);
    $stmt->execute();


    if ($stmt->affected_rows != 1) {
        return "An error occured. Please Try Again";
    } else {
        header("Location: login.php");
        return "Success ";
    }
}


function loginUser($email, $password, $userType) {
    $mysqli = connect();
    $email = trim($email);
    $password = trim($password);
    $userType = trim($userType);

    if($email == "" || $password == "") {
        return "Both fields are required";
    }

    $email = filter_var($email, FILTER_UNSAFE_RAW);
    $password = filter_var($password, FILTER_UNSAFE_RAW);

    $sql = "SELECT email, password, userType FROM userTBL WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);    
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    var_dump($data);  
    var_dump($password);  
    var_dump($userType); 
    
   // In loginUser function
if ($result->num_rows === 1 && password_verify($password, $data["password"])) {
    // If authentication is successful,
    // set the user session and redirect to the appropriate dashboard.
    $_SESSION["userType"] = $data["userType"];

    if ($_SESSION["userType"] === 'admin') {
        // Admin routes
        header("Location: adminDashboard.php");
        exit();
    } elseif ($_SESSION["userType"] === 'user') {
        // User routes
        header("Location: products.php");
        exit();
    }
}
 else {
        
        header('Location: login.php');
        echo "Please log in or Wrong username or password";
    }
}


function logoutUser(){
    session_destroy();
    header("location: login.php");
    exit();
}

function passwordReset(){}

function deleteAccount(){}