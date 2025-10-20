# 📢 SuaraSiswa: Platform Aspirasi Siswa (Proyek Akhir RPL)

Aplikasi ini adalah platform berbasis *web* yang dirancang untuk memfasilitasi komunikasi yang transparan dan efisien antara siswa dan pihak administrasi/guru di SMK Negeri 5 Telkom Banda Aceh. Siswa dapat dengan mudah menyampaikan saran, keluhan, atau ide (aspirasi) yang kemudian dapat ditindaklanjuti dan dipantau oleh admin sekolah.

Proyek ini dibuat sebagai syarat pemenuhan **Tugas Akhir Rekayasa Perangkat Lunak (RPL)**.

## ✨ Fitur Utama (Core Features)

Aplikasi ini dibagi menjadi dua *role* pengguna utama: Siswa dan Admin.

### 🧑‍💻 Fitur untuk Siswa

* **Pendaftaran & Login Siswa (`auth/register.php`, `auth/login.php`):** Siswa dapat mendaftar menggunakan NIS dan detail kelas untuk mendapatkan akses ke dashboard.
* **Dashboard Siswa (`student/dashboard.php`):** Menampilkan ringkasan statistik aspirasi yang diajukan (Total, Pending, Diproses, Diterima, Ditolak).
* **Pengajuan Aspirasi Baru (`student/process_aspirasi.php`):** Formulir modal yang mudah digunakan untuk mengirim aspirasi baru dengan pilihan Kategori (Fasilitas, Akademik, Kebersihan, dll.).
* **Pelacakan Detail & Status (`student/detail_aspirasi.php`):** Siswa dapat melihat detail penuh aspirasi yang diajukan, termasuk tanggapan resmi dari Admin.
* **Edit Aspirasi (Khusus Status Pending) (`student/edit.php`, `student/process_edit.php`):** Siswa hanya diizinkan untuk mengubah Judul, Konten, dan Kategori aspirasi selama statusnya masih **Pending**.
* **Hapus Aspirasi (`student/delete.php`):** Fitur untuk menghapus aspirasi yang sudah diajukan.

### 👨‍💼 Fitur untuk Admin

* **Login Admin (`auth/login_process.php`):** Akses masuk khusus menggunakan Username Admin/Guru.
* **Dashboard Admin (`admin/dashboard.php`):** Menampilkan ringkasan total aspirasi yang masuk, jumlah yang Menunggu Review, Disetujui, Selesai, dan Ditolak.
* **Manajemen Aspirasi (`admin/dashboard.php`):** Tampilan tabel daftar semua aspirasi yang masuk, mencakup NIS/Kelas Siswa, Judul, Kategori, Status, dan Tanggal Masuk.
* **Tindak Lanjut & Respon (`admin/detail_aspirasi.php`, `admin/process_admin_update.php`):**
    * Admin dapat melihat detail aspirasi.
    * Admin dapat mengubah status aspirasi menjadi: Pending, Diterima, Diproses, Selesai, atau Ditolak.
    * Admin wajib memberikan Tanggapan Resmi yang akan dilihat oleh siswa.

## 🛠️ Teknologi yang Digunakan

* **Backend:** PHP Native
* **Database:** MySQLi (terhubung melalui `config/database.php`)
* **Frontend:** HTML, CSS
* **Framework CSS:** Bootstrap 5 (digunakan secara ekstensif untuk tampilan UI)
* **Keamanan:** Implementasi *Hashing Password* untuk login/registrasi dan *Role-Based Access Control* (`middleware/auth_check.php`)


**Penting:** Dalam Projek Ini Sangat Rentan Dengan *SQL Injection*,Cuman Karna Tidak Saya Publikasikan Jadi Menurut Saya Tidak Apa Apa .



Rayhan 
Role :  Project Manager & Database Designer,Frontend Developer (UI),Backend Developer (CRUD LOGIC),Backend Developer (Authentication)
