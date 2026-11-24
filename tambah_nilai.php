
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

$nama_siswa = mysqli_query($conn, "SELECT id, nis, nama FROM siswa ORDER BY nama ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $siswa_id = (int)($_POST['siswa_id'] ?? 0);
    $mata_pelajaran = trim($_POST['mata_pelajaran'] ?? '');
    $nilai_tugas = (float)($_POST['nilai_tugas'] ?? -1);
    $nilai_uts = (float)($_POST['nilai_uts'] ?? -1);
    $nilai_uas = (float)($_POST['nilai_uas'] ?? -1);

    if (
        $siswa_id <= 0 ||
        $mata_pelajaran === '' ||
        $nilai_tugas < 0 || $nilai_tugas > 100 ||
        $nilai_uts < 0 || $nilai_uts > 100 ||
        $nilai_uas < 0 || $nilai_uas > 100
    ) {
        $error = "Mohon isi semua data dengan benar. Nilai harus antara 0 sampai 100.";
    } else {

        // Periksa apakah siswa benar-benar terdaftar
        $cek = mysqli_prepare($conn, "SELECT id FROM siswa WHERE id = ?");
        mysqli_stmt_bind_param($cek, "i", $siswa_id);
        mysqli_stmt_execute($cek);
        $hasil_cek = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($hasil_cek) === 0) {
            $error = "Siswa tidak ditemukan.";
        } else {

            // Menghitung nilai akhir
            $nilai_akhir = (
                ($nilai_tugas * 0.30) +
                ($nilai_uts * 0.30) +
                ($nilai_uas * 0.40)
            );

            $stmt = mysqli_prepare($conn,
                "INSERT INTO nilai
                (siswa_id, mata_pelajaran, nilai_tugas, nilai_uts, nilai_uas, nilai_akhir)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "isdddd",
                $siswa_id,
                $mata_pelajaran,
                $nilai_tugas,
                $nilai_uts,
                $nilai_uas,
                $nilai_akhir
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: nilai.php");
                exit;
            } else {
                $error = "Gagal menyimpan nilai: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tambah Nilai | SchoolAdmin</title>

<link rel="stylesheet" href="assets/style.css">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
.form-card {
    background: white;
    padding: 28px;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(15, 23, 42, .04);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-group label {
    font-size: 13px;
    font-weight: 700;
    color: #334155;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 13px 14px;
    border: 1px solid #dce3ed;
    border-radius: 9px;
    font-family: Inter, sans-serif;
    font-size: 13px;
    color: #334155;
    outline: none;
    background: #fff;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #3478f6;
    box-shadow: 0 0 0 3px rgba(52, 120, 246, .10);
}

.form-info {
    background: #eff6ff;
    color: #315fce;
    padding: 14px 16px;
    border-radius: 10px;
    font-size: 13px;
    line-height: 1.7;
    margin-bottom: 22px;
}

.form-error {
    background: #fee2e2;
    color: #b91c1c;
    padding: 13px 15px;
    border-radius: 9px;
    font-size: 13px;
    margin-bottom: 20px;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

.btn-save {
    border: none;
    background: #3478f6;
    color: white;
    padding: 12px 22px;
    border-radius: 9px;
    font-weight: 600;
    cursor: pointer;
    font-family: Inter, sans-serif;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    padding: 12px 22px;
    border-radius: 9px;
    background: #eef2f7;
    color: #475569;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
}

@media(max-width: 700px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full {
        grid-column: auto;
    }

    .form-card {
        padding: 20px;
    }
}
</style>
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
        <a href="dashboard.php" class="menu-item">
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

        <a href="nilai.php" class="menu-item active">
            <span>★</span> Nilai Siswa
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
            <h1>Tambah Nilai</h1>
            <p>Masukkan nilai akademik siswa.</p>
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
            <h2>Input Nilai Siswa 📝</h2>
            <p>Tambahkan hasil penilaian akademik siswa.</p>
        </div>

        <div class="welcome-icon">📊</div>
    </section>

    <section class="form-card">

        <div class="section-header">
            <div>
                <h2>Form Tambah Nilai</h2>
                <p>Lengkapi informasi nilai siswa di bawah ini.</p>
            </div>
        </div>

        <div class="form-info">
            <strong>Informasi perhitungan nilai:</strong><br>
            Tugas: 30% &nbsp; | &nbsp;
            UTS: 30% &nbsp; | &nbsp;
            UAS: 40%<br>
            Nilai akhir akan dihitung secara otomatis.
        </div>

        <?php if ($error !== ''): ?>
            <div class="form-error">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($nama_siswa) > 0): ?>

        <form method="POST">

            <div class="form-grid">

                <div class="form-group full">
                    <label>Nama Siswa</label>

                    <select name="siswa_id" required>
                        <option value="">-- Pilih Siswa --</option>

                        <?php while ($siswa = mysqli_fetch_assoc($nama_siswa)): ?>

                            <option
                                value="<?= (int)$siswa['id'] ?>"
                                <?= (isset($_POST['siswa_id']) && (int)$_POST['siswa_id'] === (int)$siswa['id']) ? 'selected' : '' ?>
                            >
                                <?= e($siswa['nama']) ?> - NIS: <?= e($siswa['nis']) ?>
                            </option>

                        <?php endwhile; ?>

                    </select>
                </div>

                <div class="form-group full">
                    <label>Mata Pelajaran</label>

                    <input
                        type="text"
                        name="mata_pelajaran"
                        placeholder="Contoh: Matematika"
                        value="<?= e($_POST['mata_pelajaran'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Nilai Tugas</label>

                    <input
                        type="number"
                        name="nilai_tugas"
                        min="0"
                        max="100"
                        step="0.01"
                        placeholder="0 - 100"
                        value="<?= e($_POST['nilai_tugas'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Nilai UTS</label>

                    <input
                        type="number"
                        name="nilai_uts"
                        min="0"
                        max="100"
                        step="0.01"
                        placeholder="0 - 100"
                        value="<?= e($_POST['nilai_uts'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Nilai UAS</label>

                    <input
                        type="number"
                        name="nilai_uas"
                        min="0"
                        max="100"
                        step="0.01"
                        placeholder="0 - 100"
                        value="<?= e($_POST['nilai_uas'] ?? '') ?>"
                        required
                    >
                </div>

            </div>

            <div class="form-actions">

                <button type="submit" class="btn-save">
                    Simpan Nilai
                </button>

                <a href="nilai.php" class="btn-back">
                    Kembali
                </a>

            </div>

        </form>

        <?php else: ?>

            <div class="form-error">
                Belum ada siswa yang terdaftar. Silakan tambahkan data siswa terlebih dahulu sebelum memasukkan nilai.
            </div>

            <a href="data_siswa.php" class="btn-back">
                Buka Data Siswa
            </a>

        <?php endif; ?>

    </section>

    <footer>
        © <?= date('Y') ?> SchoolAdmin. Sistem Manajemen Sekolah.
    </footer>

</main>

</div>

</body>
</html>
