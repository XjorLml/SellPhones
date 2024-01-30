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
        return "User registered successfully";
    } else {
        return "Error: " . $stmt->error;
    }
}

function loginUser(){}

function logoutUser(){}

function passwordReset(){}

function deleteAccount(){}

