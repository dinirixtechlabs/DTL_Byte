<?php
session_start();

$conn = new mysqli("localhost","root","Subhrajit@123","coding_platform");

if(isset($_POST['upload'])){

$email = $_SESSION['email'];

$photo = $_FILES['profile_photo']['name'];
$tmp = $_FILES['profile_photo']['tmp_name'];

$folder = "uploads/".$photo;

move_uploaded_file($tmp,$folder);

$conn->query("UPDATE users SET photo='$photo' WHERE email='$email'");

header("Location: profile.php");
}
?>