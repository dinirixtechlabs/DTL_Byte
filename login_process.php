<?php
session_start();
include "db.php";

if(isset($_POST['login'])){

$email=$_POST['email'];
$password=$_POST['password'];

$stmt=$conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows>0){

$row=$result->fetch_assoc();

if(password_verify($password,$row['password'])){

$_SESSION['email']=$email;

header("Location:index.php");

}else{

header("Location: index.php?error=wrongpassword");
exit();

}

}else{

header("Location: index.php?error=nouser");
exit();

}

}
?>