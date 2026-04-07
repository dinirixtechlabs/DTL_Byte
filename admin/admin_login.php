<?php
session_start();
include "../db.php";

if(isset($_POST['login'])){

$username=$_POST['username'];
$password=$_POST['password'];

$query=$conn->query("SELECT * FROM admin WHERE username='$username' AND password='$password'");

if($query->num_rows>0){
$_SESSION['admin']=$username;
header("Location: admin_dashboard.php");
}
else{
echo "Invalid Login";
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin login</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
<div class="admin-container">

<h2>Admin Login</h2>

<form method="POST">

<input type="text" name="username" placeholder="Username">

<input type="password" name="password" placeholder="Password">

<button name="login">Login</button>

</form>

</div>
</body>
</html>