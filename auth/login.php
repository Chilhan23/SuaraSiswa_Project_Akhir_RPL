<?php

session_start(); 

$current_success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$current_error = isset($_SESSION['error']) ? $_SESSION['error'] : null;

unset($_SESSION['success']);
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SuaraSiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../asset/css/login.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-card">
                    <div class="login-header">
                        <img src="../asset/Logo_Telkom.png" alt="">
                        <h2 class="mt-3 mb-0">Login SuaraSiswa</h2>
                        <p class="mb-0">Masuk ke akun Anda</p>
                    </div>
                    <div class="login-body">
                        <!-- Menampilkan pesan error/sukses dari PHP -->
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

                        <form action="login_process.php" method="POST">
                            <div class="mb-3">
                               <label class="form-label" id="identifierLabel">
                                     <i class="bi bi-person"></i> NIS / Username
                                </label>
                                <input type="text" name="identifier" id="identifierInput" class="form-control" placeholder="Masukkan NIS atau Username" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-lock"></i> Password
                                </label>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-login w-100">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="text-muted">Belum punya akun?</p>
                            <a href="register.php" class="btn btn-outline-primary">
                                <i class="bi bi-person-plus"></i> Daftar Sekarang
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
