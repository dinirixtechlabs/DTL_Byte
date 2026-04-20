<?php
session_start();
include "db.php";

if(isset($_POST['login'])){

$email = trim($_POST['email']);
$password = trim($_POST['password']);

if(empty($email) || empty($password)){
header("Location: index.php?error=emptyfields");
exit();
}

$stmt = $conn->prepare("SELECT email,password,is_verified FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){

$row = $result->fetch_assoc();

/* 🔐 CHECK EMAIL VERIFIED FIRST */
if($row['is_verified'] == 0){
    header("Location: index.php?error=notverified");
    exit();
}

/* 🔑 THEN CHECK PASSWORD */
if(password_verify($password,$row['password'])){

    $_SESSION['email'] = $row['email'];

    header("Location: index.php?msg=loginsuccess");
    exit();

}else{

    header("Location: index.php?error=wrongpassword");
    exit();

}

}else{

header("Location: index.php?error=nouser");
exit();

}

$stmt->close();
$conn->close();

}else{

header("Location: index.php");
exit();

}
?>