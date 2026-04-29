<?php
session_start();

$conn = new mysqli("localhost","root","Subhrajit@123","coding_platform");

/* ================= PROFILE PHOTO UPLOAD ================= */
if(isset($_POST['upload_photo'])){

    $email = $_SESSION['email'];

    $photo = $_FILES['profile_photo']['name'];
    $tmp = $_FILES['profile_photo']['tmp_name'];

    $folder = "uploads/profile/".$photo;

    move_uploaded_file($tmp,$folder);

    $conn->query("UPDATE users SET photo='$photo' WHERE email='$email'");

    header("Location: profile.php");
    exit();
}

/* ================= PDF ROADMAP UPLOAD ================= */
if(isset($_POST['upload_pdf'])){

    $title = $_POST['title'];

    $pdfName = $_FILES['pdf']['name'];
    $tmp = $_FILES['pdf']['tmp_name'];

    $folder = "uploads/pdf/".$pdfName;

    $fileType = strtolower(pathinfo($folder, PATHINFO_EXTENSION));

    if($fileType != "pdf"){
        echo "Only PDF allowed!";
        exit();
    }

    move_uploaded_file($tmp,$folder);

    $stmt = $conn->prepare("INSERT INTO roadmaps (title, pdf) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $pdfName);
    $stmt->execute();

    echo "PDF Uploaded!";
}
?>