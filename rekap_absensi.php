
<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$tanggal_awal = $_GET['tanggal_awal'] ?? date('Y-m-01');
$tanggal_akhir = $_GET['tanggal_akhir'] ?? date('Y-m-d');

// Validasi format tanggal
if (
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_awal) ||
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_akhir) ||
    !strtotime($tanggal_awal) ||
    !strtotime($tanggal_akhir) ||
    $tanggal_awal > $tanggal_akhir
) {
    $tanggal_awal = date('Y-m-01');
    $tanggal_akhir = date('Y-m-d');
}

$jumlah = [
    'Hadir' => 0,
    'Izin' => 0,
    'Sakit' => 0,
    'Alpa' => 0
];

$stmt = mysqli_prepare($conn, "
    SELECT status, COUNT(*) AS total
    FROM absensi
    WHERE tanggal BETWEEN ? AND ?
    GROUP BY status
");

mysqli_stmt_bind_param($stmt, "ss", $tanggal_awal, $tanggal_akhir);
mysqli_stmt_execute($stmt);
$hasil_jumlah = mysqli_stmt_get_result($stmt);

while ($data = mysqli_fetch_assoc($hasil_jumlah)) {
    $jumlah[$data['status']] = (int)$data['total'];
}

$total_absensi = array_sum($jumlah);

$query = mysqli_prepare($conn, "
    SELECT
        s.nis,
        s.nama,
        s.kelas,
        SUM(a.status = 'Hadir') AS hadir,
        SUM(a.status = 'Izin') AS izin,
        SUM(a.status = 'Sakit') AS sakit,
        SUM(a.status = 'Alpa') AS alpa,
        COUNT(a.id) AS total
    FROM siswa s
    LEFT JOIN absensi a
        ON s.id = a.siswa_id
        AND a.tanggal BETWEEN ? AND ?
    GROUP BY s.id, s.nis, s.nama, s.kelas
    ORDER BY s.nama ASC
");

mysqli_stmt_bind_param($query, "ss", $tanggal_awal, $tanggal_akhir);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rekap Absensi</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6fb;
    color: #263248;
}

.container {
    max-width: 1250px;
    margin: 35px auto;
    padding: 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 25px;
}

h2 {
    margin: 0 0 8px;
}

.header p {
    color: #718096;
    margin: 0;
}

.btn {
    display: inline-block;
    padding: 11px 17px;
    border-radius: 8px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-weight: bold;
    background: #2563eb;
    color: white;
}

.btn-back {
    background: #64748b;
}

.filter, .card, .stat {
    background: white;
    padding: 22px;
    border-radius: 12px;
    margin-bottom: 22px;
    box-shadow: 0 3px 12px rgba(0,0,0,.04);
}

.filter form {
    display: flex;
    align-items: end;
    gap: 15px;
    flex-wrap: wrap;
}

.filter label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
}

input[type="date"] {
    padding: 10px;
    border: 1px solid #d8deea;
    border-radius: 7px;
}

.stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 22px;
}

.stat {
    margin-bottom: 0;
}

.stat small {
    color: #718096;
}

.stat h2 {
    margin-top: 10px;
    font-size: 28px;
}

.table-wrap {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 850px;
}

th, td {
    padding: 13px;
    border-bottom: 1px solid #edf0f5;
    text-align: left;
}

th {
    background: #f7f9fc;
    color: #475569;
}

td {
    font-size: 14px;
}

.total-box {
    margin-top: 15px;
    color: #475569;
    font-weight: bold;
}

@media(max-width:700px) {
    .stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media print {
    body {
        background: white;
    }

    .container {
        margin: 0;
        max-width: none;
        padding: 10px;
    }

    .filter, .no-print {
        display: none !important;
    }

    .card, .stat {
        box-shadow: none;
        border: 1px solid #ddd;
    }

    table {
        min-width: 0;
    }

    th, td {
        padding: 8px;
        font-size: 11px;
    }
}
</style>
</head>

<body>
<div class="container">

    <div class="header">
        <div>
            <h2>Rekap Absensi Siswa</h2>
            <p>Laporan kehadiran siswa berdasarkan periode tanggal.</p>
        </div>

        <div class="no-print">
            <a href="dashboard.php" class="btn btn-back">Kembali</a>
            <button onclick="window.print()" class="btn">
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <div class="filter">
        <form method="GET">
            <div>
                <label>Tanggal Awal</label>
                <input type="date" name="tanggal_awal"
                    value="<?= htmlspecialchars($tanggal_awal) ?>" required>
            </div>

            <div>
                <label>Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir"
                    value="<?= htmlspecialchars($tanggal_akhir) ?>" required>
            </div>

            <button type="submit" class="btn">Tampilkan</button>
        </form>
    </div>

    <div class="stats">
        <div class="stat">
            <small>Total Hadir</small>
            <h2><?= $jumlah['Hadir'] ?></h2>
        </div>

        <div class="stat">
            <small>Total Izin</small>
            <h2><?= $jumlah['Izin'] ?></h2>
        </div>

        <div class="stat">
            <small>Total Sakit</small>
            <h2><?= $jumlah['Sakit'] ?></h2>
        </div>

        <div class="stat">
            <small>Total Alpa</small>
            <h2><?= $jumlah['Alpa'] ?></h2>
        </div>
    </div>

    <div class="card">
        <h3>Detail Kehadiran Siswa</h3>

        <p>
            Periode:
            <?= date('d-m-Y', strtotime($tanggal_awal)) ?>
            sampai
            <?= date('d-m-Y', strtotime($tanggal_akhir)) ?>
        </p>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Hadir</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Alpa</th>
                        <th>Total</th>
                        <th>Kehadiran</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)):
                    $hadir = (int)$row['hadir'];
                    $izin = (int)$row['izin'];
                    $sakit = (int)$row['sakit'];
                    $alpa = (int)$row['alpa'];
                    $total = (int)$row['total'];

                    $persentase = $total > 0
                        ? round(($hadir / $total) * 100, 1)
                        : 0;
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nis']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['kelas']) ?></td>
                        <td><?= $hadir ?></td>
                        <td><?= $izin ?></td>
                        <td><?= $sakit ?></td>
                        <td><?= $alpa ?></td>
                        <td><?= $total ?></td>
                        <td><?= $persentase ?>%</td>
                    </tr>
                <?php endwhile; ?>

                <?php if ($no === 1): ?>
                    <tr>
                        <td colspan="10" style="text-align:center">
                            Belum ada data siswa.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="total-box">
            Total seluruh catatan absensi: <?= $total_absensi ?>
        </div>
    </div>

</div>
</body>
</html>
