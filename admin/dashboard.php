<?php
session_start();
include "../middleware/auth_check.php"; 

include "../config/database.php"; 

checkRole('admin'); 
$admin_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin Sekolah';
$admin_id = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : ''; 

$current_success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$current_error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['success']);
unset($_SESSION['error']);
$aspirations = [];
$total_aspirasi = 0;
$menunggu = 0;
$ditinjau = 0;
$selesai = 0;
$ditolak = 0;
$total_aspirasi = 0;
$id= 1;
$sql_aspirations = "SELECT 
                        a.aspiration_id, a.title, a.content, a.category, a.status, a.created_at, 
                        s.student_id, s.nis, s.class  
                    FROM 
                        aspirations a
                    JOIN 
                        students s ON a.student_id = s.student_id
                    ORDER BY 
                        a.created_at DESC";

$result = $conn->query($sql_aspirations);

if ($result && $result->num_rows > 0) {
    $total_aspirasi = $result->num_rows;
    while($row = $result->fetch_assoc()) {
                $aspirations[] = $row;
                $status_lower = strtolower($row['status']);
                if ($status_lower == 'pending' || $status_lower == 'menunggu') {
                    $menunggu++;
                } elseif ($status_lower == 'diterima' || $status_lower == 'diproses') {
                    $ditinjau++;
                } elseif ($status_lower == 'selesai') {
                    $selesai++;
                } elseif ($status_lower == 'ditolak'){
                    $ditolak++;
                }
            }
}
$conn->close();

?>

<!DOCTYPE html>
<html lang="id">
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SuaraSiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../asset/css/dashboard_admin.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h4>SuaraSiswa</h4>
                <small>Admin Dashboard</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link logout-link" href="../auth/logout.php">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Dashboard Admin</h1>

            <!-- Alert Messages -->
            <?php if ($current_success): ?>
                <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i><?= $current_success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if ($current_error): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i><?= $current_error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Status Cards -->
            <div class="row g-4 my-5">
                <div class="col-lg-3 col-md-6">
                    <div class="status-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5>Total Aspirasi</h5>
                                <h2 class="text-primary"><?= $total_aspirasi ?></h2>
                            </div>
                            <div class="card-icon-container card-icon-total">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="status-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5>Menunggu Review</h5>
                                <h2 class="text-warning"><?= $menunggu ?></h2>
                            </div>
                            <div class="card-icon-container card-icon-review">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="status-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5>Disetujui</h5>
                                <h2 class="text-success"><?= $ditinjau ?></h2>
                            </div>
                            <div class="card-icon-container card-icon-approved">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="status-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5>Selesai</h5>
                                <h2 class="text-success"><?= $selesai ?></h2>
                            </div>
                            <div class="card-icon-container card-icon-approved">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="status-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5>Ditolak</h5>
                                <h2 class="text-danger"><?= $ditolak ?></h2>
                            </div>
                            <div class="card-icon-container card-icon-rejected">
                                <i class="bi bi-x-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Aspirasi -->
            <div class="aspirasi-table">
    <h4>
        <i class="bi bi-list-ul"></i>
        Daftar Aspirasi (<?= $total_aspirasi ?> Total)
    </h4>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>No  </th>
                    <th>NIS / Kelas</th>
                    <th>Judul Aspirasi</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Tanggal Masuk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($aspirations)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada aspirasi yang masuk.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($aspirations as $asp): 
                        // Tentukan class status berdasarkan nilai status dari DB
                        $status_class = 'status-' . strtolower($asp['status']); 
                    ?>
                        <tr>
                            <td><?= $id++ ?></td>
                            <td>
                                <strong><?= htmlspecialchars($asp['nis']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($asp['class']) ?></small>
                            </td>
                            <td><?= htmlspecialchars(substr($asp['title'], 0, 50)) ?>...</td>
                            <td><?= htmlspecialchars($asp['category']) ?></td>
                            <td>
                                <span class="status-tag <?= $status_class ?>">
                                    <?= htmlspecialchars($asp['status']) ?>
                                </span>
                            </td>
                            <td><?= date('d M Y', strtotime($asp['created_at'])) ?></td>
                            <td>
                                <a href="detail_aspirasi.php?id=<?= $asp['aspiration_id'] ?>" class="btn btn-outline-info btn-sm">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <small class="text-muted mt-3 d-block">Menampilkan <?= count($aspirations) ?> dari <?= $total_aspirasi ?> aspirasi</small>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>