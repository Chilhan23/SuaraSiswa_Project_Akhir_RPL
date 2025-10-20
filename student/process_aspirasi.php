<?php

session_start(); 
include "../config/database.php"; 
include "../middleware/auth_check.php";
checkRole("student");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: dashboard.php");
    exit();
}

$title = trim($_POST['title']);
$category = trim($_POST['category']);
$content = trim($_POST['content']);
$student_id_fk = trim($_POST['student_id']); 

if (empty($student_id_fk) || empty($title) || empty($content) || empty($category)) {
    $_SESSION['error'] = "Data aspirasi tidak lengkap. Pastikan Judul, Konten, dan Kategori terisi.";
    
    if (empty($student_id_fk)) {
        $_SESSION['error'] .= " (ID Siswa hilang. Silakan login ulang.)";
    }
    header("Location: dashboard.php");
    exit();
}

$sql_insert = "INSERT INTO aspirations (student_id, title, content, category) 
               VALUES ('$student_id_fk', '$title', '$content', '$category')";

if ($conn->query($sql_insert)) {
    $_SESSION['success'] = "Aspirasi Anda Berhasil Dikirimkan, Selanjutnya Akan DiCek Oleh Admin";
} else {
   
    $_SESSION['error'] = "Aspirasi Gagal Terkirim. Coba lagi. Error Database: " . $conn->error; 
}

$conn->close();
header("Location: dashboard.php");
exit();
?>