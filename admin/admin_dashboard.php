<?php
session_start();
if(!isset($_SESSION['admin'])){
header("Location: admin_login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin dashboard</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
<div class="dashboard">

<h2>Admin Dashboard</h2>

<a href="upload_resource.php">Upload PDF</a>

<a href="admin_logout.php">Logout</a>

</div>
</body>
</html>