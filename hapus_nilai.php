
<?php
session_start();
require_once 'config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Penghapusan hanya boleh melalui tombol POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: nilai.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    header("Location: nilai.php");
    exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM nilai WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: nilai.php");
exit;
?>
