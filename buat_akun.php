<?php
// 1. Ambil koneksi database
include 'config/koneksi.php';

// 2. Kosongkan dulu tabel users biar ga bentrok/duplikat
mysqli_query($koneksi, "TRUNCATE TABLE users");

// 3. Set data akun baru
$username     = 'admin';
$password_raw = 'admin123';
$nama_lengkap = 'Nurrifanti H';
$role         = 'admin';

// 4. Enkripsi password secara otomatis lewat PHP biar sinkron dengan proses_login
$password_hash = password_hash($password_raw, PASSWORD_BCRYPT);

// 5. Query insert data ke database
$query = "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$username', '$password_hash', '$nama_lengkap', '$role')";

if (mysqli_query($koneksi, $query)) {
    echo "<h2 style='color: green; font-family: sans-serif; text-align: center; margin-top: 50px;'>
            Akun Admin Berhasil Dibuat/Di-fix, Bang!<br>
            <span style='color: #555; font-size: 18px;'>Sekarang lu udah bisa login pakai <b>admin</b> dan <b>admin123</b></span>
          </h2>";
} else {
    echo "Gagal membuat akun: " . mysqli_error($koneksi);
}
?>