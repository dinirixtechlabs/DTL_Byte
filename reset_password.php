<?php
session_start();
include("db.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ✅ SESSION SECURITY */
if(!isset($_SESSION['otp_verified']) || !isset($_SESSION['reset_email'])){
    header("Location: index.php");
    exit();
}

if(isset($_POST['reset'])){

    $email = $_SESSION['reset_email'];

    // ✅ Sanitize inputs
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if(empty($new_password) || empty($confirm_password)){
        header("Location: index.php?reset=1&error=emptyfields");
        exit();
    }

    if($new_password !== $confirm_password){
        header("Location: index.php?reset=1&error=password_mismatch");
        exit();
    }

    if(strlen($new_password) < 6){
        header("Location: index.php?reset=1&error=weakpassword");
        exit();
    }

    $hashed = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password=?, otp=NULL, otp_expiry=NULL WHERE email=?");

    if(!$stmt){
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ss", $hashed, $email);

    if($stmt->execute()){

        // ✅ Even if same password, treat as success
        unset($_SESSION['otp_verified']);
        unset($_SESSION['reset_email']);
        unset($_SESSION['otp_sent']);

        header("Location: index.php?msg=passwordreset");
        exit();

    }else{
        echo "SQL Error: " . $stmt->error;
        exit();
    }

}else{
    header("Location: index.php");
    exit();
}
?>