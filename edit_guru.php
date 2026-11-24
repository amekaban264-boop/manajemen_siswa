
<?php
require_once 'config/koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: data_guru.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM guru WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$guru = mysqli_fetch_assoc($result);

if (!$guru) {
    die("Data guru tidak ditemukan.");
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nip = trim($_POST['nip']);
    $nama = trim($_POST['nama']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $mata_pelajaran = trim($_POST['mata_pelajaran']);
    $no_hp = trim($_POST['no_hp']);
    $alamat = trim($_POST['alamat']);

    $stmt = mysqli_prepare($conn,
        "UPDATE guru SET nip=?, nama=?, jenis_kelamin=?,
        mata_pelajaran=?, no_hp=?, alamat=? WHERE id=?"
    );

    mysqli_stmt_bind_param(
        $stmt, "ssssssi",
        $nip, $nama, $jenis_kelamin,
        $mata_pelajaran, $no_hp, $alamat, $id
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: data_guru.php");
        exit;
    } else {
        $error = "Gagal mengubah data. Pastikan NIP tidak digunakan guru lain.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Guru | SchoolAdmin</title>
<link rel="stylesheet" href="assets/style.css">
</head>

<body>

<main class="main-content">

<header class="topbar">
    <div>
        <h1>Edit Guru</h1>
        <p>Perbarui informasi guru sekolah.</p>
    </div>
</header>

<section class="content-card">

    <div class="section-header">
        <div>
            <h2>Form Edit Guru</h2>
            <p>Ubah informasi guru di bawah ini.</p>
        </div>
    </div>

    <?php if ($error): ?>
        <p style="color:red;margin-bottom:15px;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <div style="margin-bottom:18px;">
            <label>NIP Guru</label>
            <input type="text" name="nip"
            value="<?= htmlspecialchars($guru['nip']) ?>"
            required style="width:100%;padding:12px;margin-top:8px;border:1px solid #ddd;border-radius:8px;">
        </div>

        <div style="margin-bottom:18px;">
            <label>Nama Guru</label>
            <input type="text" name="nama"
            value="<?= htmlspecialchars($guru['nama']) ?>"
            required style="width:100%;padding:12px;margin-top:8px;border:1px solid #ddd;border-radius:8px;">
        </div>

        <div style="margin-bottom:18px;">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" required
            style="width:100%;padding:12px;margin-top:8px;border:1px solid #ddd;border-radius:8px;">
                <option value="Laki-laki" <?= $guru['jenis_kelamin']=='Laki-laki'?'selected':'' ?>>Laki-laki</option>
                <option value="Perempuan" <?= $guru['jenis_kelamin']=='Perempuan'?'selected':'' ?>>Perempuan</option>
            </select>
        </div>

        <div style="margin-bottom:18px;">
            <label>Mata Pelajaran</label>
            <input type="text" name="mata_pelajaran"
            value="<?= htmlspecialchars($guru['mata_pelajaran']) ?>"
            required style="width:100%;padding:12px;margin-top:8px;border:1px solid #ddd;border-radius:8px;">
        </div>

        <div style="margin-bottom:18px;">
            <label>Nomor HP</label>
            <input type="text" name="no_hp"
            value="<?= htmlspecialchars($guru['no_hp']) ?>"
            required style="width:100%;padding:12px;margin-top:8px;border:1px solid #ddd;border-radius:8px;">
        </div>

        <div style="margin-bottom:22px;">
            <label>Alamat</label>
            <textarea name="alamat" required
            style="width:100%;padding:12px;margin-top:8px;border:1px solid #ddd;border-radius:8px;min-height:100px;"><?= htmlspecialchars($guru['alamat']) ?></textarea>
        </div>

        <button type="submit" class="btn-primary"
        style="border:none;cursor:pointer;">
            Simpan Perubahan
        </button>

        <a href="data_guru.php" class="reset-btn">Batal</a>

    </form>

</section>

</main>

</body>
</html>
