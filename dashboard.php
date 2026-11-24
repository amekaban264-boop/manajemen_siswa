
<?php
session_start();
require_once 'config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function hitungData($conn, $tabel) {
    $hasil = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `$tabel`");
    if (!$hasil) {
        return 0;
    }
    $row = mysqli_fetch_assoc($hasil);
    return (int)$row['total'];
}

$nama_admin = $_SESSION['nama'] ?? 'Administrator';

$total_siswa = hitungData($conn, 'siswa');
$total_guru = hitungData($conn, 'guru');
$total_kelas = hitungData($conn, 'kelas');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SchoolAdmin</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
<div class="dashboard">

    <aside class="sidebar">

        <div class="brand">
            <div class="brand-icon">S</div>
            <div>
                <h2>School<span>Admin</span></h2>
                <small>Management System</small>
            </div>
        </div>

        <div class="menu-label">MENU UTAMA</div>

        <nav>
            <a href="dashboard.php" class="menu-item active">
                <span>▦</span> Dashboard
            </a>

            <a href="data_siswa.php" class="menu-item">
                <span>♙</span> Data Siswa
            </a>

            <a href="data_guru.php" class="menu-item">
                <span>▤</span> Data Guru
            </a>

            <a href="data_kelas.php" class="menu-item">
                <span>▣</span> Data Kelas
            </a>

            <a href="absensi.php" class="menu-item">
                <span>◷</span> Absensi
            </a>

            <a href="rekap_absensi.php" class="menu-item">
                <span>📊</span> Rekap Absensi
            </a>

            <a href="nilai.php" class="menu-item">
                <span>★</span> Nilai Siswa
            </a>

            <a href="rekap_nilai.php" class="menu-item">
                <span>📋</span> Rekap Nilai
            </a>

            <a href="profil_admin.php" class="menu-item">
    <span>👤</span> Profil Admin
</a>

<a href="ganti_password.php" class="menu-item">
    <span>🔒</span> Ganti Password
</a>
        </nav>

        <div class="sidebar-bottom">
            <div class="admin-mini">
                <div class="avatar">A</div>
                <div>
                    <strong><?= e($nama_admin) ?></strong>
                    <small>Administrator</small>
                </div>
            </div>

            <a href="logout.php" class="logout">↪ Keluar</a>
        </div>

    </aside>

    <main class="main-content">

        <header class="topbar">
            <div>
                <h1>Dashboard</h1>
                <p>Ringkasan sistem manajemen sekolah.</p>
            </div>

            <div class="admin-profile">
                <div class="avatar">A</div>
                <div>
                    <strong><?= e($nama_admin) ?></strong>
                    <small>Administrator</small>
                </div>
            </div>
        </header>

        <section class="welcome">
            <div>
                <span class="welcome-tag">SCHOOL MANAGEMENT SYSTEM</span>
                <h2>Selamat Datang, <?= e($nama_admin) ?>! 👋</h2>
                <p>Kelola informasi sekolah dengan mudah melalui SchoolAdmin.</p>
            </div>
            <div class="welcome-icon">🏫</div>
        </section>

        <section class="stats-grid">

            <div class="content-card">
                <h3>👨‍🎓 Total Siswa</h3>
                <h2><?= $total_siswa ?></h2>
                <a href="data_siswa.php">Lihat Data Siswa →</a>
            </div>

            <div class="content-card">
                <h3>👨‍🏫 Total Guru</h3>
                <h2><?= $total_guru ?></h2>
                <a href="data_guru.php">Lihat Data Guru →</a>
            </div>

            <div class="content-card">
                <h3>🏫 Total Kelas</h3>
                <h2><?= $total_kelas ?></h2>
                <a href="data_kelas.php">Lihat Data Kelas →</a>
            </div>

        </section>

        <section class="content-card">
            <div class="section-header">
                <div>
                    <h2>Menu Pengelolaan Sekolah</h2>
                    <p>Pilih halaman yang ingin kamu kelola.</p>
                </div>
            </div>

            <p>
                Gunakan menu di sebelah kiri untuk mengelola data siswa,
                guru, kelas, absensi, rekap absensi, nilai, dan rekap nilai siswa.
            </p>
        </section>

        <footer>
            © <?= date('Y') ?> SchoolAdmin. Sistem Manajemen Sekolah.
        </footer>

    </main>
</div>
</body>
</html>
