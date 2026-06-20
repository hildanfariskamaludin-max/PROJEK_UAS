-- 1. Membuat Tabel Users (Untuk Fitur Login Admin/Kasir)
CREATE TABLE `users_hildan_2430511059` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL, 
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `role` ENUM('admin', 'kasir') DEFAULT 'kasir',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Membuat Tabel Unit PS (Untuk CRUD & Multiple Upload Foto Kondisi)
CREATE TABLE `unitps_hildan_2430511059` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nomor_seri` VARCHAR(50) NOT NULL UNIQUE,
  `jenis_ps` ENUM('PS3', 'PS4', 'PS5') NOT NULL,
  `harga_per_jam` INT NOT NULL,
  `status` ENUM('ready', 'disewa', 'maintenance') DEFAULT 'ready',
  `foto_kondisi` TEXT DEFAULT NULL -- Menyimpan nama-nama file foto (bisa format JSON atau string dipisah koma)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Membuat Tabel Pelanggan (Untuk Data Member)
CREATE TABLE `pelanggan_hildan_2430511059` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nik_ktp` VARCHAR(20) NOT NULL UNIQUE,
  `nama_pelanggan` VARCHAR(100) NOT NULL,
  `no_hp` VARCHAR(15) NOT NULL,
  `alamat` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Membuat Tabel Transaksi (Untuk Datatable, Konversi Data, Canvas TTD, dan Audio Alert)
CREATE TABLE `transaksi_hildan_2430511059` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kode_transaksi` VARCHAR(20) NOT NULL UNIQUE, 
  `id_pelanggan` INT NOT NULL,
  `id_unit_ps` INT NOT NULL,
  `jam_mulai` DATETIME NOT NULL,
  `jam_selesai` DATETIME NOT NULL,
  `total_bayar` INT NOT NULL,
  `ttd_digital` VARCHAR(255) DEFAULT NULL, -- Menyimpan nama file gambar hasil Canvas TTD (.png)
  `status_transaksi` ENUM('berjalan', 'selesai') DEFAULT 'berjalan',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan_hildan_2430511059`(`id`) ON UPDATE CASCADE,
  FOREIGN KEY (`id_unit_ps`) REFERENCES `unitps_hildan_2430511059`(`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `users_hildan_2430511059` (`username`, `password`, `nama_lengkap`, `role`) VALUES 
('admin', '$2y$10$mK7M9hV7mXg9EFAV6F6EFeYIomk86Cxlm1B31Y9g6/P2FmR4M3W2.', 'hildan', 'admin'); 

