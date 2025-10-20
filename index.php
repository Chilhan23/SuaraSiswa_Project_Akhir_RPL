<?php
session_start();
$is_logged_in = isset($_SESSION['user_type']) && isset($_SESSION['user_id']);
$user_type = $is_logged_in ? $_SESSION['user_type'] : null;
if ($is_logged_in) {
    $dashboard_link = ($user_type == 'admin') ? 'admin/dashboard.php' : 'student/dashboard.php';
}


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suara Siswa</title>
    <link rel="stylesheet" href="asset/style.css"> 
</head>
<body class="fade-out">

    <header class="header">
        <div class="logo-container">
            <img src="asset/Logo_Telkom.png" alt="Logo SMK N 5 Telkom Banda Aceh" class="header-logo">
            <div style="font-size: 1.5em; font-weight: bold; color: #007bff;">SuaraSiswa</div>
        </div>
        
        <nav class="nav-links">
            <a href="#">Beranda</a>
            <a href="#about">Tentang</a> 
            <a href="#aspirasi">Suara Siswa</a> 
        </nav>
        <div>
            <?php if ($is_logged_in): ?>
                <a href="<?= $dashboard_link ?>" class="btn-secondary">Dashboard</a>
                <a href="auth/logout.php" class="btn-primary">Logout</a>
            <?php else: ?>
                <a href="auth/login.php" class="btn-secondary">Login</a>
                <a href="auth/register.php" class="btn-primary">Register</a>
            <?php endif; ?>
        </div>
    </header>
   
    <section class="hero-section">
        <div class="hero-content">
            <h1 style="font-size: 3em; margin: 20px 0 10px 0;">Suarakan Suaramu untuk Sekolah Lebih Baik</h1>
            <p style="font-size: 1.1em; color: #555;">
                Platform digital terdepan untuk menyampaikan ide, saran, dan keluhan. Bersama kita wujudkan lingkungan sekolah yang lebih baik dan adaptif teknologi.
            </p>
            
            <div style="margin-top: 30px;">
                <?php if ($is_logged_in): ?>
                <a href="<?= $dashboard_link ?>" class="btn-primary" style="padding: 12px 25px; font-size: 1.1em;"><i class="bi bi-send-fill"></i> Kirim Aspirasi</a>
                <?php else: ?>
               <a href="auth/login.php" class="btn-primary" style="padding: 12px 25px; font-size: 1.1em;"><i class="bi bi-send-fill"></i> Kirim Aspirasi</a>
                <?php endif; ?>
                
                <a href="#aspirasi" class="btn-light" style="padding: 12px 25px; font-size: 1.1em;">Lihat Aspirasi</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="asset/kitty.png" alt="Siswa sedang menulis aspirasi">
        </div>
    </section>

   
    <section class="features container">
        <h2 style="text-align: center; margin-bottom: 5px; font-size: 2em; color: blue;">SuaraSiswa SMK 5 TELKOM BANDA ACEH</h2>
        <h4 style="text-align: center; margin-bottom: 25px; font-size: 1.5em; color: #555;">Kenapa Harus SuaraSiswa?</h4>
        <div class="grid-4">
            <div class="grid-item">
                <h3>Mudah Digunakan</h3>
                <p>Interface yang simpel dan user-friendly untuk semua siswa</p>
            </div>
            <div class="grid-item">
                <h3>Aman & Terpercaya</h3>
                <p>Data aspirasi kamu dijamin keamanannya</p>
            </div>
            <div class="grid-item">
                <h3>Transparan</h3>
                <p>Pantau status dan perkembangan aspirasi kamu</p>
            </div>
            <div class="grid-item">
                <h3>Kolaboratif</h3>
                <p>Bersama-sama mewujudkan sekolah yang lebih baik</p>
            </div>
        </div>
    </section>

  
    <section id="about" class="about-section">
        <div class="container">
            <h2>Tentang SMK NEGERI 5 TELKOM BANDA ACEH</h2>
            <p>
                SMK NEGERI 5 TELKOM ADALAH SEKOLAH BERBASIS IT yang terletak di Banda Aceh. Kami hadir sebagai kawah candradimuka teknologi,
                mencetak generasi muda yang tidak hanya menguasai teori,
                tetapi juga ahli dalam praktik digital terkini.
                Kami berfokus pada kurikulum inovatif yang selaras dengan kebutuhan industri,
                memastikan setiap lulusan siap menjadi pelopor transformasi digital di masa depan.
                Di sinilah talenta IT terbaik dilahirkan!
            </p>
        </div>
    </section>


     <section id="aspirasi" class="aspirations-list container">
        <h1 style="text-align: center; margin-bottom: 10px;">Aspirasi Siswa</h1>
        <p style="text-align: center; margin-bottom: 30px; color: #666;">Lihat  contoh berbagai aspirasi yang telah disampaikan oleh siswa-siswi</p>

        <div style="text-align: center; margin-bottom: 30px;">
            <input type="text" placeholder="Cari aspirasi..." style="width: 50%; padding: 10px 15px; border: 1px solid #ccc; border-radius: 20px;">
        </div>
        <div class="aspirations-card-grid">
            <div class="card" style="border-left-color: #1890ff;">
                <div>
                    <span class="tag tag-fasilitas">Fasilitas</span>
                    <span class="status-tag status-ditinjau">Ditinjau</span>
                </div>
                <h4 style="margin-top: 10px;">Penambahan AC di Ruang Kelas</h4>
                <p style="font-size: 0.9em; color: #555;">Ruang kelas di lantai 2 sangat panas saat siang hari. Akan lebih baik jika ditambahkan AC atau minimal kipas...</p>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 10px 0;">
                <p style="font-size: 0.8em; color: #888;"><span style="margin-right: 15px;">👤 Anonim</span> <span>XI RPL 3    </span></p>
                <p style="font-size: 0.8em; color: #888;">🗓 15 Okt 2025</p>
            </div>

            <div class="card" style="border-left-color: #52c41a;">
                <div>
                    <span class="tag tag-kantin">Kantin</span>
                    <span class="status-tag status-selesai">Selesai</span>
                </div>
                <h4 style="margin-top: 10px;">Variasi Menu Kantin</h4>
                <p style="font-size: 0.9em; color: #555;">Mohon ditambahkan variasi menu di kantin, terutama untuk makanan sehat seperti salad dan jus buah.</p>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 10px 0;">
                <p style="font-size: 0.8em; color: #888;"><span style="margin-right: 15px;">👤 Anonim</span> <span>XI RPL 1</span></p>
                <p style="font-size: 0.8em; color: #888;">🗓 14 Okt 2025</p>
            </div>

            <div class="card" style="border-left-color: #faad14;">
                <div>
                    <span class="tag tag-ekskul">Ekstrakurikuler</span>
                    <span class="status-tag status-menunggu">Menunggu</span>
                </div>
                <h4 style="margin-top: 10px;">Penambahan Eskul</h4>
                <p style="font-size: 0.9em; color: #555;">Mohon untuk eskul ditambahkan beberap eskul lagi yg menarik</p>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 10px 0;">
                <p style="font-size: 0.8em; color: #888;"><span style="margin-right: 15px;">👤 Anonim</span> <span>XI RPL3 3</span></p>
                <p style="font-size: 0.8em; color: #888;">🗓 13 Okt 2025</p>
            </div>

        </div>
    </section>

     <section class="how-it-works container">
        <div class="how-it-works-content">
            <div class="steps">
                <h2>Cara Kerja Platform</h2>
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div>
                        <strong>Kirim Aspirasi</strong>
                        <p>Isi formulir dengan kategori yang sesuai</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div>
                        <strong>Ditinjau Tim</strong>
                        <p>Aspirasi akan ditinjau oleh tim terkait</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div>
                        <strong>Tindak Lanjut</strong>
                        <p>Aspirasi yang feasible akan ditindaklanjuti</p>
                    </div>
                </div>
            </div>
            <div class="stats">
                <h3>Statistik Aspirasi</h3>
                <div class="stat-item">
                    <span>Total Aspirasi</span>
                    <span class="stat-value" style="color: #007bff;">30</span>
                </div>
                <div class="stat-item">
                    <span>Dalam Proses</span>
                    <span class="stat-value" style="color: #ffc107;">12</span>
                </div>
                <div class="stat-item">
                    <span>Selesai</span>
                    <span class="stat-value" style="color: #28a745;">18</span>
                </div>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
                <div class="stat-item">
                    <span>Siswa Aktif</span>
                    <span class="stat-value" style="color: #6f42c1;">500+</span>
                </div>
            </div>
        </div>
    </section>

    <?php include "includes/footer.php"  ?>
 
   <script>

document.addEventListener('DOMContentLoaded', (event) => {
    document.body.classList.remove('fade-out');
    document.body.classList.add('fade-in');
});

function revealOnScroll() {
    const sections = document.querySelectorAll('.features, .about-section, .aspirations-list, .how-it-works');
    
    sections.forEach(section => {
        const sectionTop = section.getBoundingClientRect().top;
        const triggerPoint = window.innerHeight * 0.8;
        
        if (sectionTop < triggerPoint) {
            section.classList.add('show');
        }
    });
}

// Jalankan saat scroll
window.addEventListener('scroll', revealOnScroll);

// Jalankan saat halaman load
window.addEventListener('load', revealOnScroll);
</script>
</body>
</html>
