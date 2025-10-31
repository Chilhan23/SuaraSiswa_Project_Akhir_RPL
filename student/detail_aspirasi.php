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

$student_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$student_class = isset($_SESSION['class']) ? $_SESSION['class'] : '';
$student_id_session = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : null; 
$aspirasi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($aspirasi_id == 0 || !$student_id_session) {
    $_SESSION['error'] = "Akses tidak valid atau ID Aspirasi/Siswa hilang.";
    header("Location: dashboard.php");
    exit();
}

$aspirasi = null;


$sql = "SELECT 
            a.aspiration_id, a.student_id, a.title, a.content, a.category, a.status, 
            a.admin_response, a.admin_id, a.created_at, a.updated_at, a.processed_at, 
            s.name AS student_name, 
            s.class AS student_class,
            adm.name AS admin_processor_name
        FROM 
            aspirations a
        JOIN 
            students s ON a.student_id = s.student_id
        LEFT JOIN 
            admins adm ON a.admin_id = adm.admin_id
        WHERE 
            a.aspiration_id = '$aspirasi_id' AND a.student_id = '$student_id_session'"; 


$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $aspirasi = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "Aspirasi tidak ditemukan atau Anda tidak memiliki akses.";
        header("Location: dashboard.php");
        exit();
    }
} else {
    
    $_SESSION['error'] = "Gagal menjalankan kueri DETAIL ASPIRASI. Error: " . $conn->error;
    header("Location: dashboard.php");
    exit();
}

$conn->close();

$status_class = strtolower($aspirasi['status']);
$status_color = '';
if ($status_class == 'pending') $status_color = 'bg-warning text-dark';
elseif ($status_class == 'diterima' || $status_class == 'diproses') $status_color = 'bg-primary';
elseif ($status_class == 'selesai') $status_color = 'bg-success';
elseif ($status_class == 'ditolak') $status_color = 'bg-danger';

// Variabel KONDISI EDIT
$is_editable = ($aspirasi['status'] == 'Pending');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Aspirasi - SuaraSiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../asset/css/dashboard_student.css">
    <style>
        /* CSS tambahan agar detail terlihat rapi */
        .detail-card {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .response-box {
            border-left: 5px solid #007bff;
            background-color: #e9f5ff;
            padding: 20px;
            border-radius: 10px;
            margin-top: 25px;
        }
        /* Style untuk header agar terlihat konsisten */
        .dashboard-header {
            background-color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-left, .profile-info {
            display: flex;
            align-items: center;
        }
        
    </style>
</head>
<body>
    
    <header class="dashboard-header">
        <div class="header-left">
            <a href="dashboard.php" class="btn-logout text-primary me-3">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
            </a>
            <h4 class="d-none d-md-block mb-0">
                <i class="bi bi-lightbulb-fill me-2"></i> Detail Aspirasi
            </h4>
        </div>
        
    </header>

    <div class="dashboard-content">

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <h1 class="mb-4 fw-bold"><?= htmlspecialchars($aspirasi['title']) ?></h1>
        
        <div class="detail-card">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-3 text-primary"><i class="bi bi-card-text me-2"></i>Isi Aspirasi</h5>
                    <p class="lead" style="white-space: pre-wrap;"><?= htmlspecialchars($aspirasi['content']) ?></p>
                    
                    <hr class="my-4">

                    <h5 class="mb-3 text-primary"><i class="bi bi-person-gear me-2"></i>Tanggapan Admin</h5>
                    
                    <?php if (empty($aspirasi['admin_response'])): ?>
                        <div class="alert alert-light text-center" role="alert">
                            <i class="bi bi-info-circle me-1"></i> Admin belum memberikan tanggapan resmi.
                        </div>
                    <?php else: ?>
                        <div class="response-box">
                            <p class="fw-bold mb-2">Dari: <?= htmlspecialchars($aspirasi['admin_processor_name'] ?? 'Admin Sekolah') ?></p>
                            <p style="white-space: pre-wrap;"><?= htmlspecialchars($aspirasi['admin_response']) ?></p>
                            <small class="text-muted">Ditanggapi pada: <?= date('d M Y H:i', strtotime($aspirasi['processed_at'])) ?></small>
                        </div>
                    <?php endif; ?>

                </div>
                
                <div class="col-lg-4">
                    <div class="p-4 rounded-3 border">
                        <h6 class="fw-bold text-muted mb-3">Status dan Info</h6>
                        
                        <div class="mb-3">
                            <span class="d-block small text-muted">Status Saat Ini</span>
                            <span class="badge <?= $status_color ?> fs-6 p-2 mt-1"><?= htmlspecialchars($aspirasi['status']) ?></span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="d-block small text-muted">Kategori</span>
                            <p class="fw-bold mb-0"><?= htmlspecialchars($aspirasi['category']) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <span class="d-block small text-muted">Diajukan Oleh</span>
                            <p class="fw-bold mb-0"><?= htmlspecialchars($aspirasi['student_name']) ?></p>
                            <small class="text-muted"><?= htmlspecialchars($aspirasi['student_class']) ?></small>
                        </div>

                        <div class="mb-3">
                            <span class="d-block small text-muted">Tanggal Dibuat</span>
                            <p class="mb-0"><?= date('d M Y H:i', strtotime($aspirasi['created_at'])) ?></p>
                        </div>
                        
                        <?php if ($aspirasi['status'] == 'Pending'): ?>
                            <small class="text-danger fw-bold mt-3 d-block">Aspirasi masih menunggu peninjauan awal.</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>