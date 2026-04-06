<?php
session_start();
include("db.php");

/* ---------- LOGIN POPUP CONTROL ---------- */

if(!isset($_SESSION['email']) || isset($_GET['error']) || isset($_GET['msg'])){
echo "<script>
window.onload=function(){
document.getElementById('authModal').style.display='flex';
}
</script>";
}

/* ---------- GET USER PROFILE PHOTO ---------- */

$profilePhoto = "images/profile_icon.png";

if(isset($_SESSION['email'])){

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT photo FROM users WHERE email=?");
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

<a href="#">Dashboard</a>
<a href="#notes">Notes</a>
<a href="#roadmaps">Roadmaps</a>
<a href="#source">Source Codes</a>
<a href="payment_history.php">Payments</a>
<a href="profile.php">Profile</a>

</div>


<!-- ================= MAIN CONTENT ================= -->

<div class="main-content">

<div class="container">


<!-- ROADMAP -->

<h2 id="roadmaps" class="section-title">Roadmaps</h2>

<div class="cards">

<div class="card">
<h3>HTML Roadmap</h3>
<p>Complete HTML learning path</p>
<a href="roadmaps/html-roadmap.pdf" class="btn" target="_blank">View Roadmap</a>
</div>

<div class="card">
<h3>CSS Roadmap</h3>
<p>Step-by-step CSS learning</p>
<a href="roadmaps/css-roadmap.pdf" class="btn" target="_blank">View Roadmap</a>
</div>

<div class="card">
<h3>JavaScript Roadmap</h3>
<p>Modern JavaScript roadmap</p>
<a href="roadmaps/js-roadmap.pdf" class="btn" target="_blank">View Roadmap</a>
</div>

<div class="card">
<h3>Python Roadmap</h3>
<p>Backend & automation roadmap</p>
<a href="roadmaps/python-roadmap.pdf" class="btn" target="_blank">View Roadmap</a>
</div>

</div>


<!-- NOTES -->

<h2 id="notes" class="section-title">Notes</h2>

<div class="cards">

<div class="card">
<h3>HTML Notes</h3>
<p>Beginner to advanced notes</p>
<a href="payment.php?item=html_notes" class="btn lock-btn">Unlock Notes</a>
</div>

<div class="card">
<h3>CSS Notes</h3>
<p>Complete CSS guide</p>
<a href="payment.php?item=css_notes" class="btn lock-btn">Unlock Notes</a>
</div>

<div class="card">
<h3>JavaScript Notes</h3>
<p>Modern JS concepts</p>
<a href="payment.php?item=js_notes" class="btn lock-btn">Unlock Notes</a>
</div>

<div class="card">
<h3>Python Notes</h3>
<p>Python programming guide</p>
<a href="payment.php?item=python_notes" class="btn lock-btn">Unlock Notes</a>
</div>

</div>


<!-- SOURCE CODE -->

<h2 id="source" class="section-title">Source Codes</h2>

<div class="cards">

<div class="card">
<h3>Python Projects</h3>
<p>Beginner Python projects</p>
<a href="payment.php?item=python_source" class="btn lock-btn">Unlock Source</a>
</div>

<div class="card">
<h3>Java Projects</h3>
<p>Java practice projects</p>
<a href="payment.php?item=java_source" class="btn lock-btn">Unlock Source</a>
</div>

<div class="card">
<h3>Login System</h3>
<p>PHP Login system project</p>
<a href="payment.php?item=login_source" class="btn lock-btn">Unlock Source</a>
</div>

<div class="card">
<h3>Portfolio Website</h3>
<p>HTML CSS portfolio project</p>
<a href="payment.php?item=portfolio_source" class="btn lock-btn">Unlock Source</a>
</div>

</div>


</div>
</div>
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


<!-- SIGNUP -->

<div id="signupForm" style="display:none;">

<form action="signup_process.php" method="POST">

<input type="text" name="name" placeholder="Enter Name" required>

<input type="email" name="email" placeholder="Enter Email" required>

<div style="position:relative;">
<input type="password" name="password" placeholder="Enter Password" required>

<span onclick="togglePassword(this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;">
👁️
</span>
</div>

<button name="signup">Signup</button>

</form>

</div>

<div id="forgotForm" style="display:none;">

<form action="forgot_process.php" method="POST">

<h3 style="text-align:center;">Reset Password</h3>

<input type="email" name="email" placeholder="Enter your registered email" required>

<input type="password" name="new_password" placeholder="Enter New Password" required>

<input type="password" name="confirm_password" placeholder="Confirm Password" required>

<button name="reset">Change Password</button>

<p style="text-align:center;margin-top:10px;">
<a href="#" onclick="showLogin()">Back to Login</a>
</p>

</form>

</div>

</div>

</div>

<?php } ?>


<script src="script.js"></script>

</body>
</html>