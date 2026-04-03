<?php
session_start();
include "db.php";

if(!isset($_SESSION['email'])){
header("Location:index.php");
exit();
}

$email = $_SESSION['email'];

$result = $conn->query("SELECT * FROM payments WHERE email='$email'");
?>

<!DOCTYPE html>
<html>
<head>

<title>Payment History</title>
<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="payment-container">

<h2>🧾 Payment History</h2>

<table>

<tr>
<th>Product</th>
<th>Payment ID</th>
<th>Status</th>
<th>Date</th>
</tr>

<?php

if($result && $result->num_rows > 0){

while($row = $result->fetch_assoc()){

?>

<tr>

<td><?php echo $row['product']; ?></td>

<td><?php echo $row['payment_id']; ?></td>

<td><?php echo $row['status']; ?></td>

<td><?php echo $row['date']; ?></td>

</tr>

<?php
}

}else{

echo "<tr><td colspan='4'>No Payment Found</td></tr>";

}

?>

</table>

<br>

<a href="profile.php" class="back-btn">⬅ Back</a>

</div>

</body>
</html>