<?php
session_start();
include "db.php";

if(!isset($_SESSION['email'])){
header("Location:index.php");
exit();
}

$email = $_SESSION['email'];

/* Fetch User Data */

$stmt = $conn->prepare("SELECT name,email,photo FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

/* Profile Photo */

$photo = !empty($user['photo']) ? "uploads/".$user['photo'] : "default-avatar.png";
?>

<!DOCTYPE html>
<html>

<head>

<title>My Profile</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="profile-container">

<h2 class="profile-title">👤 My Profile</h2>

<!-- PROFILE IMAGE -->

<div class="profile-header">

<img src="<?php echo $photo; ?>" class="profile-photo">

<form action="upload_profile.php" method="POST" enctype="multipart/form-data">

<div class="upload-box">

<input type="file" name="profile_photo" required>

<button type="submit" name="upload" class="btn upload-btn">
📷 Upload Photo
</button>

</div>

</form>

</div>

<!-- PROFILE INFO -->

<div class="profile-info">

<div class="profile-row">
<span>Name</span>
<strong><?php echo $user['name']; ?></strong>
</div>

<div class="profile-row">
<span>Email</span>
<strong><?php echo $user['email']; ?></strong>
</div>

</div>

<!-- PROFILE FEATURES -->

<div class="profile-features">

<a href="edit_profile.php" class="btn feature-btn">
✏ Edit Profile
</a>

<a href="change_password.php" class="btn feature-btn">
🔐 Change Password
</a>

<a href="user_activity.php" class="btn feature-btn">
📊 User Activity
</a>

<a href="payment_history.php" class="btn feature-btn">
🧾 Payment History
</a>

</div>

<!-- ACTION BUTTONS -->

<div class="profile-actions">

<a href="index.php" class="btn back-btn">
⬅ Back
</a>

<a href="logout.php"
class="btn logout-btn"
onclick="return confirm('Are you sure you want to logout?')">
🚪 Logout
</a>

</div>

</div>

</body>

</html>