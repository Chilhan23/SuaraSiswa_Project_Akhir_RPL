<?php
session_start();
include "../middleware/auth_check.php"; 
include "../config/database.php"; 
checkRole('student'); 
$student_id_session = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : null; 
$delete = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($delete == 0 || !$student_id_session) {
    $_SESSION['error'] = "Akses tidak valid atau ID Aspirasi/Siswa hilang.";
    header("Location: dashboard.php");
    exit();
}



$sql = "DELETE FROM aspirations WHERE aspiration_id = $delete"; 
$query_delete = $conn->query($sql);
if ($query_delete) {
    $_SESSION['success'] = "Aspirasi Berhasil Dihapus";
    $conn->close();
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['error'] = "Aspirasi gagal dihapus. Coba lagi. Error: " . $conn->error;
    $conn->close();
    header("Location: dashboard.php");
    exit();
}


$conn->close();
header("Location: dashboard.php");
?>