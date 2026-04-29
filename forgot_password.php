<?php
include "db.php";

if(isset($_POST['reset'])){

$email = $_POST['email'];
$new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){

    $update = "UPDATE users SET password='$new_password' WHERE email='$email'";
    mysqli_query($conn, $update);

    header("Location: index.php?msg=reset_success");
    exit();

}else{
    header("Location: index.php?msg=user_not_found");
    exit();
}
}
?>