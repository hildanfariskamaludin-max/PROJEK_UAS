-- 1. Membuat Tabel Users (Untuk Fitur Login Admin/Kasir)
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL, -- Menyimpan password yang sudah di-hash (bcrypt/password_hash)
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `role` ENUM('admin', 'kasir') DEFAULT 'kasir',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Membuat Tabel Unit PS (Untuk CRUD & Multiple Upload Foto Kondisi)
CREATE TABLE `unit_ps` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nomor_seri` VARCHAR(50) NOT NULL UNIQUE,
  `jenis_ps` ENUM('PS3', 'PS4', 'PS5') NOT NULL,
  `harga_per_jam` INT NOT NULL,
  `status` ENUM('ready', 'disewa', 'maintenance') DEFAULT 'ready',
  `foto_kondisi` TEXT DEFAULT NULL -- Menyimpan nama-nama file foto (bisa format JSON atau string dipisah koma)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Membuat Tabel Pelanggan (Untuk Data Member)
CREATE TABLE `pelanggan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nik_ktp` VARCHAR(20) NOT NULL UNIQUE,
  `nama_pelanggan` VARCHAR(100) NOT NULL,
  `no_hp` VARCHAR(15) NOT NULL,
  `alamat` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Membuat Tabel Transaksi (Untuk Datatable, Konversi Data, Canvas TTD, dan Audio Alert)
CREATE TABLE `transaksi` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kode_transaksi` VARCHAR(20) NOT NULL UNIQUE, -- Contoh: TRX-20260614-001
  `id_pelanggan` INT NOT NULL,
  `id_unit_ps` INT NOT NULL,
  `jam_mulai` DATETIME NOT NULL,
  `jam_selesai` DATETIME NOT NULL,
  `total_bayar` INT NOT NULL,
  `ttd_digital` VARCHAR(255) DEFAULT NULL, -- Menyimpan nama file gambar hasil Canvas TTD (.png)
  `status_transaksi` ENUM('berjalan', 'selesai') DEFAULT 'berjalan',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan`(`id`) ON UPDATE CASCADE,
  FOREIGN KEY (`id_unit_ps`) REFERENCES `unit_ps`(`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `role`) VALUES 
('admin', '$2y$10$mK7M9hV7mXg9EFAV6F6EFeYIomk86Cxlm1B31Y9g6/P2FmR4M3W2.', 'Nurrifanti H', 'admin'); 


INSERT INTO `unit_ps` (`nomor_seri`, `jenis_ps`, `harga_per_jam`, `status`) VALUES
('PS3-001', 'PS3', 5000, 'ready'),
('PS4-002', 'PS4', 8000, 'ready'),
('PS5-003', 'PS5', 15000, 'ready');