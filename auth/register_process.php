<?php
session_start(); 
include "../config/database.php"; 
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: register.php");
    exit();
}


$nis = trim($_POST['nis']);
$class = trim($_POST['class']);
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];


if ($password !== $confirm_password) {
    $_SESSION['error'] = "Password Tidak Sama!";
    header("Location: register.php");
    exit(); 
}

$final_password = password_hash($password, PASSWORD_DEFAULT);
$sql_check = "SELECT nis FROM students WHERE nis = '$nis'";
$result_check = $conn->query($sql_check);

if ($result_check && $result_check->num_rows > 0) {
    $_SESSION['error'] = "NIS yang Anda masukkan sudah terdaftar.";
    header("Location: register.php");
    exit();
}

$sql_insert = "INSERT INTO students (nis, name, email, password, class, phone) 
               VALUES ('$nis', '$name', '$email', '$final_password', '$class', '$phone')";
               
$query_insert = $conn->query($sql_insert);

if ($query_insert) {
    $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
    $conn->close();
    header("Location: login.php");
    exit();
} else {
    $_SESSION['error'] = "Registrasi gagal. Coba lagi. Error: " . $conn->error;
    $conn->close();
    header("Location: register.php");
    exit();
}

$conn->close();
header("Location: register.php");
exit();
?>