<?php
session_start();
include("db.php");

/* RESET FLOW CLEANUP */
if(isset($_GET['reset'])){
    unset($_SESSION['otp_sent']);
    unset($_SESSION['otp_verified']);
    unset($_SESSION['reset_email']);
    header("Location: index.php");
    exit();
}

/* PHPMailer */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';


/* ========================================================= */
/* ================= SEND OTP (FIRST TIME) ================== */
/* ========================================================= */

if(isset($_POST['send_otp'])){

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if(!$email){
        header("Location: index.php?error=invalidemail");
        exit();
    }

    // check user exists
    $stmt = $conn->prepare("SELECT email FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0){
        header("Location: index.php?error=invalidemail");
        exit();
    }

    // store session
    $_SESSION['reset_email'] = $email;
    $_SESSION['otp_sent'] = true;

    // send OTP
    if(sendOTP($email, $conn)){
        header("Location: index.php?otp=sent");
        exit();
    }else{
        header("Location: index.php?error=mailfail");
        exit();
    }
}


/* ========================================================= */
/* ================= RESEND OTP (AJAX) ====================== */
/* ========================================================= */

if(isset($_POST['resend'])){

    if(!isset($_SESSION['reset_email'])){
        echo "expired";
        exit();
    }

    $email = $_SESSION['reset_email'];

    if(sendOTP($email, $conn)){
        echo "resent";
    }else{
        echo "fail";
    }

    exit();
}


/* ========================================================= */
/* ================= OTP FUNCTION =========================== */
/* ========================================================= */

function sendOTP($email, $conn){

    $otp = random_int(100000,999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // save OTP in DB
    $stmt = $conn->prepare("UPDATE users SET otp=?, otp_expiry=? WHERE email=?");
    $stmt->bind_param("sss",$otp,$expiry,$email);

    if(!$stmt->execute()){
        return false;
    }

    // send mail
    $mail = new PHPMailer(true);

    try{
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dinirixtechlabs@gmail.com';
        $mail->Password = 'tpby xiop afjw nmcq';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('dinirixtechlabs@gmail.com', 'DTL Byte');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset OTP';
        $mail->Body = "
            <h3>Your OTP Code</h3>
            <p>Your OTP is: <b>$otp</b></p>
            <p>Valid for 10 minutes</p>
        ";

        $mail->send();
        return true;

    }catch(Exception $e){
        return false;
    }
}
?>