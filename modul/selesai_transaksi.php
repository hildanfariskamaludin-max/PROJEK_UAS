<?php
session_start();
// SCRIPT SAKTI: Paksa tampilkan eror jika ada yang macet
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config/koneksi.php';

// Ambil ID transaksi dari parameter URL
if (isset($_GET['id'])) {
    $id_transaksi = intval($_GET['id']);

    // 1. Cari tahu dulu ID unit PS yang digunakan dalam transaksi ini
    $query_cari = mysqli_query($koneksi, "SELECT id_unit_ps FROM transaksi_hildan_2430511059 WHERE id = '$id_transaksi'");
    $data_transaksi = mysqli_fetch_assoc($query_cari);
    $id_unit_ps = $data_transaksi['id_unit_ps'];

    // 2. Update status transaksi menjadi 'selesai' dan set jam selesai ke waktu sekarang
    $jam_sekarang = date('Y-m-d H:i:s');
    $query_update_transaksi = "UPDATE transaksi_hildan_2430511059 SET status_transaksi = 'selesai', jam_selesai = '$jam_sekarang' WHERE id = '$id_transaksi'";
    
    if (mysqli_query($koneksi, $query_update_transaksi)) {
        
        // 3. Ubah kembali status unit PS menjadi 'ready' agar bisa disewa lagi
        mysqli_query($koneksi, "UPDATE unitps_hildan_2430511059 SET status = 'ready' WHERE id = '$id_unit_ps'");

        echo "<script>
                alert('Transaksi Berhasil Diselesaikan! Unit PS kembali Ready.');
                window.location.href = '../dashboard.php';
              </script>";
    } else {
        echo "Gagal menyelesaikan transaksi: " . mysqli_error($koneksi);
    }
} else {
    header("Location: ../dashboard.php");
    exit;
}
?>