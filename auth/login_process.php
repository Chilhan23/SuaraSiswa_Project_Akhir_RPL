<?php
session_start(); 

include "../config/database.php"; 

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit();
}

$identifier = isset($_POST['identifier']) ? trim($_POST['identifier']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($identifier) || empty($password)) {
    $_SESSION['error'] = "NIS/Username dan Password harus diisi.";
    header("Location: login.php");
    exit();
}
$conn->real_escape_string($identifier); 

$loggedIn = false;
$user_data = null;
$user_type = null;
$sql_admin = "SELECT * FROM admins WHERE username = '$identifier' LIMIT 1";
$result_admin = $conn->query($sql_admin);

if ($result_admin && $result_admin->num_rows > 0) {
    $user_data = $result_admin->fetch_assoc();
    if ($password === $user_data['password']) { 
        $loggedIn = true;
        $user_type = 'admin';
    } 
}

if (!$loggedIn) {
    $sql_student = "SELECT * FROM students WHERE nis = '$identifier' OR name = '$identifier' LIMIT 1";
    $result_student = $conn->query($sql_student);

    if ($result_student && $result_student->num_rows > 0) {
        $user_data = $result_student->fetch_assoc();
        if (password_verify($password, $user_data['password'])) { 
            $loggedIn = true;
            $user_type = 'student';
        }
    }
}

if ($loggedIn) {
    $_SESSION['user_type'] = $user_type;
    $_SESSION['user_name'] = $user_data['name']; 

    if ($user_type == "admin") {
        $_SESSION['id_user'] = $user_data['admin_id']; 
        $_SESSION['user_name'] = $user_data['username']; 
        header("Location: ../admin/dashboard.php");
    } else {
        $_SESSION['id_user'] = $user_data['student_id']; 
        $_SESSION['user_identifier'] = $user_data['nis']; 
        $_SESSION['user_name'] = $user_data['name'];
        $_SESSION['class'] = isset($user_data['class']) ? $user_data['class'] : ''; 
        header("Location: ../student/dashboard.php");
    }
    
    exit();
} else {

    $_SESSION['error'] = "NIS/Username atau Password salah.";
}

$conn->close();
header("Location: login.php");
exit();

?>