<?php
session_start();
include "../db.php";

// HANDLE FORM SUBMIT
if(isset($_POST['upload'])){

    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $course = $_POST['course'];

    if(empty($title) || empty($category) || empty($course) || empty($_FILES['file']['name'])){
        echo "All fields are required!";
        exit();
    }

    $file = $_FILES['file']['name'];
    $tmp  = $_FILES['file']['tmp_name'];
    $size = $_FILES['file']['size'];

    $fileName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", $file);
    $folder = "../uploads/pdf/";

    if(!is_dir($folder)){
        mkdir($folder, 0777, true);
    }

    $filePath = $folder . $fileName;
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if(in_array($category, ["roadmap", "notes"]) && $fileType != "pdf"){
        echo "Only PDF allowed!";
        exit();
    }

    if($category == "sourcecode" && !in_array($fileType, ["zip", "rar"])){
        echo "Only ZIP or RAR allowed!";
        exit();
    }

    if($size > 5 * 1024 * 1024){
        echo "File too large!";
        exit();
    }

    if(move_uploaded_file($tmp, $filePath)){
        $stmt = $conn->prepare("INSERT INTO resources(title, category, file_name, course) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $category, $fileName, $course);
        $stmt->execute();

        echo "<script>alert('Upload Successful'); window.location='upload_resources.php';</script>";
    } else {
        echo "Upload Failed!";
    }
}
?>

<!-- ✅ ADD THIS HTML FORM -->
<!DOCTYPE html>
<html>
<head>
    <title>Upload Resources</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

<div class="upload-container">

    <h2>Upload Resource</h2>
    <p class="subtitle">Add new learning materials for students</p>

    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" placeholder="Enter resource title" required>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="roadmap">Roadmap</option>
                <option value="notes">Notes</option>
                <option value="sourcecode">Source Code</option>
            </select>
        </div>

        <div class="form-group">
            <label>Course</label>
            <input type="text" name="course" placeholder="Enter course name" required>
        </div>

        <div class="form-group">
            <label>Upload File</label>
            <input type="file" name="file" required>
        </div>

        <button type="submit" name="upload">Upload Resource</button>

    </form>

</div>

</body>
</html>