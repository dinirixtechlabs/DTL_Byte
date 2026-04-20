<?php
session_start();

if(!isset($_POST['otp'])){
    echo "fail";
    exit();
}

$otp = trim($_POST['otp']);

// ✅ Validate OTP format (must be 6 digits)
if(empty($otp) || !preg_match('/^[0-9]{6}$/', $otp)){
    echo "invalid";
    exit();
}

// ✅ Check required session data
if(!isset($_SESSION['signup_otp']) || !isset($_SESSION['signup_email'])){
    echo "session_expired";
    exit();
}

// ✅ Check expiry
if(isset($_SESSION['signup_expiry'])){
    if(time() > $_SESSION['signup_expiry']){
        unset($_SESSION['signup_otp']);
        unset($_SESSION['signup_expiry']);
        echo "expired";
        exit();
    }
}

// ✅ Verify OTP
if($otp == $_SESSION['signup_otp']){

    $_SESSION['email_verified'] = true;

    // ✅ Clear OTP after success
    unset($_SESSION['signup_otp']);
    unset($_SESSION['signup_expiry']);

    echo "success";

}else{
    echo "fail";
}
?>