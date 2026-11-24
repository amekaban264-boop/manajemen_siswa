
<?php
session_start();
require_once 'config/koneksi.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['admin_id'];
$pesan = "";
$error = "";

// Mengambil data admin
$stmt = mysqli_prepare($conn,
    "SELECT username, nama FROM admin_users WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($hasil);

if (!$admin) {
    die("Data administrator tidak ditemukan.");
}

// Menyimpan perubahan nama
if (isset($_POST['simpan'])) {
    $nama_baru = trim($_POST['nama'] ?? '');

    if ($nama_baru === '') {
        $error = "Nama administrator tidak boleh kosong.";
    } elseif (strlen($nama_baru) > 100) {
        $error = "Nama maksimal 100 karakter.";
    } else {
        $update = mysqli_prepare($conn,
            "UPDATE admin_users SET nama = ? WHERE id = ?"
        );

        mysqli_stmt_bind_param($update, "si", $nama_baru, $id);

        if (mysqli_stmt_execute($update)) {
            $_SESSION['nama'] = $nama_baru;
            $admin['nama'] = $nama_baru;
            $pesan = "Profil berhasil diperbarui!";
        } else {
            $error = "Profil gagal diperbarui.";
        }
    }
}

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil Admin | SchoolAdmin</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background: #f4f6fb;
    color: #1e293b;
}

.container {
    max-width: 850px;
    margin: 45px auto;
    padding: 20px;
}

.header {
    margin-bottom: 25px;
}

.header h1 {
    margin: 0 0 8px;
    font-size: 27px;
}

.header p {
    color: #64748b;
    font-size: 14px;
}

.profile-card {
    background: white;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(0,0,0,.04);
}

.profile-top {
    display: flex;
    align-items: center;
    gap: 18px;
    padding-bottom: 25px;
    border-bottom: 1px solid #edf0f5;
    margin-bottom: 25px;
}

.avatar {
    width: 75px;
    height: 75px;
    border-radius: 50%;
    background: #dbeafe;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    font-weight: 800;
}

.profile-top h2 {
    margin: 0 0 7px;
}

.profile-top p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 9px;
}

.form-group input {
    width: 100%;
    padding: 13px;
    border: 1px solid #dbe2ee;
    border-radius: 9px;
    font-size: 14px;
    outline: none;
}

.form-group input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.10);
}

.form-group input[readonly] {
    background: #f1f5f9;
    color: #64748b;
}

.button {
    display: inline-block;
    padding: 13px 20px;
    border: none;
    border-radius: 9px;
    background: #2563eb;
    color: white;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
}

.button:hover {
    background: #1d4ed8;
}

.button-back {
    background: #64748b;
    margin-left: 7px;
}

.button-back:hover {
    background: #475569;
}

.success {
    padding: 13px;
    background: #dcfce7;
    color: #166534;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.error {
    padding: 13px;
    background: #fee2e2;
    color: #991b1b;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

@media(max-width: 600px) {
    .profile-card {
        padding: 22px;
    }

    .button {
        margin-bottom: 8px;
    }
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Profil Administrator</h1>
        <p>Kelola informasi akun administrator SchoolAdmin.</p>
    </div>

    <div class="profile-card">

        <div class="profile-top">
            <div class="avatar">A</div>
            <div>
                <h2><?= e($admin['nama']) ?></h2>
                <p>Administrator SchoolAdmin</p>
            </div>
        </div>

        <?php if ($pesan): ?>
            <div class="success"><?= e($pesan) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label>Username</label>
                <input type="text"
                    value="<?= e($admin['username']) ?>"
                    readonly>
            </div>

            <div class="form-group">
                <label>Nama Administrator</label>
                <input type="text"
                    name="nama"
                    value="<?= e($admin['nama']) ?>"
                    placeholder="Masukkan nama administrator"
                    maxlength="100"
                    required>
            </div>

            <button type="submit" name="simpan" class="button">
                Simpan Perubahan
            </button>

            <a href="dashboard.php" class="button button-back">
                Kembali
            </a>

        </form>

        <hr style="border:0;border-top:1px solid #edf0f5;margin:28px 0;">

        <h3>Keamanan Akun</h3>
        <p style="color:#64748b;font-size:14px;">
            Kamu juga bisa mengganti password akun administrator.
        </p>

        <a href="ganti_password.php" class="button">
            🔒 Ganti Password
        </a>

    </div>

</div>

</body>
</html>
