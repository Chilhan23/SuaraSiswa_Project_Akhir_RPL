<?php
// Selalu mulai session di awal file PHP yang menggunakan session
session_start(); 

// Ambil pesan dari session untuk ditampilkan di Alert Box
$current_success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$current_error = isset($_SESSION['error']) ? $_SESSION['error'] : null;

// Hapus pesan session setelah diambil (agar tidak muncul lagi setelah refresh)
unset($_SESSION['success']);
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - SuaraSiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
     <link rel="stylesheet" href="../asset/css/register.css">
    <style>
       
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="register-card">
                    <div class="register-header">
                       <img src="../asset/Logo_Telkom.png" alt="">
                        <h2 class="mt-3 mb-0">Daftar SuaraSiswa</h2>
                        <p class="mb-0">Buat akun baru untuk siswa</p>
                    </div>
                    <div class="register-body">
                        <?php if ($current_success): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> <?= $current_success; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($current_error): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle"></i> <?= $current_error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="register_process.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-card-text"></i> NIS *
                                    </label>
                                    <input type="text" name="nis" class="form-control" placeholder="Nomor Induk Siswa" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-people"></i> Kelas *
                                    </label>
                                    <select name="class" class="form-select" required>
                                        <option value="">Pilih Kelas</option>
                                        <option value="X TJKT 1">X TJKT 1</option>
                                        <option value="X TJKT 2">X TJKT 2</option>
                                        <option value="X TJKT 3">X TJKT 3</option>
                                        <option value="X PPLG 1">X PPLG 1</option>
                                        <option value="X PPLG 2">X PPLG 2</option>
                                        <option value="X PPLG 3">X PPLG 3</option>
                                        <option value="X BP">X BP</option>
                                        <option value="XI TJA 1">XI TJA 1</option>
                                        <option value="XI TJA 2">XI TJA 2</option>
                                        <option value="XI TKJ 1">XI TKJ 1</option>
                                        <option value="XI TKJ 2">XI TKJ 2</option>
                                        <option value="XI RPL 1">XI RPL 1</option>
                                        <option value="XI RPL 2">XI RPL 2</option>
                                        <option value="XI RPL 3">XI RPL 3</option>
                                        <option value="XI BP 1">XI BP 1</option>
                                        <option value="XI BP 2">XI BP 2</option>
                                        </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-person"></i> Nama Lengkap *
                                </label>
                                <input type="text" name="name" class="form-control" placeholder="Nama lengkap sesuai KTS " required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-envelope"></i> Email *
                                </label>
                                <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-phone"></i> No. Telepon
                                </label>
                                <input type="tel" name="phone" class="form-control" placeholder="08xxxxxxxxxx">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-lock"></i> Password *
                                    </label>
                                    <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required minlength="6">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-lock-fill"></i> Konfirmasi Password *
                                    </label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Ketik ulang password" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-register w-100 mt-3">
                                <i class="bi bi-person-plus"></i> Daftar Sekarang
                            </button>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="text-muted">Sudah punya akun?</p>
                            <a href="login.php" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="../index.php" class="text-decoration-none text-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>