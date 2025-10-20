<?php
// admin/process_admin_update.php

session_start();
include "../middleware/auth_check.php";
include "../config/database.php"; 
checkRole('admin'); 



if (!isset($conn) || $conn->connect_error) {
    $_SESSION['error'] = "Koneksi database gagal.";
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST['submit_update'])) {
    header("Location: dashboard.php");
    exit();
}

$aspirasi_id = isset($_POST['aspirasi_id']) ? intval($_POST['aspirasi_id']) : 0;
$admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 0; // ID admin yang sedang login
$new_status = trim($_POST['new_status']);
$admin_response = trim($_POST['admin_response']);

if ($aspirasi_id == 0 || $admin_id == 0 || empty($new_status) || empty($admin_response)) {
    $_SESSION['error'] = "Semua field harus diisi. Proses gagal.";
    header("Location: detail_aspirasi.php?id=" . $aspirasi_id);
    exit();
}

$sql_update = "UPDATE aspirations 
               SET status='$new_status', admin_response='$admin_response', admin_id='$admin_id', processed_at=NOW()
               WHERE aspiration_id='$aspirasi_id'";

if ($conn->query($sql_update)) {
    $_SESSION['success'] = "Status dan tanggapan aspirasi ID $aspirasi_id berhasil diperbarui!";
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['error'] = "Gagal memperbarui aspirasi. Error SQL: " . $conn->error;
}

$conn->close();

// Redirect kembali ke halaman detail yang sama
header("Location: detail_aspirasi.php?id=" . $aspirasi_id);
exit();
?>