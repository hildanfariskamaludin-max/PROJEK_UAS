<?php
// 1. Mengaktifkan Session
session_start();

// 2. SCRIPT SAKTI: Paksa tampilkan eror jika ada salah ketik
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 3. PROTEKSI: Kalau kasir/admin NYATANYA SUDAH LOGIN, langsung lempar ke dashboard otomatis
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PS Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            border-radius: 10px;
            border: 1px solid #dee2e6;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2 class="text-center mb-4" style="font-weight: 600; color: #212529; letter-spacing: -0.5px;">PS Rental</h2>
        
        <form action="modul/proses_login.php" method="POST">
            
            <div class="mb-3">
                <label for="username" class="form-label" style="color: #495057; font-weight: 500;">Username</label>
                <input type="text" class="form-control py-2" id="username" name="username" placeholder="Masukkan username" required>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label" style="color: #495057; font-weight: 500;">Password</label>
                <input type="password" class="form-control py-2" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary py-2.5 text-uppercase" style="font-weight: 600; letter-spacing: 0.5px;">Masuk</button>
            </div>
            
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>