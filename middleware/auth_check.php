<?php
session_start(); 
if (!isset($_SESSION['user_type']) || !isset($_SESSION['id_user'])) {
    $_SESSION['error'] = "Anda harus login untuk mengakses halaman tersebut.";
    header("Location: ../auth/login.php"); 
    exit();
}


function checkRole($required_role) {
    if ($_SESSION['user_type'] !== $required_role) {
        $error_message = "Akses Ditolak. Role Anda harus '$required_role' untuk melihat halaman ini.";
        $redirect_page = ($_SESSION['user_type'] == 'admin') ? '../admin/dashboard.php' : '../student/dashboard.php';
        $_SESSION['error'] = $error_message;
        header("Location: " . $redirect_page); 
        exit();
    }
}   
?>
