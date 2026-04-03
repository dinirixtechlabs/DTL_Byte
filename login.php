<?php
session_start();
include "db.php";

$error="";

if(isset($_POST['login'])){

$email = $_POST['email'];
$password = $_POST['password'];

/* PREPARED STATEMENT (SECURE LOGIN) */

$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){

$row = $result->fetch_assoc();

if(password_verify($password,$row['password'])){

$_SESSION['email']=$row['email'];
header("Location: index.php");
exit();

}else{

$error="Incorrect password";

}

}else{

$error="User not found";

}

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="form-container">

<h2>Login</h2>

<?php
if($error!=""){
echo "<p style='color:red;'>$error</p>";
}
?>

<form method="post">

<input type="email" name="email" placeholder="Enter Email" required>

<input type="password" name="password" placeholder="Enter Password" required>

<p><a href="forgot.php">Forgot Password?</a></p>

<button type="submit" name="login">Login</button>

<p>Don't have an account? <a href="signup.php">Signup</a></p>

</form>

</div>

<script src="script.js"></script>

</body>
</html>