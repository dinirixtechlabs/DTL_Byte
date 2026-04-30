<?php
session_start();
include("db.php");

/* ---------- OTP VERIFY (REPLACE OLD CODE WITH THIS) ---------- */
if(isset($_POST['verify_otp'])){

    if(!isset($_SESSION['reset_email'])){
        header("Location: index.php");
        exit();
    }

    $email = $_SESSION['reset_email'];
    $otp = filter_input(INPUT_POST, 'otp', FILTER_SANITIZE_NUMBER_INT);

    if(empty($otp)){
    $_SESSION['otp_error'] = "Please enter OTP";
    header("Location: index.php?otp=sent");
    exit();
    }

    $stmt = $conn->prepare("SELECT otp, otp_expiry FROM users WHERE email=?");

    if(!$stmt){
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if($data){

        $current = date("Y-m-d H:i:s");

        if($otp == $data['otp'] && strtotime($current) <= strtotime($data['otp_expiry'])){

            $_SESSION['otp_verified'] = true;
            $_SESSION['show_reset'] = true;
            unset($_SESSION['otp_sent']);

            // ✅ CLEAR OTP AFTER SUCCESS
            $clear = $conn->prepare("UPDATE users SET otp=NULL, otp_expiry=NULL WHERE email=?");
            $clear->bind_param("s", $email);
            $clear->execute();
            
            header("Location: index.php?reset=1");
            exit();

        }else{
            $_SESSION['otp_error'] = "Invalid or Expired OTP";
            header("Location: index.php?otp=sent");
            exit();
        }

    }else{
        $otp_error = "Something went wrong. Try again.";
    }
}

/* ---------- LOGIN POPUP CONTROL ---------- */

if(!isset($_SESSION['email']) && !isset($_GET['reset']) && !isset($_GET['otp'])){
echo "<script>
window.addEventListener('load', function(){
document.getElementById('authModal').style.display='flex';
});
</script>";
}

/* ---------- GET USER PROFILE PHOTO ---------- */

$profilePhoto = "images/profile_icon.png";

if(isset($_SESSION['email'])){

    $email = $_SESSION['email'];

    $stmt = $conn->prepare("SELECT photo FROM users WHERE email=?");

    if(!$stmt){
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("s",$email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if(!empty($user['photo'])){
        $profilePhoto = "uploads/".$user['photo'];
    }

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Learn Coding Easily</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<!-- ================= NAVBAR ================= -->

<header class="navbar">

<div class="menu-btn" onclick="toggleSidebar()">☰</div>

<div class="nav-left">
<img src="logo.jpg" class="logo">
<span class="brand">DTL Byte</span>
</div>

<div class="nav-center">
<p class="tagline">Learn Coding Easily With Study Resources</p>
</div>

<div class="nav-right">

<?php if(isset($_SESSION['email'])){ ?>

<a href="profile.php" class="profile-link">
<img src="<?php echo $profilePhoto; ?>" class="nav-profile">
</a>

<a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">
<button class="logout-btn">⏻ Logout</button>
</a>

<?php } ?>

</div>

</header>


<!-- ================= DASHBOARD ================= -->

<div class="dashboard">

<!-- SIDEBAR -->

<div class="sidebar">

<a href="index.php">
<span>🏠</span> Dashboard
</a>

<a href="#notes">
<span>📚</span> Notes
</a>

<a href="#roadmaps">
<span>🗺️</span> Roadmaps
</a>

<a href="#source">
<span>💻</span> Source Codes
</a>

<a href="payment_history.php">
<span>💳</span> Payments
</a>

<a href="profile.php">
<span>👤</span> Profile
</a>

<a href="admin/admin_login.php" class="admin-btn">
<span>🛠️</span> Admin
</a>

</div>


<!-- ================= MAIN CONTENT ================= -->

<div class="main-content">

<div class="container">

<?php include 'db.php'; ?>

<!-- ================= ROADMAP ================= -->

<h2 id="roadmaps" class="section-title">Roadmaps</h2>

<div class="cards">
<?php
$query = "SELECT * FROM resources WHERE category='roadmap' ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        $filePath = $row['category'] . "/" . $row['file_name'];
?>
        <div class="card">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo htmlspecialchars($row['course']); ?></p>
            <a href="<?php echo $filePath; ?>" class="btn" target="_blank">View Roadmap</a>
        </div>
<?php
    }
} else {
?>
        <div class="card">
            <h3>No Roadmaps Yet</h3>
            <p>Upload will appear here</p>
        </div>
<?php
}
?>
</div>


<!-- ================= NOTES ================= -->

<h2 id="notes" class="section-title">Notes</h2>

<div class="cards">
<?php
$query = "SELECT * FROM resources WHERE category='notes' ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
?>
        <div class="card">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo htmlspecialchars($row['course']); ?></p>
            <a href="payment.php?item=<?php echo urlencode($row['file_name']); ?>" class="btn lock-btn">
                Unlock Notes
            </a>
        </div>
<?php
    }
} else {
?>
        <div class="card">
            <h3>No Notes Yet</h3>
            <p>Upload will appear here</p>
        </div>
<?php
}
?>
</div>


<!-- ================= SOURCE CODE ================= -->

<h2 id="source" class="section-title">Source Codes</h2>

