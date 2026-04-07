<?php
session_start();
include "db.php";

if(isset($_POST['signup'])){

$name=$_POST['name'];
$email=$_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql="INSERT INTO users(name,email,password)
VALUES('$name','$email','$password')";

if(mysqli_query($conn,$sql)){

header("Location:index.php");

}else{

echo "Error";

}

}
?>