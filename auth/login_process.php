<?php
session_start(); 
include "../config/database.php"; 

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit();
}

$identifier = isset($_POST['identifier']) ? trim($_POST['identifier']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$user_type = isset($_POST['user_type']) ? $_POST['user_type'] : 'student';


if ($user_type == "student") {
    if (empty($identifier) || empty($password)) {
        $_SESSION['error'] = "NIS dan Password harus diisi.";
        header("Location: login.php");
        exit();
    }
} else {
    if (empty($identifier) || empty($password)) {
        $_SESSION['error'] = "Username dan Password harus diisi.";
        header("Location: login.php");
        exit();
    }
}


if ($user_type == 'student') {
    $table = 'students';
    $identifier_column = 'nis';
} else {
    $table = 'admins';
    $identifier_column = 'username';
}

$sql = "SELECT * FROM $table WHERE $identifier_column = '$identifier'";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
        $_SESSION['user_nis'] = $user[$identifier_column];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_type'] = $user_type;
        $_SESSION['class'] = isset($user['class']) ? $user['class'] : '';
        if ($user_type == "student") { 
            $_SESSION['id_user'] = $user['student_id'];
        } else {
           $_SESSION['id_user'] = $user['admin_id'];
        }
        
        
        if ($user_type == "student") { 
            header("Location: ../student/dashboard.php");
        } else {
            header("Location: ../admin/dashboard.php");
        }
        $conn->close();
        exit();
        
} else {
    $_SESSION['error'] = "NIS/Username tidak ditemukan.";
}
$conn->close();
header("Location: login.php");
exit();
