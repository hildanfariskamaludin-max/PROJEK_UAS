<?php
session_start();
// SCRIPT SAKTI: Paksa tampilkan eror jika ada yang macet
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config/koneksi.php';

// Cek parameter aksi dari URL (apakah tambah, edit, atau hapus)
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

if ($aksi == 'tambah') {
    $nik_ktp        = mysqli_real_escape_string($koneksi, $_POST['nik_ktp']);
    $nama_pelanggan = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
    $no_hp          = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    $query_tambah = "INSERT INTO pelanggan_hildan_2430511059 (nik_ktp, nama_pelanggan, no_hp) VALUES ('$nik_ktp', '$nama_pelanggan', '$no_hp')";
    
    if (mysqli_query($koneksi, $query_tambah)) {
        echo "<script>
                alert('Data Pelanggan Berhasil Disimpan, '); 
                window.location.href = '../data_pelanggan.php';
              </script>";
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
} 

// ================= FIX LOGIKA EDIT DATA PELANGGAN LENGKAP =================
elseif ($aksi == 'edit') {
    // Tangkap data dari form modal edit pelanggan
    $id             = intval($_POST['id']);
    $nik_ktp        = mysqli_real_escape_string($koneksi, $_POST['nik_ktp']);
    $nama_pelanggan = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
    $no_hp          = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    // Jalankan query update ke database rentalPS
    $query_update = "UPDATE pelanggan_hildan_2430511059 SET nik_ktp='$nik_ktp', nama_pelanggan='$nama_pelanggan', no_hp='$no_hp' WHERE id='$id'";
    
    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>
                alert('Data Pelanggan Berhasil Diubah, Bang!'); 
                window.location.href = '../data_pelanggan.php';
              </script>";
    } else {
        echo "Gagal mengubah data pelanggan: " . mysqli_error($koneksi);
    }
} 

// ================= LOGIKA HAPUS DATA PELANGGAN (CASCADE MANUAL) =================
elseif ($aksi == 'hapus') {
    $id = intval($_GET['id']);
    
    // 1. Hapus riwayat di tabel transaksi dulu biar foreign key ga error
    mysqli_query($koneksi, "DELETE FROM transaksi_hildan_2430511059 WHERE id_pelanggan = '$id'");

    // 2. Baru hapus data master orangnya
    $query_hapus = "DELETE FROM pelanggan_hildan_2430511059 WHERE id = '$id'";
    
    if (mysqli_query($koneksi, $query_hapus)) {
        echo "<script>
                alert('Data Pelanggan Berhasil Dihapus!'); 
                window.location.href = '../data_pelanggan.php';
              </script>";
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
}
?>