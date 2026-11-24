
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

$stmt = mysqli_prepare($conn,
    "SELECT * FROM guru
     WHERE nip LIKE ? OR nama LIKE ?
     ORDER BY id DESC"
);

$keyword = "%$cari%";
mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);
$total_guru = mysqli_num_rows($data);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Guru | SchoolAdmin</title>
<link rel="stylesheet" href="assets/style.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
.content-card {
    overflow: hidden;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.section-header p {
    color: #8490a3;
    font-size: 13px;
}

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
    letter-spacing: .4px;
    padding: 15px 13px;
    text-align: left;
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
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: #1e293b;
}

.student-avatar {
    width: 36px;
    height: 36px;
    min-width: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e8efff;
    color: #315fce;
    font-weight: 700;
}

.class-badge {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 20px;
    background: #eef2ff;
    color: #4f46e5;
    font-size: 12px;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 7px;
}

.action-buttons form {
    margin: 0;
}

.table-footer {
    padding-top: 17px;
    color: #64748b;
    font-size: 13px;
}

.empty {
    text-align: center;
    padding: 35px !important;
    color: #929daf !important;
}

@media (max-width: 768px) {
    .section-header {
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .search-form {
        flex-wrap: wrap;
    }

    .search-form input {
        min-width: 100%;
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

        <a href="data_guru.php" class="menu-item active">
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

        <a href="rekap_absensi.php" class="menu-item">
            <span>📊</span> Rekap Absensi
        </a>

        <a href="rekap_nilai.php" class="menu-item">
            <span>📋</span> Rekap Nilai
        </a>

        <a href="profil_admin.php" class="menu-item">
            <span>👤</span> Profil Admin
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
            <h1>Data Guru</h1>
            <p>Kelola seluruh data guru sekolah.</p>
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
            <h2>Data Guru 📚</h2>
            <p>Kelola informasi seluruh guru sekolah.</p>
        </div>

        <div class="welcome-icon">👨‍🏫</div>
    </section>

    <section class="content-card">

        <div class="section-header">
            <div>
                <h2>Daftar Guru</h2>
                <p>Seluruh guru yang terdaftar.</p>
            </div>

            <div class="header-actions">
                <a href="cetak_guru.php?cari=<?= urlencode($cari) ?>"
                   target="_blank" class="print-btn">
                    🖨 Cetak Data
                </a>

                <a href="tambah_guru.php" class="btn-primary">
                    + Tambah Guru
                </a>
            </div>
        </div>

        <form method="GET" class="search-form">

            <input
                type="text"
                name="cari"
                placeholder="Cari nama atau NIP guru..."
                value="<?= e($cari) ?>"
            >

            <button type="submit">Cari</button>

            <?php if ($cari !== ''): ?>
                <a href="data_guru.php" class="reset-btn">Reset</a>
            <?php endif; ?>

        </form>

        <div class="table-responsive">

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama Guru</th>
                        <th>Jenis Kelamin</th>
                        <th>Mata Pelajaran</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                $no = 1;

                if ($total_guru > 0):
                    while ($row = mysqli_fetch_assoc($data)):
                ?>

                    <tr>
                        <td><?= $no++ ?></td>

                        <td>
                            <strong><?= e($row['nip']) ?></strong>
                        </td>

                        <td>
                            <div class="student-name">
                                <div class="student-avatar">
                                    <?= e(strtoupper(substr($row['nama'], 0, 1))) ?>
                                </div>
                                <?= e($row['nama']) ?>
                            </div>
                        </td>

                        <td><?= e($row['jenis_kelamin']) ?></td>

                        <td>
                            <span class="class-badge">
                                <?= e($row['mata_pelajaran']) ?>
                            </span>
                        </td>

                        <td><?= e($row['no_hp']) ?></td>

                        <td><?= e($row['alamat']) ?></td>

                        <td>
                            <div class="action-buttons">

                                <a href="edit_guru.php?id=<?= (int)$row['id'] ?>"
                                   class="edit-btn">Edit</a>

                                <form method="POST"
                                      action="hapus_guru.php"
                                      onsubmit="return confirm('Yakin ingin menghapus guru ini?')">

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
                        <td colspan="8" class="empty">
                            Data guru belum ditemukan.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>
            </table>

        </div>

        <div class="table-footer">
            Menampilkan <?= $total_guru ?> data guru
        </div>

    </section>

    <footer>
        © <?= date('Y') ?> SchoolAdmin. Sistem Manajemen Sekolah.
    </footer>

</main>
</div>

</body>
</html>
