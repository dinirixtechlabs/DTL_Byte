<?php

include("db.php");

if(isset($_POST['reset'])){

$email = $_POST['email'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if($new_password != $confirm_password){
header("Location:index.php?error=Password not match");
exit();
}

$hashed = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
$stmt->bind_param("ss",$hashed,$email);

if($stmt->execute()){
header("Location:index.php?msg=Password Updated Successfully");
}else{
header("Location:index.php?error=Something went wrong");
}

}
?>