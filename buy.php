<?php
session_start();

/* ================= LOGIN CHECK ================= */

if(!isset($_SESSION['email'])){
header("Location: index.php");
exit();
}

/* ================= COURSE VALIDATION ================= */

$course = $_GET['course'] ?? '';

if($course == ''){
die("<h2 style='text-align:center;margin-top:50px;'>Invalid Request</h2>");
}

/* ================= PRODUCT LIST ================= */

$products = [

"html" => ["name"=>"HTML Notes","price"=>49],
"css" => ["name"=>"CSS Notes","price"=>49],
"js" => ["name"=>"JavaScript Notes","price"=>59],
"python" => ["name"=>"Python Notes","price"=>69],

"python_source" => ["name"=>"Python Source Code","price"=>99],
"java_source" => ["name"=>"Java Source Code","price"=>99],
"login_system" => ["name"=>"PHP Login System","price"=>79],
"portfolio" => ["name"=>"Portfolio Website","price"=>79]

];

/* ================= PRODUCT VALIDATION ================= */

if(!array_key_exists($course,$products)){
die("<h2 style='text-align:center;margin-top:50px;'>Product Not Found</h2>");
}

$product = $products[$course];

?>

<!DOCTYPE html>
<html>
<head>

<title>Buy <?php echo $product['name']; ?></title>
<link rel="stylesheet" href="style.css">

<style>

.buy-box{
background:white;
max-width:450px;
margin:80px auto;
padding:40px;
border-radius:12px;
text-align:center;
box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

.buy-box h2{
margin-bottom:15px;
}

.price{
font-size:22px;
margin:20px 0;
color:#2c3e50;
font-weight:600;
}

</style>

</head>

<body>

<div class="buy-box">

<h2><?php echo $product['name']; ?></h2>

<p class="price">
Price : ₹<?php echo $product['price']; ?>
</p>

<a href="payment.php?course=<?php echo $course; ?>">
<button class="btn lock-btn">
Pay ₹<?php echo $product['price']; ?> & Unlock
</button>
</a>

</div>

</body>
</html>