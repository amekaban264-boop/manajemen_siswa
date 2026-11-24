
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

$nama_admin = $_SESSION['nama'] ?? 'Administrator';
$error = '';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    header("Location: data_kelas.php");
    exit;
}

// Ambil data kelas
$stmt = mysqli_prepare($conn, "SELECT * FROM kelas WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$kelas = mysqli_fetch_assoc($hasil);

if (!$kelas) {
    header("Location: data_kelas.php");
    exit;
}

$nama_kelas = $kelas['nama_kelas'];
$tingkat = $kelas['tingkat'];
$jurusan = $kelas['jurusan'];
$wali_kelas = $kelas['wali_kelas'];

// Proses perubahan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_kelas = trim($_POST['nama_kelas'] ?? '');
    $tingkat = trim($_POST['tingkat'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $wali_kelas = trim($_POST['wali_kelas'] ?? '');

    if ($nama_kelas === '' || $tingkat === '' ||
        $jurusan === '' || $wali_kelas === '') {

        $error = 'Semua kolom wajib diisi.';

    } else {

        $stmt = mysqli_prepare($conn,
            "UPDATE kelas
             SET nama_kelas = ?, tingkat = ?, jurusan = ?, wali_kelas = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt, "ssssi",
            $nama_kelas, $tingkat, $jurusan, $wali_kelas, $id
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: data_kelas.php");
            exit;
        } else {
            $error = 'Gagal mengubah data. Nama kelas mungkin sudah digunakan.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas | SchoolAdmin</title>
    <link rel="stylesheet" href="assets/style.css">
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
            <a href="dashboard.php" class="menu-item">▦ Dashboard</a>
            <a href="data_siswa.php" class="menu-item">♙ Data Siswa</a>
            <a href="data_guru.php" class="menu-item">▤ Data Guru</a>
            <a href="data_kelas.php" class="menu-item active">▣ Data Kelas</a>
            <a href="absensi.php" class="menu-item">◷ Absensi</a>
            <a href="nilai.php" class="menu-item">★ Nilai Siswa</a>
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
                <h1>Edit Kelas</h1>
                <p>Ubah informasi kelas sekolah.</p>
            </div>
        </header>

        <section class="content-card">

            <div class="section-header">
                <div>
                    <h2>Form Edit Kelas</h2>
                    <p>Perbarui informasi kelas berikut.</p>
                </div>
            </div>

            <?php if ($error !== ''): ?>
                <p style="color:red; margin-bottom:15px;">
                    <?= e($error) ?>
                </p>
            <?php endif; ?>

            <form method="POST">

                <input type="hidden" name="id" value="<?= $id ?>">

                <div style="margin-bottom:18px;">
                    <label>Nama Kelas</label>
                    <input type="text" name="nama_kelas"
                        value="<?= e($nama_kelas) ?>" required
                        style="width:100%; padding:12px; margin-top:8px; border:1px solid #ddd; border-radius:8px;">
                </div>

                <div style="margin-bottom:18px;">
                    <label>Tingkat</label>
                    <select name="tingkat" required
                        style="width:100%; padding:12px; margin-top:8px; border:1px solid #ddd; border-radius:8px;">
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="X" <?= $tingkat == 'X' ? 'selected' : '' ?>>X</option>
                        <option value="XI" <?= $tingkat == 'XI' ? 'selected' : '' ?>>XI</option>
                        <option value="XII" <?= $tingkat == 'XII' ? 'selected' : '' ?>>XII</option>
                    </select>
                </div>

                <div style="margin-bottom:18px;">
                    <label>Jurusan</label>
                    <input type="text" name="jurusan"
                        value="<?= e($jurusan) ?>" required
                        style="width:100%; padding:12px; margin-top:8px; border:1px solid #ddd; border-radius:8px;">
                </div>

                <div style="margin-bottom:22px;">
                    <label>Wali Kelas</label>
                    <input type="text" name="wali_kelas"
                        value="<?= e($wali_kelas) ?>" required
                        style="width:100%; padding:12px; margin-top:8px; border:1px solid #ddd; border-radius:8px;">
                </div>

                <button type="submit" class="btn-primary">
                    Simpan Perubahan
                </button>

                <a href="data_kelas.php" style="margin-left:10px; color:#555;">
                    Batal
                </a>

            </form>
        </section>

        <footer>
            © <?= date('Y') ?> SchoolAdmin. Sistem Manajemen Sekolah.
        </footer>

    </main>
</div>
</body>
</html>
