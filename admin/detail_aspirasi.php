<?php
// admin/detail_aspirasi.php
session_start();
include "../middleware/auth_check.php"; 
include "../config/database.php"; 
checkRole('admin'); 

$admin_id_session = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : null; 
$admin_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin Sekolah';
$aspirasi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($aspirasi_id == 0 || !$admin_id_session) {
    $_SESSION['error'] = "Akses tidak valid.";
    header("Location: dashboard.php");
    exit();
}

$aspirasi = null;
$statuses = ['Pending', 'Diterima', 'Diproses', 'Selesai', 'Ditolak']; 
$sql = "SELECT 
            a.aspiration_id, a.student_id, a.title, a.content, a.category, a.status, 
            a.admin_response, a.admin_id, a.created_at, a.updated_at, a.processed_at, 
            s.class AS student_class,
            adm.name AS admin_processor_name
        FROM 
            aspirations a
        JOIN 
            students s ON a.student_id = s.student_id
        LEFT JOIN 
            admins adm ON a.admin_id = adm.admin_id
        WHERE 
            a.aspiration_id = $aspirasi_id";
$result = $conn->query($sql);
if ($result) {
    if ($result->num_rows > 0) {
        $aspirasi = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "Aspirasi tidak ditemukan.";
        header("Location: dashboard.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Gagal menyiapkan kueri detail. Error: " . $conn->error;
    header("Location: dashboard.php");
    exit();
}

$conn->close();

// Tentukan warna status
$status_class = strtolower($aspirasi['status']);
$status_color = '';
if ($status_class == 'pending') $status_color = 'bg-warning text-dark';
elseif ($status_class == 'diterima' || $status_class == 'diproses') $status_color = 'bg-primary';
elseif ($status_class == 'selesai') $status_color = 'bg-success';
elseif ($status_class == 'ditolak') $status_color = 'bg-danger';

$no_edit = $aspirasi['status'] == 'Selesai'
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Aspirasi - <?= $aspirasi['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../asset/css/dashboard_student.css">
    </head>
<body>
    
    <header class="dashboard-header">
        <div class="header-left">
            <a href="dashboard.php" class="btn-logout text-primary me-3">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
            </a>
            <h4 class="d-none d-md-block mb-0">
                <i class="bi bi-person-gear me-2"></i> Proses Aspirasi
            </h4>
        </div>
        
        <div class="profile-info">
            <div class="profile-text text-end">
                <h6 class="mb-0"><?= htmlspecialchars($admin_name) ?></h6>
                <small class="text-muted">Admin</small>
            </div>
            <div class="profile-avatar">
                <?= strtoupper(substr($admin_name, 0, 1)) ?>
            </div>
        </div>
    </header>

    <div class="dashboard-content">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
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
                    <p class="mb-1 fw-bold">Kategori: <?= htmlspecialchars($aspirasi['category']) ?></p>
                    <p class="lead" style="white-space: pre-wrap;"><?= htmlspecialchars($aspirasi['content']) ?></p>
                    
                    <hr class="my-4">

                    <form action="process_admin_update.php" method="POST">
                        <input type="hidden" name="aspirasi_id" value="<?= $aspirasi_id ?>">
                        <input type="hidden" name="admin_id" value="<?= $admin_id_session ?>">

                        <h5 class="mb-3 text-success"><i class="bi bi-pencil-square me-2"></i>Tindak Lanjut</h5>
                        
                        <div class="mb-3">
                            <label for="new_status" class="form-label fw-bold">Ubah Status</label>
                            <?php if ($no_edit): ?>
                                <p class="fw-bold mb-0"><?= htmlspecialchars($aspirasi['status']) ?></p>
                            <?php else: ?>
                            <select class="form-select" id="new_status" name="new_status" required>
                                <?php foreach ($statuses as $s): ?>
                                    <option value="<?= htmlspecialchars($s) ?>"
                                        <?= ($aspirasi['status'] === $s) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="admin_response" class="form-label fw-bold">Tanggapan Resmi Admin</label>
                            <?php if ($no_edit): ?>
                             <p class="lead" style="white-space: pre-wrap;"><?= htmlspecialchars($aspirasi['admin_response']) ?></p>
                            <?php else: ?>
                            <textarea class="form-control" id="admin_response" name="admin_response" rows="6" required><?= htmlspecialchars($aspirasi['admin_response']) ?></textarea>
                            <?php endif; ?>
                            <div class="form-text">Berikan tanggapan yang jelas dan informatif.</div>
                        </div>

                    <?php if($no_edit): ?>
                        <button type="button" class="btn btn-lg btn-secondary" disabled>
                            <i class="bi bi-lock me-2"></i> Tidak Dapat Diedit karena Sudah Selesai
                        </button>
                    <?php else: ?>
                        <button type="submit" name="submit_update" class="btn btn-primary mt-3">
                            <i class="bi bi-check-circle me-2"></i> Simpan Tindak Lanjut
                        </button>
                    <?php endif; ?>
                    </form>

                </div>
                
                <div class="col-lg-4">
                    <div class="p-4 rounded-3 border">
                        <h6 class="fw-bold text-muted mb-3">Informasi Aspirasi</h6>
                        
                        <div class="mb-3">
                            <span class="d-block small text-muted">Status Saat Ini</span>
                            <span class="badge <?= $status_color ?> fs-6 p-2 mt-1"><?= htmlspecialchars($aspirasi['status']) ?></span>
                        </div>

                        <div class="mb-3">
                            <span class="d-block small text-muted">Diajukan Oleh</span>
                            <small class="text-muted"><?= htmlspecialchars($aspirasi['student_class']) ?></small>
                        </div>

                        <div class="mb-3">
                            <span class="d-block small text-muted">Tanggal Dibuat</span>
                            <p class="mb-0"><?= date('d M Y H:i', strtotime($aspirasi['created_at'])) ?></p>
                        </div>

                        <?php if ($aspirasi['processed_at']): ?>
                            <div class="mb-3">
                                <span class="d-block small text-muted">Terakhir Diproses Oleh</span>
                                <p class="mb-0 fw-bold"><?= htmlspecialchars($aspirasi['admin_processor_name'] ?? 'Admin') ?></p>
                                <small class="text-muted">Pada: <?= date('d M Y H:i', strtotime($aspirasi['processed_at'])) ?></small>
                            </div>
                        <?php else: ?>
                              <span class="d-block small text-muted">Belum Ada Yang MemProses</span>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>