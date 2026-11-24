
<?php
session_start();
require_once 'config/koneksi.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$pesan = "";
$error = "";

if (isset($_POST['ubah_password'])) {
    $lama = $_POST['password_lama'] ?? '';
    $baru = $_POST['password_baru'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if (strlen($baru) < 8) {
        $error = "Password baru minimal 8 karakter.";
    } elseif ($baru !== $konfirmasi) {
        $error = "Konfirmasi password tidak sama.";
    } else {
        $id = $_SESSION['admin_id'];

        $stmt = mysqli_prepare($conn,
            "SELECT password FROM admin_users WHERE id = ?"
        );
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        $hasil = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($hasil);

        if (!$admin || !password_verify($lama, $admin['password'])) {
            $error = "Password lama salah.";
        } else {
            $hash = password_hash($baru, PASSWORD_DEFAULT);

            $update = mysqli_prepare($conn,
                "UPDATE admin_users SET password = ? WHERE id = ?"
            );
            mysqli_stmt_bind_param($update, "si", $hash, $id);

            if (mysqli_stmt_execute($update)) {
                $pesan = "Password berhasil diganti!";
            } else {
                $error = "Password gagal diperbarui.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password | SchoolAdmin</title>
    <link rel="stylesheet" href="assets/style.css">

    <style>
        body {
            background: #f4f6fb;
            font-family: Arial, sans-serif;
        }

        .password-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }

        .password-card {
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0,0,0,.05);
        }

        .password-card h2 {
            color: #1e293b;
            margin-top: 0;
        }

        .password-card p {
            color: #64748b;
        }

        .password-card label {
            display: block;
            margin: 18px 0 8px;
            font-weight: bold;
        }

        .password-card input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d8deea;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .password-card button,
        .back-button {
            display: inline-block;
            margin-top: 22px;
            padding: 12px 18px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }

        .back-button {
            background: #64748b;
        }

        .success {
            padding: 12px;
            background: #dcfce7;
            color: #166534;
            border-radius: 8px;
        }

        .error {
            padding: 12px;
            background: #fee2e2;
            color: #991b1b;
            border-radius: 8px;
        }
    </style>
</head>

<body>
<div class="password-container">
    <div class="password-card">

        <h2>Ganti Password Admin</h2>
        <p>Silakan isi formulir berikut untuk mengganti password.</p>

        <?php if ($pesan): ?>
            <div class="success">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label>Password Lama</label>
            <input type="password" name="password_lama"
                placeholder="Masukkan password lama" required>

            <label>Password Baru</label>
            <input type="password" name="password_baru"
                placeholder="Minimal 8 karakter" minlength="8" required>

            <label>Konfirmasi Password Baru</label>
            <input type="password" name="konfirmasi"
                placeholder="Ulangi password baru" minlength="8" required>

            <button type="submit" name="ubah_password">
                Simpan Password Baru
            </button>

            <a href="dashboard.php" class="back-button">Kembali</a>
        </form>

    </div>
</div>
</body>
</html>
