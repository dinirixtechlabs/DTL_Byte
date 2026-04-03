<?php

$host="localhost";
$user="root";
$password="Subhrajit@123";
$db="coding_platform";

$conn = new mysqli($host,$user,$password,$db);

if($conn->connect_error){
die("Connection failed");
}

?>