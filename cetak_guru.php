
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

$keyword = trim($_GET['cari'] ?? '');

$stmt = mysqli_prepare(
    $conn,
    "SELECT nip, nama, jenis_kelamin, mata_pelajaran, no_hp, alamat
     FROM guru
     WHERE nip LIKE ? OR nama LIKE ?
     ORDER BY nama ASC"
);

$search = "%$keyword%";
mysqli_stmt_bind_param($stmt, "ss", $search, $search);
mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Data Guru</title>

<style>
body {
    font-family: Arial, sans-serif;
    color: #222;
    padding: 25px;
}

.header {
    text-align: center;
    border-bottom: 3px solid #222;
    padding-bottom: 15px;
    margin-bottom: 25px;
}

.header h2 {
    margin-bottom: 7px;
}

.header p {
    margin: 4px;
    font-size: 13px;
}

.info {
    margin-bottom: 15px;
    font-size: 13px;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

th, td {
    border: 1px solid #555;
    padding: 9px;
    text-align: left;
}

th {
    background: #e8edf5;
    text-align: center;
}

td:first-child {
    text-align: center;
}

.signature {
    margin-top: 45px;
    width: 230px;
    margin-left: auto;
    text-align: center;
    font-size: 13px;
}

.signature .space {
    height: 70px;
}

.actions {
    margin-bottom: 20px;
}

button, a {
    display: inline-block;
    padding: 10px 16px;
    border: 0;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    background: #2563eb;
    color: white;
    font-size: 13px;
}

a {
    background: #64748b;
}

@media print {
    body {
        padding: 0;
    }

    .actions {
        display: none;
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
    <button onclick="window.print()">🖨 Cetak / Simpan PDF</button>
    <a href="data_guru.php">Kembali</a>
</div>

<div class="header">
    <h2>LAPORAN DATA GURU</h2>
    <p>SCHOOL ADMINISTRATION MANAGEMENT SYSTEM</p>
    <p>Laporan Data Guru Sekolah</p>
</div>

<div class="info">
    <strong>Tanggal Cetak:</strong> <?= date('d-m-Y') ?><br>
    <strong>Pencarian:</strong>
    <?= $keyword !== '' ? e($keyword) : 'Semua guru' ?>
</div>

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
        </tr>
    </thead>

    <tbody>
    <?php
    $no = 1;
    $jumlah = 0;

    while ($row = mysqli_fetch_assoc($data)):
        $jumlah++;
    ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= e($row['nip']) ?></td>
            <td><?= e($row['nama']) ?></td>
            <td><?= e($row['jenis_kelamin']) ?></td>
            <td><?= e($row['mata_pelajaran']) ?></td>
            <td><?= e($row['no_hp']) ?></td>
            <td><?= e($row['alamat']) ?></td>
        </tr>
    <?php endwhile; ?>

    <?php if ($jumlah === 0): ?>
        <tr>
            <td colspan="7" style="text-align:center">
                Tidak ada data guru.
            </td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<p><strong>Total guru: <?= $jumlah ?> orang</strong></p>

<div class="signature">
    <p>Mengetahui,</p>
    <p>Administrator</p>
    <div class="space"></div>
    <strong><?= e($_SESSION['nama'] ?? 'Administrator') ?></strong>
</div>

</body>
</html>
