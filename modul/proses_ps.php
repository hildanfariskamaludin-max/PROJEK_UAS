<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config/koneksi.php';

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

if ($aksi == 'tambah') {
    $nomor_seri    = mysqli_real_escape_string($koneksi, $_POST['nomor_seri']);
    $jenis_ps      = mysqli_real_escape_string($koneksi, $_POST['jenis_ps']);
    $harga_per_jam = intval($_POST['harga_per_jam']);
    $status        = 'ready';

    $daftar_nama_foto = array();
    if (!empty($_FILES['foto_kondisi']['name'][0])) {
        $files = $_FILES['foto_kondisi'];
        foreach ($files['name'] as $key => $name) {
            $tmp_name = $files['tmp_name'][$key];
            if ($files['error'][$key] === 0) {
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $nama_file_baru = "foto_" . $nomor_seri . "_" . ($key + 1) . "_" . rand(10, 99) . "." . $ext;
                if (move_uploaded_file($tmp_name, "../uploads/foto_ps/" . $nama_file_baru)) {
                    $daftar_nama_foto[] = $nama_file_baru;
                }
            }
        }
    }
    $foto_string = implode(',', $daftar_nama_foto);

    $query_tambah = "INSERT INTO unit_ps (nomor_seri, jenis_ps, harga_per_jam, status, foto_kondisi) VALUES ('$nomor_seri', '$jenis_ps', '$harga_per_jam', '$status', '$foto_string')";
    if (mysqli_query($koneksi, $query_tambah)) {
        echo "<script>alert('Data Unit PS Berhasil Disimpan!'); window.location.href = '../data_ps.php';</script>";
    }
} 

// ================= FITUR EDIT DATA PS SAKTI =================
elseif ($aksi == 'edit') {
    $id            = intval($_POST['id']);
    $nomor_seri    = mysqli_real_escape_string($koneksi, $_POST['nomor_seri']);
    $jenis_ps      = mysqli_real_escape_string($koneksi, $_POST['jenis_ps']);
    $harga_per_jam = intval($_POST['harga_per_jam']);
    $status        = mysqli_real_escape_string($koneksi, $_POST['status']);

    // Cek apakah kasir mengupload foto baru
    if (!empty($_FILES['foto_kondisi']['name'][0])) {
        // 1. Hapus dulu berkas file foto fisik yang lama dari folder biar ga menumpuk nyampah di PC
        $query_lama = mysqli_query($koneksi, "SELECT foto_kondisi FROM unit_ps WHERE id = '$id'");
        $data_lama  = mysqli_fetch_assoc($query_lama);
        if (!empty($data_lama['foto_kondisi'])) {
            $array_foto_lama = explode(',', $data_lama['foto_kondisi']);
            foreach ($array_foto_lama as $foto_l) {
                $path_l = "../uploads/foto_ps/" . trim($foto_l);
                if (file_exists($path_l)) unlink($path_l);
            }
        }

        // 2. Upload file-file foto kondisi baru
        $daftar_nama_foto = array();
        $files = $_FILES['foto_kondisi'];
        foreach ($files['name'] as $key => $name) {
            $tmp_name = $files['tmp_name'][$key];
            if ($files['error'][$key] === 0) {
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $nama_file_baru = "foto_" . $nomor_seri . "_" . ($key + 1) . "_" . rand(10, 99) . "." . $ext;
                if (move_uploaded_file($tmp_name, "../uploads/foto_ps/" . $nama_file_baru)) {
                    $daftar_nama_foto[] = $nama_file_baru;
                }
            }
        }
        $foto_string = implode(',', $daftar_nama_foto);

        // Query update beserta nama berkas foto baru
        $query_update = "UPDATE unit_ps SET nomor_seri='$nomor_seri', jenis_ps='$jenis_ps', harga_per_jam='$harga_per_jam', status='$status', foto_kondisi='$foto_string' WHERE id='$id'";
    } else {
        // Query update jika kasir tidak ingin mengubah foto lama
        $query_update = "UPDATE unit_ps SET nomor_seri='$nomor_seri', jenis_ps='$jenis_ps', harga_per_jam='$harga_per_jam', status='$status' WHERE id='$id'";
    }

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data Unit PS Berhasil Diubah, Bang!'); window.location.href = '../data_ps.php';</script>";
    } else {
        echo "Gagal mengubah data: " . mysqli_error($koneksi);
    }
} 

elseif ($aksi == 'hapus') {
    $id = intval($_GET['id']);
    $query_foto = mysqli_query($koneksi, "SELECT foto_kondisi FROM unit_ps WHERE id = '$id'");
    $data_foto  = mysqli_fetch_assoc($query_foto);
    if (!empty($data_foto['foto_kondisi'])) {
        $array_foto = explode(',', $data_foto['foto_kondisi']);
        foreach ($array_foto as $foto) {
            $path_file = "../uploads/foto_ps/" . trim($foto);
            if (file_exists($path_file)) unlink($path_file);
        }
    }
    $query_hapus = "DELETE FROM unit_ps WHERE id = '$id'";
    if (mysqli_query($koneksi, $query_hapus)) {
        echo "<script>alert('Data Unit PS Berhasil Dihapus!'); window.location.href = '../data_ps.php';</script>";
    }
}
?>