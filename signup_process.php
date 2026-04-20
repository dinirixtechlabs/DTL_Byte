<?php
session_start();
include "db.php";

if(isset($_POST['signup'])){

    // 🔐 MUST VERIFY EMAIL FIRST
    if(!isset($_SESSION['email_verified']) || $_SESSION['email_verified'] !== true){
        header("Location: index.php?error=notverified");
        exit();
    }

    // 📥 GET INPUTS
    $name = trim($_POST['name']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);

    // ❌ VALIDATION
    if(empty($name) || !$email || empty($password)){
        header("Location: index.php?error=invalidinput");
        exit();
    }

    // 🚨 EMAIL MISMATCH PROTECTION (VERY IMPORTANT)
    if(!isset($_SESSION['signup_email']) || $email !== $_SESSION['signup_email']){
        header("Location: index.php?error=emailmismatch");
        exit();
    }

    // 🔍 CHECK IF USER ALREADY EXISTS
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    if(!$check){
        die("SQL Error: " . $conn->error);
    }

    $check->bind_param("s",$email);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){
        header("Location: index.php?error=userexists");
        exit();
    }

    // 🔐 HASH PASSWORD
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // ✅ INSERT VERIFIED USER
    $stmt = $conn->prepare("INSERT INTO users (name,email,password,is_verified) VALUES (?,?,?,1)");
    if(!$stmt){
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("sss",$name,$email,$hashed);

    if(!$stmt->execute()){
        die("Insert Error: " . $stmt->error);
    }

    // 🧹 CLEAR OTP SESSION DATA (IMPORTANT)
    unset($_SESSION['email_verified']);
    unset($_SESSION['signup_otp']);
    unset($_SESSION['signup_email']);

    // ✅ REDIRECT SUCCESS
    header("Location: index.php?msg=signupsuccess");
    exit();
}
?>