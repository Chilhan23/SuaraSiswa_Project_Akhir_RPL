<?php
session_start();
include "../middleware/auth_check.php"; 
include "../config/database.php"; 
checkRole('student'); 

$student_id = isset($_SESSION['id_user']) ? $conn->real_escape_string(intval($_SESSION['id_user'])) : ''; 
$student_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Siswa';
$student_class = isset($_SESSION['class']) ? $_SESSION['class'] : 'Tidak Diketahui';
$student_nis = isset($_SESSION['user_nis']) ? $_SESSION['user_nis'] : 'Tidak Diketahui';

$total_aspirasi = 0;
$menunggu = 0; 
$ditinjau = 0;
$selesai = 0;
$ditolak = 0;

$categories = ['Fasilitas', 'Akademik', 'Kebersihan', 'Kantin', 'Ekstrakurikuler', 'Teknologi', 'Lainnya'];

$current_success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$current_error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['success']);
unset($_SESSION['error']);


$aspirations = [];
if ($student_id) {
    $sql_aspirations = "SELECT 
                            aspirations.*, 
                            students.name AS student_name, 
                            students.class AS student_class
                        FROM 
                            aspirations
                        JOIN 
                            students ON aspirations.student_id = students.student_id
                        WHERE 
                            aspirations.student_id = '$student_id' 
                        ORDER BY 
                            aspirations.created_at DESC";

    $result = $conn->query($sql_aspirations);
    
    if ($result) {
        if ($result->num_rows > 0) {
            $total_aspirasi = $result->num_rows;
            while($row = $result->fetch_assoc()) {
                $aspirations[] = $row;
                $status_lower = strtolower($row['status']);
                if ($status_lower == 'pending') {
                    $menunggu++;
                } elseif ($status_lower == 'diproses') {
                    $ditinjau++;
                } elseif ($status_lower == 'diterima') {
                    $selesai++;
                } elseif ($status_lower == 'ditolak'){
                    $ditolak++;
                }
            }
        }
    } else {
        $current_error = "Gagal memuat aspirasi. Error SQL: " . $conn->error;
    }
}
$conn->close();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - SuaraSiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../asset/css/dashboard_student.css">
</head>
<body>
    
    <header class="dashboard-header">
        <div class="header-left">
            <a href="../auth/logout.php" class="btn-logout">
                <i class="bi bi-box-arrow-left me-2"></i> Logout
            </a>
            <h4>
                <i class="bi bi-megaphone-fill me-2"></i> Dashboard Siswa
            </h4>
        </div>
        
        <div class="profile-info">
            <div class="profile-text text-end">
                <h6><?= htmlspecialchars($student_name) ?></h6>
                <small><?= htmlspecialchars($student_class) ?></small>
            </div>
            <div class="profile-avatar">
                <?= strtoupper(substr($student_name, 0, 1)) ?>
            </div>
        </div>
    </header>

    <div class="dashboard-content">
        
        <div class="hero-message">
            <h5>🎯 Suaramu Penting!</h5>
            <p>Sampaikan ide, saran, dan aspirasimu untuk membuat sekolah kita menjadi lebih baik. Setiap aspirasi akan ditinjau dan ditindaklanjuti oleh tim sekolah dengan serius.</p>
            
            <button class="btn btn-sampaikan" data-bs-toggle="modal" data-bs-target="#aspirasiModal">
                <i class="bi bi-send-fill me-2"></i> Sampaikan Aspirasi Sekarang
            </button>
        </div>
        
        <?php if ($current_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($current_success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($current_error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($current_error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="status-card card-primary">
            <div class="card-body">
                <div>
                    <small>Total Aspirasi</small>
                    <h4 class="text-primary"><?= $total_aspirasi ?></h4>
                </div>
                <i class="bi bi-chat-text-fill text-primary status-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="status-card card-warning">
            <div class="card-body">
                <div>
                    <small>Pending</small>
                    <h4 class="text-warning"><?= $menunggu ?></h4>
                </div>
                <i class="bi bi-clock-fill text-warning status-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="status-card card-info">
            <div class="card-body">
                <div>
                    <small>Diproses</small>
                    <h4 class="text-info"><?= $ditinjau ?></h4>
                </div>
                <i class="bi bi-eye-fill text-info status-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="status-card card-success">
            <div class="card-body">
                <div>
                    <small>Diterima</small>
                    <h4 class="text-success"><?= $selesai ?></h4>
                </div>
                <i class="bi bi-check-circle-fill text-success status-icon"></i>
            </div>
        </div>
    </div>
</div> 
<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="status-card card-danger">
            <div class="card-body">
                <div>
                    <small>Ditolak</small>
                    <h4 class="text-danger"><?= $ditolak ?></h4>
                </div>
                <i class="bi bi-x-circle-fill text-danger status-icon"></i>
            </div>
        </div>
    </div>
</div>

        <div class="aspirasi-list-container">
            <h5>
                <i class="bi bi-list-ul"></i>
                Aspirasi Saya
            </h5>
            
            
            <p class="text-muted small mt-3">Menampilkan <?= count($aspirations) ?> dari <?= $total_aspirasi ?> aspirasi</p>

            <?php if (empty($aspirations)): ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h6>Belum Ada Aspirasi</h6>
                <p class="text-muted">Mulai sampaikan aspirasi pertamumu dengan klik tombol di atas!</p>
            </div>
            <?php else: ?>
                <?php foreach ($aspirations as $asp): ?>
                <div class="aspirasi-card p-3 mb-3 border rounded">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-bold mb-1 text-primary"><?= htmlspecialchars($asp['title']) ?></h6>
                            <small class="text-muted">
                                <i class="bi bi-calendar-event me-1"></i> <?= date('d M Y', strtotime($asp['created_at'])) ?>
                                | <i class="bi bi-tag me-1"></i> <?= htmlspecialchars($asp['category']) ?>
                            </small>
                        </div>
                        <span class="badge bg-secondary status-tag status-<?= strtolower($asp['status']) ?>"><?= htmlspecialchars($asp['status']) ?></span>
                    </div>
                    
                    <p class="mt-3 mb-2 small text-secondary">
                        <?= htmlspecialchars(substr($asp['content'], 0, 150)) ?>...
                    </p>
                    
                    <a href="detail_aspirasi.php?id=<?=$asp['aspiration_id'] ?>" class="btn btn-sm btn-outline-primary mt-2">Lihat Detail</a>
                    <a href="delete.php?id=<?=$asp['aspiration_id'] ?>" class="btn btn-sm btn-outline-primary mt-2">Hapus</a>
                    <a href="edit.php?id=<?=$asp['aspiration_id'] ?>" class="btn btn-sm btn-outline-primary mt-2">Edit</a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="modal fade" id="aspirasiModal" tabindex="-1" aria-labelledby="aspirasiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="aspirasiModalLabel">
                        <i class="bi bi-send-fill me-2"></i> Sampaikan Aspirasi Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="process_aspirasi.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="student_id" value="<?= $student_id ?>"> 
                        <div class="mb-4">
                            <label for="title" class="form-label">
                                <i class="bi bi-pencil-fill text-primary me-2"></i>Judul Aspirasi
                            </label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Contoh: Penambahan buku di perpustakaan" required maxlength="100">
                            <div class="form-text">Buat judul yang singkat dan jelas (maksimal 100 karakter)</div>
                        </div>

                        <div class="mb-4">
                            <label for="category" class="form-label">
                                <i class="bi bi-tag-fill text-primary me-2"></i>Kategori
                            </label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="" selected disabled>Pilih kategori yang sesuai</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Pilih kategori yang paling sesuai dengan aspirasi Anda</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="content" class="form-label">
                                <i class="bi bi-card-text text-primary me-2"></i>Detail Aspirasi
                            </label>
                            <textarea class="form-control" id="content" name="content" rows="6" placeholder="Jelaskan detail aspirasi Anda di sini. Berikan alasan yang jelas dan solusinya (jika ada)." required></textarea>
                            <div class="form-text">Jelaskan aspirasi Anda secara detail dan lengkap</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </button>
                        <button type="submit" name="submit_aspirasi" class="btn btn-primary">
                            <i class="bi bi-send-fill me-2"></i>Kirim Aspirasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>