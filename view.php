<?php

$file = $_GET['file'];

$path = "uploads/pdf/" . $file;

if(file_exists($path)){
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename='".basename($path)."'");
    readfile($path);
} else {
    echo "File not found!";
}
?>