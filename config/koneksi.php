<?php
// Hapus atau komentari baris session_start jika ada di sini
// session_start(); 

$host     = "localhost";
$username = "root";
$password = ""; 
$database = "rentalps"; 

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>