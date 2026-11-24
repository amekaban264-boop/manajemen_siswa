
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

require 'config/koneksi.php';

$error = "";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("ID tidak valid");
}

// Mengambil data siswa
$stmt = mysqli_prepare($conn, "SELECT * FROM siswa WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$data) {
    die("Data tidak ditemukan");
}

// Memperbarui data siswa
if (isset($_POST['update'])) {
    $nama = trim($_POST['nama']);
    $nis = trim($_POST['nis']);
    $kelas = trim($_POST['kelas']);
    $alamat = trim($_POST['alamat']);
    $jk = $_POST['jenis_kelamin'];

    // Menjaga isi form jika update gagal
    $data['nama'] = $nama;
    $data['nis'] = $nis;
    $data['kelas'] = $kelas;
    $data['alamat'] = $alamat;
    $data['jenis_kelamin'] = $jk;

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE siswa SET
        nama=?, nis=?, kelas=?, alamat=?, jenis_kelamin=?
        WHERE id=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssssi",
        $nama,
        $nis,
        $kelas,
        $alamat,
        $jk,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: data_siswa.php");
        exit;
    } else {
        $error = "Gagal memperbarui data. Pastikan NIS belum digunakan siswa lain.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Data Siswa - SchoolAdmin</title>

    <link rel="stylesheet" href="assets/style.css">

    <style>
        .form-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            max-width: 850px;
        }

        .form-description {
            color: #777;
            margin-top: 8px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .student-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dce1e8;
            border-radius: 7px;
            font-size: 14px;
            font-family: inherit;
            box-sizing: border-box;
            outline: none;
            background: #fff;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #3478f6;
            box-shadow: 0 0 0 3px rgba(52,120,246,0.10);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            gap: 12px;
            margin-top: 10px;
        }

        .btn-save {
            background: #3478f6;
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 7px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-save:hover {
            background: #2463d5;
        }

        .btn-back {
            background: #eef1f5;
            color: #444;
            padding: 12px 22px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .error-message {
            background: #fff0f0;
            color: #c0392b;
            padding: 12px 15px;
            border-radius: 7px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        @media (max-width: 650px) {
            .student-form {
                grid-template-columns: 1fr;
            }

            .form-group.full,
            .form-actions {
                grid-column: 1;
            }

            .form-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="brand">SchoolAdmin</div>

        <p class="menu-label">MENU UTAMA</p>

        <a href="dashboard.php" class="menu-item">Dashboard</a>
        <a href="data_siswa.php" class="menu-item active">Data Siswa</a>
        <a href="data_guru.php" class="menu-item">Data Guru</a>
        <a href="data_kelas.php" class="menu-item">Data Kelas</a>
        <a href="absensi.php" class="menu-item">Absensi</a>
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
            <h2>Edit Data Siswa</h2>
        </div>

        <div class="form-card">

            <h3>Formulir Edit Siswa</h3>

            <p class="form-description">
                Silakan ubah informasi siswa sesuai data terbaru.
            </p>

            <?php if ($error != ""): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="student-form">

                <div class="form-group">
                    <label>Nama Lengkap Siswa</label>
                    <input
                        type="text"
                        name="nama"
                        value="<?php echo htmlspecialchars($data['nama']); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Nomor Induk Siswa (NIS)</label>
                    <input
                        type="text"
                        name="nis"
                        value="<?php echo htmlspecialchars($data['nis']); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Kelas</label>
                    <input
                        type="text"
                        name="kelas"
                        value="<?php echo htmlspecialchars($data['kelas']); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>

                    <select name="jenis_kelamin" required>
                        <option value="Laki-laki"
                            <?php echo ($data['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>
                            Laki-laki
                        </option>

                        <option value="Perempuan"
                            <?php echo ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>
                            Perempuan
                        </option>
                    </select>
                </div>

                <div class="form-group full">
                    <label>Alamat Lengkap</label>

                    <textarea name="alamat"><?php echo htmlspecialchars($data['alamat']); ?></textarea>
                </div>

                <div class="form-actions">

                    <button
                        type="submit"
                        name="update"
                        class="btn-save"
                    >
                        Update Data
                    </button>

                    <a href="data_siswa.php" class="btn-back">
                        Kembali
                    </a>

                </div>

            </form>

        </div>

    </main>

</div>

</body>
</html>
