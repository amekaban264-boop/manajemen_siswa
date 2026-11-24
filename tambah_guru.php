
<?php
require_once 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nip = trim($_POST['nip']);
    $nama = trim($_POST['nama']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $mata_pelajaran = trim($_POST['mata_pelajaran']);
    $no_hp = trim($_POST['no_hp']);
    $alamat = trim($_POST['alamat']);

    $stmt = mysqli_prepare($conn,
        "INSERT INTO guru
        (nip, nama, jenis_kelamin, mata_pelajaran, no_hp, alamat)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssssss",
        $nip,
        $nama,
        $jenis_kelamin,
        $mata_pelajaran,
        $no_hp,
        $alamat
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Data guru berhasil ditambahkan!');
            window.location='data_guru.php';
        </script>";
        exit;
    } else {
        $error = "Gagal menyimpan data. Pastikan NIP belum digunakan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Guru | SchoolAdmin</title>
<link rel="stylesheet" href="assets/style.css">
</head>

<body>

<div class="main-content">

    <header class="topbar">
        <div>
            <h1>Tambah Guru</h1>
            <p>Masukkan informasi guru baru.</p>
        </div>
    </header>

    <section class="content-card">

        <div class="section-header">
            <div>
                <h2>Form Tambah Guru</h2>
                <p>Lengkapi semua informasi berikut.</p>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <p style="color:red;margin-bottom:15px;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST">

            <div style="margin-bottom:18px;">
                <label>NIP Guru</label>
                <input type="text" name="nip" required
                style="width:100%;padding:12px;margin-top:8px;border:1px solid #ddd;border-radius:8px;">
            </div>

            <div style="margin-bottom:18px;">
                <label>Nama Guru</label>
                <input type="text" name="nama" required
                style="width:100%;padding:12px;margin-top:8px;border:1px solid #ddd;border-radius:8px;">
            </div>

            <div style="margin-bottom:18px;">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" required
                style="width:100%;padding:12px;margin-top:8px;border:1px solid #ddd;border-radius:8px;">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            <div style="margin-bottom:18px;">
                <label>Mata Pelajaran</label>
                <input type="text" name="mata_pelajaran" required
                style="width:100%;padding:12px;margin-top:8px;border:1px solid #ddd;border-radius:8px;">
            </div>

            <div style="margin-bottom:18px;">
                <label>Nomor HP</label>
                <input type="text" name="no_hp" required
                style="width:100%;padding:12px;margin-top:8px;border:1px solid #ddd;border-radius:8px;">
            </div>

            <div style="margin-bottom:22px;">
                <label>Alamat</label>
                <textarea name="alamat" required
                style="width:100%;padding:12px;margin-top:8px;border:1px solid #ddd;border-radius:8px;min-height:100px;"></textarea>
            </div>

            <button type="submit" class="btn-primary"
            style="border:none;cursor:pointer;">
                Simpan Guru
            </button>

            <a href="data_guru.php" class="reset-btn">
                Batal
            </a>

        </form>

    </section>

</div>

</body>
</html>
