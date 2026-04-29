<?php
session_start();
include "../db.php";

if(isset($_POST['upload'])){

    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $course = $_POST['course']; // ✅ NEW

    if(empty($title) || empty($category) || empty($course) || empty($_FILES['file']['name'])){
        echo "All fields are required!";
        exit();
    }

    $file = $_FILES['file']['name'];
    $tmp  = $_FILES['file']['tmp_name'];
    $size = $_FILES['file']['size'];

    // ✅ Unique filename
    $fileName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", $file);

    // ✅ Folder path
    $folder = "../uploads/pdf/";

    if(!is_dir($folder)){
        mkdir($folder, 0777, true);
    }

    $filePath = $folder . $fileName;

    // ✅ File type
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // ✅ Validation
    if(in_array($category, ["roadmap", "notes"])){
        if($fileType != "pdf"){
            echo "Only PDF allowed!";
            exit();
        }
    }

    if($category == "sourcecode"){
        if(!in_array($fileType, ["zip", "rar"])){
            echo "Only ZIP or RAR allowed!";
            exit();
        }
    }

    // ✅ File size limit (5MB)
    if($size > 5 * 1024 * 1024){
        echo "File too large! Max 5MB allowed.";
        exit();
    }

    // ✅ Upload
    if(move_uploaded_file($tmp, $filePath)){

        // ✅ UPDATED QUERY (with course)
        $stmt = $conn->prepare("INSERT INTO resources(title, category, file_name, course) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $category, $fileName, $course);
        $stmt->execute();

        echo "<script>alert('Upload Successful'); window.location='upload_resources.php';</script>";

    } else {
        echo "Upload Failed!";
    }
}
?>