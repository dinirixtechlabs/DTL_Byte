<?php
session_start();

if(!isset($_GET['course'])){
echo "Invalid Request";
exit();
}

$course = $_GET['course'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2>Scan QR to Pay</h2>

<img src="qr.png" width="250">

<p style="margin-top:20px;">After payment click below</p>

<a href="download.php?course=<?php echo $course; ?>">
<button class="btn">I Have Paid</button>
</a>

</div>

</body>
</html>