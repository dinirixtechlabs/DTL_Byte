<?php
session_start();
include "db.php";

if(!isset($_SESSION['email'])){
header("Location:index.php");
exit();
}

$email = $_SESSION['email'];
$message = "";

if(isset($_POST['change'])){

$current = $_POST['current'];
$new = $_POST['new'];
$confirm = $_POST['confirm'];

$query = $conn->prepare("SELECT password FROM users WHERE email=?");
$query->bind_param("s",$email);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

if($user['password'] != $current){

$message = "❌ Current password is incorrect";

}elseif($new != $confirm){

$message = "❌ New passwords do not match";

}else{

$stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
$stmt->bind_param("ss",$new,$email);
$stmt->execute();

$message = "✅ Password updated successfully";

}
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Change Password</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="password-container">

<h2 class="password-title">🔐 Change Password</h2>

<?php if($message!=""){ ?>
<div class="password-message"><?php echo $message; ?></div>
<?php } ?>

<form method="POST" class="password-form">

<div class="password-group">
<label>Current Password</label>
<input type="password" name="current" required>
</div>

<div class="password-group">
<label>New Password</label>
<input type="password" name="new" required>
</div>

<div class="password-group">
<label>Confirm Password</label>
<input type="password" name="confirm" required>
</div>

<button type="submit" name="change" class="password-btn">
Change Password
</button>

</form>

<a href="profile.php" class="password-back">⬅ Back</a>

</div>
</body>

</html>