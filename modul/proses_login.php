<?php
// Mengaktifkan session PHP untuk menyimpan status login kasir/admin
session_start();

// Menghubungkan ke file koneksi database
include '../config/koneksi.php';

// Memastikan data dikirim melalui method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Mengamankan inputan dari serangan SQL Injection
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Mengambil data user berdasarkan username
    $query  = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Memverifikasi password yang di-input dengan password hash di database
        if (password_verify($password, $row['password'])) {
            
            // Jika cocok, buat session untuk tanda masuk
            $_SESSION['login']        = true;
            $_SESSION['id_user']      = $row['id'];
            $_SESSION['username']     = $row['username'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
            $_SESSION['role']         = $row['role'];

            // Alihkan ke halaman dashboard utama
            header("Location: ../dashboard.php");
            exit;
        }
    }

    // Jika username tidak ketemu atau password salah, balikin ke login dan kasih pesan error
    echo "<script>
            alert('Username atau Password salah!');
            window.location.href = '../index.php';
          </script>";
} else {
    // Jika mencoba akses langsung tanpa lewat form login
    header("Location: ../index.php");
    exit;
}
?>