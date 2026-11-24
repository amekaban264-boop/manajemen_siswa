
<?php
require_once 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: data_guru.php");
    exit;
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header("Location: data_guru.php");
    exit;
}

$id = (int) $_POST['id'];

$stmt = mysqli_prepare($conn, "DELETE FROM guru WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>
        alert('Data guru berhasil dihapus!');
        window.location='data_guru.php';
    </script>";
} else {
    echo "<script>
        alert('Data guru gagal dihapus!');
        window.location='data_guru.php';
    </script>";
}

exit;
?>
