<?php
session_start();
include 'config/koneksi.php';

$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Username & Password Admin (Bisa kamu sesuaikan di sini)
    if ($username === 'admin_eswaras' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $username;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Es Waras</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; margin: 0; padding: 0; }
        body { 
            background-color: #a7f3d0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh;
        }
        .login-card {
            background: white;
            padding: 35px 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 380px;
            text-align: center;
        }
        .brand-title {
            font-size: 16px;
            font-weight: 800;
            color: #1b5e20;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 11px;
            color: #666;
            margin-bottom: 25px;
        }
        .form-group {
            text-align: left;
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 6px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            background: #fef08a;
            border: 1px solid #eab308;
            border-radius: 8px;
            font-size: 13px;
            outline: none;
        }
        .btn-login {
            background: #69f0ae;
            color: #1b5e20;
            border: 1px solid #00c853;
            width: 100%;
            padding: 12px;
            border-radius: 20px;
            font-weight: 800;
            cursor: pointer;
            font-size: 13px;
            margin-top: 10px;
        }
        .btn-login:hover {
            background: #00c853;
            color: white;
        }
        .error-msg {
            background: #ffebee;
            color: #c62828;
            padding: 8px;
            border-radius: 6px;
            font-size: 12px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            font-size: 12px;
            color: #2e7d32;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-title">LOGIN ADMIN</div>
        <div class="subtitle">Sistem Pengelola Kedai Es Waras</div>

        <?php if ($error != '') : ?>
            <div class="error-msg"><?= $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Username :</label>
                <input type="text" name="username" placeholder="admin_eswaras" required>
            </div>
            <div class="form-group">
                <label>Password :</label>
                <input type="password" name="password" placeholder="••••••••••••" required>
            </div>
            <button type="submit" name="login" class="btn-login">[ MASUK / LOGIN ]</button>
        </form>

        <a href="index.php" class="back-link">← Kembali ke Katalog Utama</a>
    </div>

</body>
</html>