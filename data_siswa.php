
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
    if (!$hasil) return 0;
    $row = mysqli_fetch_assoc($hasil);
    return (int)$row['total'];
}

$nama_admin = $_SESSION['nama'] ?? 'Administrator';
$keyword = trim($_GET['cari'] ?? '');

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM siswa
     WHERE nama LIKE ? OR nis LIKE ?
     ORDER BY id DESC"
);

$search = "%$keyword%";
mysqli_stmt_bind_param($stmt, "ss", $search, $search);
mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);

$total_siswa = mysqli_num_rows($data);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Siswa | SchoolAdmin</title>
<link rel="stylesheet" href="assets/style.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
.main-content { font-family: 'Inter', sans-serif; }
.content-card { border-radius: 14px; }
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}
.section-header p { color: #8490a3; font-size: 13px; }

.header-actions {
    display: flex;
    gap: 9px;
    flex-wrap: wrap;
}

.print-btn {
    display: inline-block;
    padding: 12px 17px;
    border-radius: 8px;
    background: #0f9f6e;
    color: white;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
}

.search-form {
    display: flex;
    gap: 10px;
    margin-bottom: 22px;
    max-width: 550px;
}
.search-form input {
    flex: 1;
    min-width: 0;
    padding: 12px 15px;
    border: 1px solid #e1e5ec;
    border-radius: 8px;
    outline: none;
}
.search-form button {
    background: #3478f6;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0 20px;
    cursor: pointer;
    font-weight: 600;
}
.reset-btn {
    display: flex;
    align-items: center;
    padding: 0 15px;
    border-radius: 8px;
    background: #eef1f5;
    color: #555;
    text-decoration: none;
    font-size: 13px;
}

.table-responsive { overflow-x: auto; }
.table-responsive table {
    width: 100%;
    border-collapse: collapse;
    min-width: 850px;
}
.table-responsive th {
    background: #f7f9fc;
    color: #68758a;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 15px 12px;
    text-align: left;
}
.table-responsive td {
    padding: 15px 12px;
    font-size: 13px;
    color: #465166;
    border-bottom: 1px solid #edf0f5;
}
.table-responsive tbody tr:hover { background: #fafcff; }

.student-name {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: #293449;
}
.student-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e9f0ff;
    color: #3478f6;
    font-weight: 700;
    flex-shrink: 0;
}
.class-badge {
    background: #edf3ff;
    color: #3478f6;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}
.action-buttons {
    display: flex;
    align-items: center;
    gap: 7px;
}
.action-buttons form { margin: 0; }
.edit-btn, .delete-btn {
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
}
.edit-btn { background: #eaf1ff; color: #3478f6; }
.delete-btn { background: #fff0f0; color: #dc4444; }
.table-footer {
    padding-top: 18px;
    color: #8490a3;
    font-size: 12px;
}
.empty {
    text-align: center;
    padding: 35px !important;
    color: #929daf !important;
}
@media(max-width:600px) {
    .section-header { align-items: flex-start; flex-direction: column; }
    .search-form { flex-wrap: wrap; }
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
        <a href="dashboard.php" class="menu-item"><span>▦</span> Dashboard</a>
        <a href="data_siswa.php" class="menu-item active"><span>♙</span> Data Siswa</a>
        <a href="data_guru.php" class="menu-item"><span>▤</span> Data Guru</a>
        <a href="data_kelas.php" class="menu-item"><span>▣</span> Data Kelas</a>
        <a href="absensi.php" class="menu-item"><span>◷</span> Absensi</a>
        <a href="nilai.php" class="menu-item"><span>★</span> Nilai Siswa</a>
        <a href="rekap_absensi.php" class="menu-item"><span>📊</span> Rekap Absensi</a>
        <a href="rekap_nilai.php" class="menu-item"><span>📋</span> Rekap Nilai</a>
        <a href="profil_admin.php" class="menu-item"><span>👤</span> Profil Admin</a>
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
            <h1>Data Siswa</h1>
            <p>Kelola seluruh data siswa sekolah.</p>
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
            <h2>Data Siswa 🎓</h2>
            <p>Kelola informasi dan data seluruh siswa.</p>
        </div>
        <div class="welcome-icon">👨‍🎓</div>
    </section>

    <section class="content-card">

        <div class="section-header">
            <div>
                <h2>Daftar Siswa</h2>
                <p>Seluruh siswa yang terdaftar di database.</p>
            </div>

            <div class="header-actions">
                <a href="cetak_siswa.php?cari=<?= urlencode($keyword) ?>" target="_blank" class="print-btn">
                    🖨 Cetak Data
                </a>
                <a href="tambah.php" class="btn-primary">+ Tambah Siswa</a>
            </div>
        </div>

        <form method="GET" class="search-form">
            <input type="text" name="cari"
                placeholder="Cari nama atau NIS siswa..."
                value="<?= e($keyword) ?>">
            <button type="submit">Cari</button>

            <?php if ($keyword !== ''): ?>
                <a href="data_siswa.php" class="reset-btn">Reset</a>
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
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                $no = 1;
                if ($total_siswa > 0):
                    while ($row = mysqli_fetch_assoc($data)):
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= e($row['nis']) ?></strong></td>
                        <td>
                            <div class="student-name">
                                <div class="student-avatar">
                                    <?= e(strtoupper(substr($row['nama'], 0, 1))) ?>
                                </div>
                                <?= e($row['nama']) ?>
                            </div>
                        </td>
                        <td><span class="class-badge"><?= e($row['kelas']) ?></span></td>
                        <td><?= e($row['jenis_kelamin']) ?></td>
                        <td><?= e($row['alamat']) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="edit.php?id=<?= (int)$row['id'] ?>" class="edit-btn">Edit</a>

                                <form method="POST" action="hapus.php"
                                    onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                    <button type="submit" class="delete-btn">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="7" class="empty">Data siswa belum ditemukan.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            Menampilkan <?= $total_siswa ?> data siswa
        </div>

    </section>

    <footer>
        © <?= date('Y') ?> SchoolAdmin. Sistem Manajemen Sekolah.
    </footer>

</main>
</div>
</body>
</html>
