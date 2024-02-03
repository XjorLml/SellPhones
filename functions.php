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

function reservePhone($phoneId, $phoneCount, $claimDate) {
    $mysqli = connect();

    $phoneId = filter_var($phoneId, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
    $phoneCount = filter_var($phoneCount, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));

    if (!$phoneId || !$phoneCount) {
        return "Invalid data provided for reservation.";
    }

    // Calculate pickup date (claim date + 3 days)
    $pickupDate = date('Y-m-d', strtotime($claimDate . ' +3 days'));

    // Insert reservation data into reservetbl
    $stmt = $mysqli->prepare("INSERT INTO reservetbl (phoneID, phoneCount, reserveDate, pickupDate) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $phoneId, $phoneCount, $claimDate, $pickupDate);

    if ($stmt->execute()) {
        return "Reservation successful.";
    } else {
        return "Error: " . $stmt->error;
    }
}

function getReservedItems() {
    $mysqli = connect();

    // Check if the user is logged in (email is stored in the session)
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        // Retrieve reserved items for the logged-in user
        $stmt = $mysqli->prepare("SELECT r.*, p.* FROM reservetbl r JOIN phonetbl p ON r.phoneID = p.phoneID WHERE r.email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $reservedItems = array();
            while ($row = $result->fetch_assoc()) {
                $reservedItems[] = $row;
            }
            return $reservedItems;
        } else {
            return "Error: " . $mysqli->error;
        }
    } else {
        return "User not logged in.";
    }
}

function registerUser($email, $fname, $lname, $phoneNumber, $password, $registerRepeatPassword) {

    $mysqli = connect();
    $args = func_get_args();

    $args = array_map(function($value){
        return trim($value);
    }, $args);
    
    foreach ($args as $key => $value){
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
    
    if($data != null){
        return "Email already exists, please use a different Email";
    }

    if(strlen($password) < 8){
        return "Password too short";      
    }

    if($password != $registerRepeatPassword){
        return "Passwords don't match";
    }
    


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("INSERT INTO userTbl (email, fName, lName, phoneNumber, password, userType) VALUES (?, ?, ?, ?, ?, 'user')");
    
    // Updated bind_param to include the data types for each parameter
    $stmt->bind_param("sssss", $email, $fname, $lname, $phoneNumber, $hashed_password);

    if ($stmt->execute()) {
        header("Location: login.php");
        return "User registered successfully";
    } else {
        return "Error: " . $stmt->error;
    }
}

function loginUser($email, $password) {
    $mysqli = connect();

    $email = trim($email);
    $password = trim($password);

    if($email == "" || $password == "") {
        return "Both fields are required";
    }

    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    // Convert email to lowercase for a case-insensitive comparison
    $email = strtolower($email);

    $sql = "SELECT email, password FROM userTbl WHERE LOWER(email) = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if($data == NULL){
        return "Wrong Email or password";
    }

    if(password_verify($password, $data["password"]) == FALSE){
        return "Wrong Email or password";
    } else {
        $_SESSION["email"] = $data["email"]; // Store the lowercase email in the session
        header("Location: products.html");
        exit();
    }
}

function logoutUser(){}

function passwordReset(){}

function deleteAccount(){}

