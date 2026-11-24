
<?php
require_once 'config/koneksi.php';

$cari = $_GET['cari'] ?? '';

$stmt = mysqli_prepare($conn,
    "SELECT * FROM kelas
     WHERE nama_kelas LIKE ?
     OR jurusan LIKE ?
     OR wali_kelas LIKE ?
     ORDER BY id DESC"
);

$keyword = "%$cari%";
mysqli_stmt_bind_param($stmt, "sss", $keyword, $keyword, $keyword);
mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Kelas | SchoolAdmin</title>
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
        <a href="dashboard.php" class="menu-item">
            <span>▦</span> Dashboard
        </a>

        <a href="data_siswa.php" class="menu-item">
            <span>♙</span> Data Siswa
        </a>

        <a href="data_guru.php" class="menu-item">
            <span>▤</span> Data Guru
        </a>

        <a href="data_kelas.php" class="menu-item active">
            <span>▣</span> Data Kelas
        </a>

        <a href="absensi.php" class="menu-item">
            <span>◷</span> Absensi
        </a>

        <a href="nilai.php" class="menu-item">
            <span>★</span> Nilai Siswa
        </a>
    </nav>

    <div class="sidebar-bottom">
        <div class="admin-mini">
            <div class="avatar">A</div>
            <div>
                <strong>Administrator</strong>
                <small>Administrator</small>
            </div>
        </div>
        <a href="logout.php" class="logout">↪ Keluar</a>
    </div>

</aside>

<main class="main-content">

    <header class="topbar">
        <div>
            <h1>Data Kelas</h1>
            <p>Kelola seluruh data kelas sekolah.</p>
        </div>

        <div class="admin-profile">
            <div class="avatar">A</div>
            <div>
                <strong>Administrator</strong>
                <small>Administrator</small>
            </div>
        </div>
    </header>

    <section class="welcome">
        <div>
            <span class="welcome-tag">SCHOOL MANAGEMENT SYSTEM</span>
            <h2>Data Kelas 🏫</h2>
            <p>Kelola informasi seluruh kelas sekolah.</p>
        </div>
        <div class="welcome-icon">🏫</div>
    </section>

    <section class="content-card">

        <div class="section-header">
            <div>
                <h2>Daftar Kelas</h2>
                <p>Seluruh kelas yang terdaftar.</p>
            </div>

            <a href="tambah_kelas.php" class="btn-primary">
                + Tambah Kelas
            </a>
        </div>

        <form method="GET" class="search-form">

            <input
                type="text"
                name="cari"
                placeholder="Cari nama kelas, jurusan atau wali kelas..."
                value="<?= htmlspecialchars($cari) ?>"
            >

            <button type="submit">Cari</button>

            <?php if ($cari != ''): ?>
                <a href="data_kelas.php" class="reset-btn">Reset</a>
            <?php endif; ?>

        </form>

        <div class="table-responsive">
            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelas</th>
                        <th>Tingkat</th>
                        <th>Jurusan</th>
                        <th>Wali Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                $no = 1;

                if (mysqli_num_rows($data) > 0):
                    while ($row = mysqli_fetch_assoc($data)):
                ?>

                    <tr>
                        <td><?= $no++ ?></td>

                        <td>
                            <strong><?= htmlspecialchars($row['nama_kelas']) ?></strong>
                        </td>

                        <td><?= htmlspecialchars($row['tingkat']) ?></td>

                        <td>
                            <span class="class-badge">
                                <?= htmlspecialchars($row['jurusan']) ?>
                            </span>
                        </td>

                        <td><?= htmlspecialchars($row['wali_kelas']) ?></td>

                        <td>
                            <div class="action-buttons">

                                <a href="edit_kelas.php?id=<?= (int)$row['id'] ?>"
                                   class="edit-btn">Edit</a>

                                <form method="POST"
                                      action="hapus_kelas.php"
                                      onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">

                                    <input type="hidden"
                                           name="id"
                                           value="<?= (int)$row['id'] ?>">

                                    <button type="submit"
                                            class="delete-btn">
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
                        <td colspan="6" class="empty">
                            Data kelas belum ditemukan.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>
            </table>
        </div>

        <div class="table-footer">
            Menampilkan <?= mysqli_num_rows($data) ?> data kelas
        </div>

    </section>

    <footer>
        © <?= date('Y') ?> SchoolAdmin. Sistem Manajemen Sekolah.
    </footer>

</main>
</div>

</body>
</html>