<div class="cards">
<?php
$query = "SELECT * FROM resources WHERE category='sourcecode' ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
?>
        <div class="card">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo htmlspecialchars($row['course']); ?></p>
            <a href="payment.php?item=<?php echo urlencode($row['file_name']); ?>" class="btn lock-btn">
                Unlock Source
            </a>
        </div>
<?php
    }
} else {
?>
        <div class="card">
            <h3>No Source Codes Yet</h3>
            <p>Upload will appear here</p>
        </div>
<?php
}
?>
</div>


<!-- ================= LOGIN SIGNUP POPUP ================= -->

<?php if(!isset($_SESSION['email'])){ ?>

<div id="authModal" class="modal">

<div class="modal-content">

<div class="tabs">
<button onclick="showLogin()">Login</button>
<button onclick="showSignup()">Signup</button>
</div>

<!-- LOGIN -->
<div id="loginForm">

<?php

/* SUCCESS MESSAGE */

if(isset($_GET['msg'])){

if($_GET['msg']=="signupsuccess"){
echo "<p class='success-msg'>✅ Signup Successful! Please login.</p>";
}

if($_GET['msg']=="passwordreset"){
echo "<p class='success-msg'>✅ Password changed successfully!</p>";
}

}

/* ERROR MESSAGE */

if(isset($_GET['error'])){

if($_GET['error']=="wrongpassword"){
echo "<p class='error-msg'>❌ Wrong Password</p>";
}

if($_GET['error']=="nouser"){
echo "<p class='error-msg'>❌ User not found</p>";
}

if($_GET['error']=="emptyfields"){
echo "<p class='error-msg'>❌ Please fill all fields</p>";
}

if($_GET['error']=="notverified"){
echo "<p class='error-msg'>❌ Please verify your email first</p>";
}

}
?>

<form action="login_process.php" method="POST">

<input type="email" name="email" placeholder="Enter Email" required>

<div style="position:relative;">
<input type="password" name="password" placeholder="Enter Password" required>

<span onclick="togglePassword(this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;">
👁️
</span>
</div>

<button name="login">Login</button>

<p style="text-align:center;margin-top:10px;">
<a href="#" onclick="showForgot()">Forgot Password?</a>
</p>

</form>

</div>

<div id="signupForm" style="display:none;">

<form action="signup_process.php" method="POST">

<input type="text" name="name" placeholder="Enter Name" required>

<div class="email-group">
    <input type="email" id="signup_email" name="email" placeholder="Enter Email" required>
    <button type="button" class="verify-btn" onclick="sendOTP()">Verify</button>
</div>

<div id="otp_section" style="display:none;">
    <input type="text" id="otp" placeholder="Enter OTP">

    <button type="button" onclick="verifyOTP()">Submit OTP</button>

    <p id="otp_timer" style="font-size:12px;color:#555;"></p>

    <button type="button" id="resend_btn" onclick="resendOTP()" disabled>
        Resend OTP
    </button>
</div>

<div style="position:relative;">
<input type="password" id="signup_password" name="password" placeholder="Enter Password" disabled required>
<span onclick="togglePassword(this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;">👁️</span>
</div>

<button type="submit" id="signup_btn" name="signup" disabled>Signup</button>

</form>

</div>

<div id="forgotForm" style="display:none;">

<h3 style="text-align:center;">Forgot Password</h3>

<?php if(!isset($_SESSION['otp_sent'])){ ?>

<form action="forgot_password_process.php" method="POST">
    <input type="email" name="email" placeholder="Enter your registered email" required>
    <button name="send_otp">Send OTP</button>
</form>

<?php } else { ?>

<form method="POST">
    <input type="text" name="otp" placeholder="Enter OTP" required>
    <button name="verify_otp">Verify OTP</button>

    <?php if(isset($_SESSION['otp_error'])){ ?>
        <p class="error-msg"><?php echo $_SESSION['otp_error']; ?></p>
    <?php unset($_SESSION['otp_error']); } ?>
</form>

<p id="forgot_timer"></p>

<button type="button" id="forgot_resend" onclick="resendForgotOTP()" disabled>
    Resend OTP
</button>

<?php } ?>

</div>

<p style="text-align:center;margin-top:10px;">
<a href="#" onclick="showLogin()">Back to Login</a>
</p>

</form>

</div>

</div>

</div>

<?php } ?>

<?php if(isset($_GET['otp'])){ ?>
<script>
window.addEventListener('load', function(){
document.getElementById('authModal').style.display='flex';
showForgot();
startForgotTimer();
});
</script>
<?php } ?>

<!-- RESET PASSWORD MODAL -->
<div id="resetModal" class="modal">

<div class="modal-content">
<span class="close" onclick="closeReset()">&times;</span>

<h2>Set New Password</h2>

<form method="POST" action="reset_password.php">

<input type="password" name="new_password" placeholder="New Password" required>
<input type="password" name="confirm_password" placeholder="Confirm Password" required>

<button type="submit" name="reset">Reset Password</button>

</form>

</div>
</div>
<?php if(isset($_GET['reset']) && isset($_SESSION['otp_verified'])){ 
 ?>
<script>
window.addEventListener('load', function(){

const reset = document.getElementById('resetModal');
const auth = document.getElementById('authModal');

if(auth){
    auth.style.display = 'none';
}

if(reset){
    reset.style.display = 'flex';
}

});
</script>
<?php } ?>
<script src="script.js"></script>

</body>
</html>