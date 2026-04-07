<?php
session_start();
include "../db.php";

if(isset($_POST['upload'])){

$title=$_POST['title'];
$category=$_POST['category'];

$file=$_FILES['file']['name'];
$tmp=$_FILES['file']['tmp_name'];

$folder="uploads/".$category."/";

move_uploaded_file($tmp,$folder.$file);

$conn->query("INSERT INTO resources(title,category,file_name)
VALUES('$title','$category','$file')");

echo "File Uploaded Successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload resource</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
<div class="upload-container">

<h2>Upload Resource</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="title" placeholder="Title">

<select name="category">
<option value="roadmap">Roadmap</option>
<option value="notes">Notes</option>
<option value="sourcecode">Source Code</option>
</select>

<input type="file" name="file">

<button name="upload">Upload File</button>

</form>

</div>
</body>
</html>