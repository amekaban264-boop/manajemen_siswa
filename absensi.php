
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'config/koneksi.php';

$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$pesan = "";

// Menyimpan absensi
if (isset($_POST['simpan'])) {
    $tanggal = $_POST['tanggal'];
    $siswa_id = $_POST['siswa_id'] ?? [];
    $status = $_POST['status'] ?? [];
    $keterangan = $_POST['keterangan'] ?? [];

    $query = mysqli_prepare($conn, "
        INSERT INTO absensi (siswa_id, tanggal, status, keterangan)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            status = VALUES(status),
            keterangan = VALUES(keterangan)
    ");

    foreach ($siswa_id as $i => $id) {
        $statusSiswa = $status[$i];
        $keteranganSiswa = trim($keterangan[$i] ?? '');

        mysqli_stmt_bind_param(
            $query,
            "isss",
            $id,
            $tanggal,
            $statusSiswa,
            $keteranganSiswa
        );

        mysqli_stmt_execute($query);
    }

    mysqli_stmt_close($query);
    $pesan = "Absensi berhasil disimpan!";
}

// Mengambil data siswa dan absensi
$sql = "
    SELECT
        siswa.id,
        siswa.nis,
        siswa.nama,
        siswa.kelas,
        absensi.status,
        absensi.keterangan
    FROM siswa
    LEFT JOIN absensi
        ON siswa.id = absensi.siswa_id
        AND absensi.tanggal = ?
    ORDER BY siswa.nama ASC
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $tanggal);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Siswa</title>
    <link rel="stylesheet" href="assets/style.css">

    <style>
        .attendance-input {
            width: 100%;
            padding: 9px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .attendance-note {
            min-width: 180px;
        }

        .attendance-date {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin: 10px 0 20px;
        }

        .success-message {
            background: #e7f8ed;
            color: #218447;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<div class="dashboard">

    <aside class="sidebar">
        <div class="brand">SchoolAdmin</div>

        <p class="menu-label">MENU UTAMA</p>

        <a href="dashboard.php" class="menu-item">Dashboard</a>
        <a href="data_siswa.php" class="menu-item">Data Siswa</a>
        <a href="data_guru.php" class="menu-item">Data Guru</a>
        <a href="data_kelas.php" class="menu-item">Data Kelas</a>
        <a href="absensi.php" class="menu-item active">Absensi</a>
        <a href="nilai.php" class="menu-item">Data Nilai</a>

        <div class="sidebar-bottom">
            <div class="admin-mini">
                <div class="avatar">A</div>
                <span>
                    <?php echo htmlspecialchars($_SESSION['nama'] ?? 'Admin'); ?>
                </span>
            </div>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <h2>Absensi Siswa</h2>
        </div>

        <div class="content-card">

            <div class="section-header">
                <div>
                    <h3>Daftar Absensi</h3>
                    <p>Kelola kehadiran dan keterangan siswa.</p>
                </div>
            </div>

            <?php if ($pesan != ""): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($pesan); ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <label for="tanggal"><b>Tanggal Absensi</b></label><br>

                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    class="attendance-date"
                    value="<?php echo htmlspecialchars($tanggal); ?>"
                    required
                    onchange="ubahTanggal(this.value)"
                >

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php if (mysqli_num_rows($result) > 0): ?>

                            <?php $no = 1; ?>

                            <?php while ($siswa = mysqli_fetch_assoc($result)): ?>

                                <tr>
                                    <td><?php echo $no++; ?></td>

                                    <td>
                                        <?php echo htmlspecialchars($siswa['nis']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($siswa['nama']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($siswa['kelas']); ?>
                                    </td>

                                    <td>
                                        <input
                                            type="hidden"
                                            name="siswa_id[]"
                                            value="<?php echo $siswa['id']; ?>"
                                        >

                                        <select
                                            name="status[]"
                                            class="attendance-input"
                                            required
                                        >
                                            <?php
                                            $statusSekarang = $siswa['status'] ?? 'Hadir';
                                            $pilihan = ['Hadir', 'Izin', 'Sakit', 'Alpa'];

                                            foreach ($pilihan as $pilih):
                                            ?>
                                                <option
                                                    value="<?php echo $pilih; ?>"
                                                    <?php echo ($statusSekarang == $pilih) ? 'selected' : ''; ?>
                                                >
                                                    <?php echo $pilih; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>

                                    <td>
                                        <input
                                            type="text"
                                            name="keterangan[]"
                                            class="attendance-input attendance-note"
                                            placeholder="Masukkan keterangan..."
                                            value="<?php echo htmlspecialchars($siswa['keterangan'] ?? ''); ?>"
                                        >
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" style="text-align:center;">
                                    Belum ada data siswa.
                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>
                    </table>
                </div>

                <?php if (mysqli_num_rows($result) > 0): ?>
                    <button
                        type="submit"
                        name="simpan"
                        class="btn-primary"
                        style="margin-top: 20px;"
                    >
                        Simpan Absensi
                    </button>
                <?php endif; ?>

            </form>

        </div>

    </main>
</div>

<script>
function ubahTanggal(tanggal) {
    window.location.href = 'absensi.php?tanggal=' + tanggal;
}
</script>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
?>
