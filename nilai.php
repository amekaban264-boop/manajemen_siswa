
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
$cari = $_GET['cari'] ?? '';

$sql = "SELECT nilai.*, siswa.nama, siswa.nis
        FROM nilai
        INNER JOIN siswa ON nilai.siswa_id = siswa.id
        WHERE siswa.nama LIKE ?
        OR siswa.nis LIKE ?
        OR nilai.mata_pelajaran LIKE ?
        ORDER BY nilai.id DESC";

$stmt = mysqli_prepare($conn, $sql);

$keyword = "%$cari%";
mysqli_stmt_bind_param($stmt, "sss", $keyword, $keyword, $keyword);
mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);

$total_nilai = mysqli_num_rows($data);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Nilai | SchoolAdmin</title>
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

.nilai-akhir {
    display: inline-block;
    padding: 6px 11px;
    border-radius: 20px;
    background: #e8efff;
    color: #315fce;
    font-weight: 700;
}

.nilai-baik {
    background: #dcfce7;
    color: #15803d;
}

.nilai-kurang {
    background: #fee2e2;
    color: #b91c1c;
}

.student-name {
    font-weight: 600;
    color: #1e293b;
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
            <h1>Nilai Siswa</h1>
            <p>Kelola nilai akademik seluruh siswa.</p>
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
            <h2>Nilai Siswa 📊</h2>
            <p>Pantau hasil belajar dan nilai akademik siswa.</p>
        </div>

        <div class="welcome-icon">📝</div>
    </section>

    <section class="content-card">

        <div class="section-header">
            <div>
                <h2>Daftar Nilai Siswa</h2>
                <p>Seluruh nilai siswa yang telah dimasukkan.</p>
            </div>

            <a href="tambah_nilai.php" class="btn-primary">
                + Tambah Nilai
            </a>
        </div>

        <form method="GET" class="search-form">

            <input
                type="text"
                name="cari"
                placeholder="Cari nama, NIS, atau mata pelajaran..."
                value="<?= e($cari) ?>"
            >

            <button type="submit">Cari</button>

            <?php if ($cari != ''): ?>
                <a href="nilai.php" class="reset-btn">Reset</a>
            <?php endif; ?>

        </form>

        <div class="table-responsive">

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Mata Pelajaran</th>
                        <th>Tugas</th>
                        <th>UTS</th>
                        <th>UAS</th>
                        <th>Nilai Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                $no = 1;

                if ($total_nilai > 0):
                    while ($row = mysqli_fetch_assoc($data)):
                        $akhir = (float)$row['nilai_akhir'];

                        $kelas_nilai = $akhir >= 75
                            ? 'nilai-baik'
                            : 'nilai-kurang';
                ?>

                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= e($row['nis']) ?></td>

                        <td>
                            <div class="student-name">
                                <?= e($row['nama']) ?>
                            </div>
                        </td>

                        <td>
                            <span class="class-badge">
                                <?= e($row['mata_pelajaran']) ?>
                            </span>
                        </td>

                        <td><?= e($row['nilai_tugas']) ?></td>
                        <td><?= e($row['nilai_uts']) ?></td>
                        <td><?= e($row['nilai_uas']) ?></td>

                        <td>
                            <span class="nilai-akhir <?= $kelas_nilai ?>">
                                <?= number_format($akhir, 2) ?>
                            </span>
                        </td>

                        <td>
                            <div class="action-buttons">

                                <a href="edit_nilai.php?id=<?= (int)$row['id'] ?>"
                                   class="edit-btn">Edit</a>

                                <form method="POST"
                                      action="hapus_nilai.php"
                                      onsubmit="return confirm('Yakin ingin menghapus nilai ini?')">

                                    <input type="hidden"
                                           name="id"
                                           value="<?= (int)$row['id'] ?>">

                                    <button type="submit" class="delete-btn">
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                <?php
                    endwhile;
                else:
                ?>

                    <tr>
                        <td colspan="9" class="empty">
                            Belum ada data nilai siswa.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>
            </table>

        </div>

        <div class="table-footer">
            Menampilkan <?= $total_nilai ?> data nilai
        </div>

    </section>

    <footer>
        © <?= date('Y') ?> SchoolAdmin. Sistem Manajemen Sekolah.
    </footer>

</main>
</div>

</body>
</html>
