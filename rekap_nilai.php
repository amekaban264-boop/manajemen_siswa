
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
$cari = trim($_GET['cari'] ?? '');

$sql = "
    SELECT
        s.id,
        s.nis,
        s.nama,
        s.kelas,
        COUNT(n.id) AS jumlah_mapel,
        AVG(n.nilai_akhir) AS rata_rata
    FROM siswa s
    LEFT JOIN nilai n ON s.id = n.siswa_id
    WHERE s.nama LIKE ? OR s.nis LIKE ?
    GROUP BY s.id, s.nis, s.nama, s.kelas
    ORDER BY s.nama ASC
";

$stmt = mysqli_prepare($conn, $sql);
$keyword = "%$cari%";
mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);

$total_siswa = mysqli_num_rows($data);

function predikat($nilai) {
    if ($nilai === null) return '-';
    if ($nilai >= 90) return 'A';
    if ($nilai >= 80) return 'B';
    if ($nilai >= 70) return 'C';
    return 'D';
}

function keterangan($nilai) {
    if ($nilai === null) return 'Belum ada nilai';
    if ($nilai >= 90) return 'Sangat Baik';
    if ($nilai >= 80) return 'Baik';
    if ($nilai >= 70) return 'Cukup';
    return 'Perlu Perbaikan';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rekap Nilai | SchoolAdmin</title>

<link rel="stylesheet" href="assets/style.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
.content-card {
    overflow: hidden;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    white-space: nowrap;
}

thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 15px 13px;
}

tbody td {
    padding: 15px 13px;
    border-bottom: 1px solid #edf0f5;
    font-size: 13px;
    color: #475569;
}

tbody tr:hover {
    background: #f8fbff;
}

.student-name {
    font-weight: 600;
    color: #1e293b;
}

.predikat {
    display: inline-block;
    min-width: 28px;
    text-align: center;
    padding: 6px 9px;
    border-radius: 20px;
    font-weight: 700;
}

.predikat-a, .predikat-b {
    background: #dcfce7;
    color: #15803d;
}

.predikat-c {
    background: #fef3c7;
    color: #b45309;
}

.predikat-d {
    background: #fee2e2;
    color: #b91c1c;
}

.predikat-kosong {
    background: #f1f5f9;
    color: #64748b;
}

.rata-rata {
    font-weight: 700;
    color: #315fce;
}

.table-footer {
    padding-top: 17px;
    color: #64748b;
    font-size: 13px;
}

.rekap-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-print {
    display: inline-block;
    padding: 11px 17px;
    border-radius: 8px;
    background: #16a36a;
    color: white;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
}

@media(max-width:768px) {
    .section-header {
        flex-wrap: wrap;
        gap: 15px;
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

        <a href="nilai.php" class="menu-item">
            <span>★</span> Nilai Siswa
        </a>

        <a href="rekap_nilai.php" class="menu-item active">
            <span>▤</span> Rekap Nilai
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
            <h1>Rekap Nilai</h1>
            <p>Lihat rata-rata hasil belajar seluruh siswa.</p>
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
            <h2>Rekap Nilai Siswa 📊</h2>
            <p>Pantau rata-rata dan predikat akademik siswa.</p>
        </div>

        <div class="welcome-icon">🏆</div>
    </section>

    <section class="content-card">

        <div class="section-header">
            <div>
                <h2>Daftar Rekap Nilai</h2>
                <p>Rata-rata seluruh mata pelajaran setiap siswa.</p>
            </div>

            <div class="rekap-actions">
                <a href="cetak_rekap.php" class="btn-print">
                    🖨 Cetak Rekap
                </a>

                <a href="nilai.php" class="btn-primary">
                    Kembali ke Data Nilai
                </a>
            </div>
        </div>

        <form method="GET" class="search-form">

            <input
                type="text"
                name="cari"
                placeholder="Cari nama atau NIS siswa..."
                value="<?= e($cari) ?>"
            >

            <button type="submit">Cari</button>

            <?php if ($cari !== ''): ?>
                <a href="rekap_nilai.php" class="reset-btn">Reset</a>
            <?php endif; ?>

        </form>

        <div class="table-responsive">

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jumlah Mapel</th>
                        <th>Rata-rata</th>
                        <th>Predikat</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                $no = 1;

                if ($total_siswa > 0):
                    while ($row = mysqli_fetch_assoc($data)):

                        $rata = $row['rata_rata'] !== null
                            ? (float)$row['rata_rata']
                            : null;

                        $huruf = predikat($rata);

                        $warna = [
                            'A' => 'predikat-a',
                            'B' => 'predikat-b',
                            'C' => 'predikat-c',
                            'D' => 'predikat-d',
                            '-' => 'predikat-kosong'
                        ][$huruf];
                ?>

                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= e($row['nis']) ?></td>

                        <td>
                            <div class="student-name">
                                <?= e($row['nama']) ?>
                            </div>
                        </td>

                        <td><?= e($row['kelas']) ?></td>
                        <td><?= (int)$row['jumlah_mapel'] ?></td>

                        <td>
                            <span class="rata-rata">
                                <?= $rata !== null
                                    ? number_format($rata, 2)
                                    : '-' ?>
                            </span>
                        </td>

                        <td>
                            <span class="predikat <?= $warna ?>">
                                <?= $huruf ?>
                            </span>
                        </td>

                        <td><?= keterangan($rata) ?></td>
                    </tr>

                <?php
                    endwhile;
                else:
                ?>

                    <tr>
                        <td colspan="8" class="empty">
                            Data siswa belum ditemukan.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>
            </table>

        </div>

        <div class="table-footer">
            Menampilkan <?= $total_siswa ?> siswa
        </div>

    </section>

    <footer>
        © <?= date('Y') ?> SchoolAdmin. Sistem Manajemen Sekolah.
    </footer>

</main>
</div>

</body>
</html>
