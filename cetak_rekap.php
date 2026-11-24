
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

$sql = "
    SELECT
        s.nis,
        s.nama,
        s.kelas,
        COUNT(n.id) AS jumlah_mapel,
        AVG(n.nilai_akhir) AS rata_rata
    FROM siswa s
    LEFT JOIN nilai n ON s.id = n.siswa_id
    GROUP BY s.id, s.nis, s.nama, s.kelas
    ORDER BY s.nama ASC
";

$data = mysqli_query($conn, $sql);

function predikat($nilai) {
    if ($nilai === null) return '-';
    if ($nilai >= 90) return 'A';
    if ($nilai >= 80) return 'B';
    if ($nilai >= 70) return 'C';
    return 'D';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cetak Rekap Nilai | SchoolAdmin</title>

<style>
* {
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    color: #222;
    background: #f1f5f9;
    margin: 0;
    padding: 30px;
}

.container {
    max-width: 1100px;
    margin: auto;
    background: white;
    padding: 35px;
}

.header {
    text-align: center;
    border-bottom: 3px solid #222;
    padding-bottom: 18px;
    margin-bottom: 25px;
}

.header h1 {
    margin: 0 0 8px;
    font-size: 24px;
}

.header p {
    margin: 4px 0;
    font-size: 13px;
}

.info {
    margin-bottom: 20px;
    font-size: 13px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #999;
    padding: 10px 8px;
    font-size: 12px;
    text-align: center;
}

th {
    background: #e8edf5;
}

td.nama {
    text-align: left;
}

.footer {
    margin-top: 45px;
    display: flex;
    justify-content: flex-end;
    text-align: center;
}

.ttd {
    width: 230px;
    font-size: 13px;
    line-height: 1.7;
}

.garis {
    margin-top: 65px;
    font-weight: bold;
    text-decoration: underline;
}

.actions {
    max-width: 1100px;
    margin: 0 auto 15px;
    display: flex;
    gap: 10px;
}

.actions a,
.actions button {
    border: none;
    padding: 11px 18px;
    border-radius: 7px;
    text-decoration: none;
    cursor: pointer;
    font-size: 13px;
    font-weight: bold;
}

.btn-print {
    background: #3478f6;
    color: white;
}

.btn-back {
    background: #e2e8f0;
    color: #334155;
}

@media print {
    body {
        background: white;
        padding: 0;
    }

    .container {
        max-width: none;
        padding: 10px;
    }

    .actions {
        display: none;
    }

    th {
        background: #e8edf5 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    @page {
        size: A4 landscape;
        margin: 15mm;
    }
}
</style>
</head>

<body>

<div class="actions">
    <button class="btn-print" onclick="window.print()">
        Cetak / Simpan PDF
    </button>

    <a href="rekap_nilai.php" class="btn-back">
        Kembali
    </a>
</div>

<div class="container">

    <div class="header">
        <h1>LAPORAN REKAP NILAI SISWA</h1>
        <p>SCHOOL ADMINISTRATION SYSTEM</p>
        <p>Laporan Hasil Belajar Siswa</p>
    </div>

    <div class="info">
        <strong>Tanggal Cetak:</strong> <?= date('d-m-Y') ?>
        <br>
        <strong>Jumlah Siswa:</strong> <?= mysqli_num_rows($data) ?> siswa
    </div>

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
            </tr>
        </thead>

        <tbody>
        <?php
        $no = 1;

        if (mysqli_num_rows($data) > 0):
            while ($row = mysqli_fetch_assoc($data)):
                $rata = $row['rata_rata'] !== null
                    ? (float)$row['rata_rata']
                    : null;
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= e($row['nis']) ?></td>
                <td class="nama"><?= e($row['nama']) ?></td>
                <td><?= e($row['kelas']) ?></td>
                <td><?= (int)$row['jumlah_mapel'] ?></td>
                <td><?= $rata !== null ? number_format($rata, 2) : '-' ?></td>
                <td><?= predikat($rata) ?></td>
            </tr>
        <?php
            endwhile;
        else:
        ?>
            <tr>
                <td colspan="7">Belum ada data siswa.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd">
            Mengetahui,<br>
            Administrator

            <div class="garis">
                <?= e($nama_admin) ?>
            </div>
        </div>
    </div>

</div>

</body>
</html>
