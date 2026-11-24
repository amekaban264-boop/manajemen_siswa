
<?php
session_start();
require_once 'config/koneksi.php';

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conn,
        "SELECT id, username, nama, password
         FROM admin_users WHERE username = ? LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $hasil = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($hasil);

    if ($admin && password_verify($password, $admin['password'])) {
        session_regenerate_id(true);

        $_SESSION['login'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['nama'] = $admin['nama'];

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin | SchoolAdmin</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    background: linear-gradient(135deg, #101d3a, #1d4ed8, #4385f5);
}

.login-wrapper {
    width: 100%;
    max-width: 950px;
    min-height: 560px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    background: white;
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 25px 60px rgba(0,0,0,.25);
}

.login-left {
    background: linear-gradient(145deg, #17264a, #2563eb);
    color: white;
    padding: 55px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.brand {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 65px;
}

.brand-icon {
    width: 52px;
    height: 52px;
    background: #ffffff;
    color: #2563eb;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 29px;
    font-weight: 800;
}

.brand h2 {
    font-size: 23px;
}

.brand h2 span {
    color: #8bb8ff;
}

.brand small {
    font-size: 11px;
    opacity: .8;
}

.login-left h1 {
    font-size: 32px;
    line-height: 1.4;
    margin-bottom: 15px;
}

.login-left p {
    font-size: 14px;
    line-height: 1.8;
    color: #dbeafe;
}

.school-icon {
    font-size: 75px;
    margin-top: 35px;
}

.login-right {
    padding: 55px 45px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.login-right h2 {
    font-size: 27px;
    color: #172554;
    margin-bottom: 10px;
}

.subtitle {
    color: #64748b;
    font-size: 13px;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 9px;
}

.form-group input {
    width: 100%;
    padding: 14px 15px;
    border: 1px solid #dbe2ee;
    border-radius: 9px;
    outline: none;
    font-size: 14px;
    transition: .2s;
}

.form-group input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.12);
}

.login-button {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 9px;
    background: #2563eb;
    color: white;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: .2s;
}

.login-button:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
}

.error {
    background: #fee2e2;
    color: #b91c1c;
    padding: 12px;
    border-radius: 8px;
    font-size: 13px;
    margin-bottom: 20px;
}

.footer {
    text-align: center;
    color: #94a3b8;
    font-size: 11px;
    margin-top: 35px;
}

@media(max-width: 700px) {
    .login-wrapper {
        grid-template-columns: 1fr;
    }

    .login-left {
        padding: 30px;
    }

    .brand {
        margin-bottom: 25px;
    }

    .login-left h1 {
        font-size: 25px;
    }

    .school-icon {
        display: none;
    }

    .login-right {
        padding: 35px 25px;
    }
}
</style>
</head>

<body>

<div class="login-wrapper">

    <div class="login-left">
        <div class="brand">
            <div class="brand-icon">S</div>
            <div>
                <h2>School<span>Admin</span></h2>
                <small>Management System</small>
            </div>
        </div>

        <h1>Selamat Datang di SchoolAdmin!</h1>

        <p>
            Sistem manajemen sekolah untuk membantu mengelola
            data siswa, guru, kelas, absensi, dan nilai
            dengan lebih mudah dan praktis.
        </p>

        <div class="school-icon">🏫</div>
    </div>

    <div class="login-right">

        <h2>Login Admin</h2>
        <p class="subtitle">
            Silakan masuk menggunakan akun administrator.
        </p>

        <?php if ($error): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label>Username</label>
                <input
                    type="text"
                    name="username"
                    placeholder="Masukkan username"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="form-group">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" name="login" class="login-button">
                Masuk ke Dashboard →
            </button>

        </form>

        <div class="footer">
            © <?= date('Y') ?> SchoolAdmin. All Rights Reserved.
        </div>

    </div>

</div>

</body>
</html>

