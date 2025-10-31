<?php
session_start();
include "../middleware/auth_check.php";
include "../config/database.php"; 
checkRole('student'); 

if (!isset($conn) || $conn->connect_error) {
    $_SESSION['error'] = "Koneksi database gagal.";
    header("Location: dashboard.php");
    exit();
}

$aspirasi_id = isset($_POST['aspirasi_id']) ? intval($_POST['aspirasi_id']) : 0;
$student_id_session = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : 0;
$title = trim($_POST['title']);
$content = trim($_POST['content']);
$category = trim($_POST['category']); 

if ($aspirasi_id == 0 || $student_id_session == 0 || empty($title) || empty($content) || empty($category)) {
    $_SESSION['error'] = "Data tidak lengkap. Harap isi semua field.";
    header("Location: detail_aspirasi.php?id=" . $aspirasi_id); 
    exit();
}
$success = false;
$sql_check = "SELECT status FROM aspirations WHERE aspiration_id = '$aspirasi_id' AND student_id = '$student_id_session'";
$result_check = $conn->query($sql_check);

if ($result_check) {
    $row = $result_check->fetch_assoc();
    
    if ($row && $row['status'] == 'Pending') {
        $sql_update = "UPDATE aspirations SET title='$title', content='$content', category='$category' WHERE aspiration_id='$aspirasi_id' AND student_id='$student_id_session'";
        
        if ($conn->query($sql_update)) {
            $success = true;
            $_SESSION['success'] = "Aspirasi berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Gagal memperbarui aspirasi. Error SQL: " . $conn->error;
        }

    } else {
        $_SESSION['error'] = "Aspirasi tidak dapat diedit karena status sudah: " . ($row ? $row['status'] : 'Tidak Ditemukan');
    }
} else {
    $_SESSION['error'] = "Gagal memeriksa status aspirasi. Error SQL: " . $conn->error;
}
$conn->close();
header("Location: dashboard.php");
exit();
?>