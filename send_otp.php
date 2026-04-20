<?php
session_start();
include "db.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';


/* ========================================================= */
/* ================= SEND / RESEND OTP ====================== */
/* ========================================================= */

if(isset($_POST['email']) || isset($_POST['resend'])){

    // ================= RESEND CASE =================
    if(isset($_POST['resend'])){

        if(!isset($_SESSION['signup_email'])){
            echo "expired";
            exit();
        }

        $email = $_SESSION['signup_email'];

    } 
    // ================= FIRST SEND =================
    else{

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        if(!$email){
            echo "invalid";
            exit();
        }

        // check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0){
            echo "exists";
            exit();
        }

        $_SESSION['signup_email'] = $email;
    }


    // ================= GENERATE OTP =================
    $otp = random_int(100000,999999);
    $expiry = time() + 300; // 5 minutes

    $_SESSION['signup_otp'] = $otp;
    $_SESSION['signup_expiry'] = $expiry;


    // ================= SEND MAIL =================
    $mail = new PHPMailer(true);

    try{
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dinirixtechlabs@gmail.com';
        $mail->Password =  'tpby xiop afjw nmcq';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('dinirixtechlabs@gmail.com', 'DTL Byte');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Email Verification OTP';
        $mail->Body = "
            <h3>Email Verification</h3>
            <p>Your OTP is:</p>
            <h2 style='color:#2ecc71;'>$otp</h2>
            <p>This OTP will expire in 5 minutes.</p>
        ";

        $mail->send();

        // response for JS
        if(isset($_POST['resend'])){
            echo "resent";
        }else{
            echo "sent";
        }

    }catch(Exception $e){
        echo "fail";
    }

    exit();
}
?>