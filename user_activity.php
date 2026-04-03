<?php
session_start();
include "db.php";

if(!isset($_SESSION['email'])){
header("Location:index.php");
exit();
}

$email=$_SESSION['email'];
?>

<!DOCTYPE html>
<html>

<head>

<title>User Activity</title>
<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="payment-container">

<h2>🧾 Payment History</h2>

<div class="payment-box">

<div class="payment-row">
<span>HTML Course</span>
<span class="payment-badge success">Success</span>
</div>

<div class="payment-row">
<span>JavaScript Course</span>
<span class="payment-badge failed">Failed</span>
</div>

</div>

<a href="profile.php" class="payment-back">⬅ Back</a>

</div>
</body>

</html>