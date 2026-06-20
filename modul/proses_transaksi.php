<?php
session_start();
// Paksa tampilkan eror jika ada yang macet
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Ambil data dari form modal dashboard
    $nama_pelanggan = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $id_unit_ps     = mysqli_real_escape_string($koneksi, $_POST['id_ps']);
    $durasi_jam     = intval($_POST['durasi']);
    
    // 2. Generate Otomatis Kode Transaksi (Contoh: -20260614-001)
    $tanggal_sekarang = date('Ymd');
    $kode_transaksi   = "-" . $tanggal_sekarang . "-" . rand(100, 999);

    // 3. Atur Waktu Mulai & Selesai secara otomatis
    $jam_mulai   = date('Y-m-d H:i:s');
    $jam_selesai = date('Y-m-d H:i:s', strtotime("+$durasi_jam hours"));

    // 4. Hitung Total Bayar (Ambil harga per jam dari tabel unitps_hildan_2430511059)
    $query_ps  = mysqli_query($koneksi, "SELECT harga_per_jam FROM unitps_hildan_2430511059 WHERE id = '$id_unit_ps'");
    $data_ps   = mysqli_fetch_assoc($query_ps);
    $harga_ps  = $data_ps['harga_per_jam'];
    $total_bayar = $harga_ps * $durasi_jam;

    // 5. PROSES DECODE CANVAS TANDA TANGAN DIGITAL (.png)
    $ttd_base64 = $_POST['ttd_image'];
    $nama_file_ttd = "default.png"; // fallback jika user ga ttd

    if (!empty($ttd_base64)) {
        // Hapus teks header Base64 ("data:image/png;base64,")
        $filteredData = explode(',', $ttd_base64);
        $unencodedData = base64_decode($filteredData[1]);

        // Berikan nama file unik untuk gambar ttd-nya
        $nama_file_ttd = "ttd_" . uniqid() . ".png";
        $jalur_simpan  = "../uploads/ttd/" . $nama_file_ttd;

        // Simpan file fisik gambar ke folder uploads/ttd/
        file_put_contents($jalur_simpan, $unencodedData);
    }

    // 6. PROSES INSERT DATA UTAMA (Ke Tabel Pelanggan & Transaksi)
    // Kita buat data pelanggan instan dulu biar foreign key-nya sinkron
    $nik_dummy = "ID-" . rand(1000, 9999);
    mysqli_query($koneksi, "INSERT INTO pelanggan_hildan_2430511059 (nik_ktp, nama_pelanggan, no_hp) VALUES ('$nik_dummy', '$nama_pelanggan', '-')");
    $id_pelanggan_baru = mysqli_insert_id($koneksi);

    // Insert ke tabel utama transaksi
    $query_transaksi = "INSERT INTO transaksi_hildan_2430511059 (kode_transaksi, id_pelanggan, id_unit_ps, jam_mulai, jam_selesai, total_bayar, ttd_digital, status_transaksi) 
                        VALUES ('$kode_transaksi', '$id_pelanggan_baru', '$id_unit_ps', '$jam_mulai', '$jam_selesai', '$total_bayar', '$nama_file_ttd', 'berjalan')";

    if (mysqli_query($koneksi, $query_transaksi)) {
        // Ubah status PS menjadi 'disewa' agar tidak bentrok
        mysqli_query($koneksi, "UPDATE unitps_hildan_2430511059 SET status = 'disewa' WHERE id = '$id_unit_ps'");

        echo "<script>
                alert('Transaksi Berhasil Disimpan!');
                window.location.href = '../dashboard.php';
              </script>";
    } else {
        echo "Gagal menyimpan transaksi: " . mysqli_error($koneksi);
    }

} else {
    header("Location: ../dashboard.php");
    exit;
}
?>