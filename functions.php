<?php  

require "config.php";
function includeBootstrap() {
    echo '
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS (Popper.js and jQuery required for Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    ';
}

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
    $userID = $_SESSION["userID"];
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
    $sql = "INSERT INTO reservetbl (phoneID, phoneCount, totalPrice, reserveDate, pickupDate, userID) VALUES (?, ?, ?, ?, ?, ?)";
    
    // Use prepared statement to prevent SQL injection
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iiissi", $phoneId, $quantity, $totalPrice, $reserveDate, $claimDate, $userID);
    
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
function getReservationsByUserIDAndStatus($userID, $status) {
    $mysqli = connect();
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare SQL statement to fetch reservations by userID and status
    $sql = "SELECT * FROM reservetbl JOIN phonetbl ON reservetbl.phoneId = phonetbl.phoneId WHERE userID = ? AND reservationStatus = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $userID, $status);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if query executed successfully
    if ($result === false) {
        die("Error executing query: " . $mysqli->error);
    }

    $reservations = array();

    // Fetch reservations from the result set
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }

    // Close connection
    $mysqli->close();

    return $reservations;
}
function getReservationsByUserID($userID) {
    $mysqli = connect();
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare SQL statement to fetch reservations by userID
    $sql = "SELECT * FROM reservetbl JOIN phonetbl ON reservetbl.phoneId = phonetbl.phoneId WHERE userID = $userID";
    $result = $mysqli->query($sql);

    // Check if query executed successfully
    if ($result === false) {
        die("Error executing query: " . $mysqli->error);
    }

    $reservations = array();

    // Fetch reservations from the result set
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }

    // Close connection
    $mysqli->close();

    return $reservations;
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

function getUserIpAddr() {
    // Check for shared Internet Protocol (IP)
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    // Check for proxy Internet Protocol (IP)
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    // Use the remote Internet Protocol (IP)
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // Validate and sanitize the IP address
    $ip = filter_var($ip, FILTER_VALIDATE_IP);
    return $ip;
}

function loginUser($email, $password) {


    $mysqli = connect(); // Assuming you have a connect() function that returns a MySQLi object
    $email = trim($email);
    $password = trim($password);
    $dbData = [
        "localhost", // Hostname
        "root",      // Username
        "",          // Password
        "sellphone"  // DBName
    ];
    $activityLog = new ActivityLog(...$dbData);
    

    if ($email == "" || $password == "") {
        if ($email == "") {
            return "<div class='alert alert-danger' role='alert'>Please Enter Your Email</div>";
        }
        
        if ($password == "") {
            return "<div class='alert alert-danger' role='alert'>Please Enter Your Password</div>";
        }
    }
    
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Use FILTER_SANITIZE_EMAIL for email input
    $password = filter_var($password, FILTER_SANITIZE_ENCODED); // Sanitize string input

    $sql = "SELECT email, password, userType, userID FROM userTBL WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);    
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();


    // Check if the user exists and the password is correct
    if ($result->num_rows !== 1 || !password_verify($password, $data["password"])) {
        // If authentication fails, increment login attempt count
        $ip_address = getUserIpAddr();  
        $time = time() - 30; // 30 sec  
    
        $check_attmp = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT count(*) as total_count FROM `attempt_count` WHERE `time_count` > $time AND `ip_address`='$ip_address'"));  
    
        $total_count = $check_attmp['total_count'];  
        $msg = ""; // Define $msg variable
        
        if ($total_count == 3) {  
            $msg = "Your account is blocked. Please try after 30 sec";  
        } else {  
            $total_count++;   
            $time_remain = 3 - $total_count;  
            $time = time();  
    
            if ($time_remain == 0) {  
                $msg = "You are currently blocked. Please try again after 30 seconds, then refresh the page.";  
            } else {  
                $msg = "Please enter valid login details. $time_remain attempts remain";  
            }  
            
            $stmt = $mysqli->prepare("INSERT INTO `attempt_count` (`ip_address`, `time_count`) VALUES (?, ?)");
            $stmt->bind_param("si", $ip_address, $time);
            $stmt->execute();
        }
    
        // Return the message to be displayed in the UI
        return "<div class='alert alert-danger' role='alert'>$msg</div>";
    } elseif ($result->num_rows === 1 && password_verify($password, $data["password"])) {
        // If authentication is successful,
        // set the user session and redirect to the appropriate dashboard.
        $_SESSION["userID"] = $data["userID"];
        $_SESSION["userType"] = $data["userType"];
       
    
        if ($_SESSION["userType"] === "admin" ) {
            // Admin routes
            header("Location: adminDashboard.php");
            $activityLog->setAction($_SESSION['userID'], "successfully logged-in");
            exit();
        } elseif ($_SESSION["userType"] === "user") {
            // User routes
            header("Location: products.php");
            $activityLog->setAction($_SESSION['userID'], "successfully logged-in");
            exit();
      }
    }
    
}

function logoutUser(){

    $dbData = [
        "localhost", // Hostname
        "root",      // Username
        "",          // Password
        "sellphone"  // DBName
    ];

   
    $activityLog = new ActivityLog(...$dbData);
    $activityLog->setAction($_SESSION['userID'], "successfully logged-out");
    // Destroy session
    session_destroy();

    // Redirect to login page
    header("location: login.php");
    exit();
}


function passwordReset($email){
    $mysqli = connect();
    $email = trim($email);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return "Email is not Valid";
    }

    $stmt = $mysqli->prepare("SELECT email FROM userTbl WHERE email= ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if($data == NULL){
        return "Email doesn't exist in the database";
    }

    $str = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $password_length = 7;
    $shuffled_str = str_shuffle($str);
    $new_pass = substr($shuffled_str, 0, $password_length);

    $subject = "Password Recovery";
    $body = "You can log in with your new password: <br>";
    $body .= $new_pass;

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html;charset=UTF-8" . "\r\n"; // Corrected
    $headers .= "From: Admin" . "\r\n"; // Appended correctly

    $send = mail($email, $subject, $body, $headers);
    if($send === FALSE){
        return "Email not sent. Try Again";
    }else{
        $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("UPDATE userTbl SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();
        if($stmt->affected_rows != 1){
            return "Connection Error. Try again";
        }else{
            return "success";
        }
    }
}







