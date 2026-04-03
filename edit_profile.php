<?php
session_start();
include "db.php";

/* CHECK LOGIN */

if(!isset($_SESSION['email'])){
header("Location:index.php");
exit();
}

$email = $_SESSION['email'];
$success = "";

/* UPDATE PROFILE */

if(isset($_POST['update'])){

$name = $_POST['name'];
$phone = $_POST['phone'];
$bio = $_POST['bio'];

/* SECURE QUERY */

$stmt = $conn->prepare("UPDATE users SET name=?, phone=?, bio=? WHERE email=?");
$stmt->bind_param("ssss",$name,$phone,$bio,$email);
$stmt->execute();

$success = "Profile updated successfully";
}

/* GET USER DATA */

$stmt = $conn->prepare("SELECT name,email,phone,bio FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Profile</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="edit_profile.css">

</head>

<body>

<div class="edit-container">

<h2 class="edit-title">✏ Edit Profile</h2>

<?php if($success!=""){ ?>
<div class="success-msg">
<?php echo $success; ?>
</div>
<?php } ?>

<form method="POST">

<div class="form-group">
<label>Name</label>
<input type="text" name="name" 
value="<?php echo htmlspecialchars($user['name']); ?>" required>
</div>

<div class="form-group">
<label>Email</label>
<input type="email" 
value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
</div>

<div class="form-group">
<label>Phone</label>
<input type="text" name="phone" 
value="<?php echo htmlspecialchars($user['phone']); ?>">
</div>

<div class="form-group">
<label>Bio</label>
<textarea name="bio" rows="4"><?php echo htmlspecialchars($user['bio']); ?></textarea>
</div>

<div class="edit-actions">

<button type="submit" name="update" class="save-btn">
Save Changes
</button>

<a href="profile.php" class="cancel-btn">
Cancel
</a>

</div>

</form>

</div>

</body>
</html>