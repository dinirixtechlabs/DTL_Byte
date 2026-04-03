<?php
include "db.php";

if(isset($_POST['signup'])){

$name=$_POST['name'];
$email=$_POST['email'];
$password=password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql="INSERT INTO users(name,email,password)
VALUES('$name','$email','$password')";

if(mysqli_query($conn,$sql)){
echo "<script>alert('Signup Successful');window.location='login.php';</script>";
}

}
?>
<!DOCTYPE html>
<html>
<head>
<title>Signup</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="form-container">

<h2>Create Account</h2>

<form method="post">

<input type="text" name="name" placeholder="Enter Name" required>

<input type="email" name="email" placeholder="Enter Email" required>

<input type="password" name="password" placeholder="Enter Password" required>

<button name="signup">Signup</button>

<p>Already have an account? <a href="login.php">Login</a></p>

</form>

</div>

</body>
</html>