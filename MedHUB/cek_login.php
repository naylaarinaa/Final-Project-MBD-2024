<?php
session_start();

$query = new mysqli('localhost', 'root', '', 'mdb');

$username = $_POST['email_user'];
$password = $_POST['password'];

$data = mysqli_query($query, "select * from users where email_user='$email_user'and password='$password'") or die;
(mysqli_error($query));
$cek = mysqli_num_rows($data);

if ($cek > 0) {
    $_SESSION['username'] = $username;
    $_SESSION['status'] = "login";

    header("location:../index.php");
} else {
    header("location:../login.php?pesan=gagal");
} ?>