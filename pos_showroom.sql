-- ============================================================
-- DATABASE: pos_showroom
-- Sistem Informasi Jual Beli Mobil — Showroom POS
-- 
-- CARA IMPORT:
--   1. Buka phpMyAdmin
--   2. Klik tab "Import"
--   3. Pilih file ini (pos_showroom.sql)
--   4. Klik "Go" / "Impor"
--
-- AKUN LOGIN SISTEM:
--   Admin  → username: admin  | password: admin123
--   Owner  → username: owner  | password: owner123
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+07:00";

-- ============================================================
-- Buat & gunakan database
-- ============================================================
CREATE DATABASE IF NOT EXISTS `pos_showroom`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `pos_showroom`;

-- ============================================================
-- Hapus tabel lama (urutan terbalik untuk hindari FK error)
-- ============================================================
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `laporan`;
DROP TABLE IF EXISTS `penyerahan_mobil`;
DROP TABLE IF EXISTS `pembayaran_penjualan`;
DROP TABLE IF EXISTS `penjualan`;
DROP TABLE IF EXISTS `pemesanan`;
DROP TABLE IF EXISTS `pembayaran_pembelian`;
DROP TABLE IF EXISTS `pembelian`;
DROP TABLE IF EXISTS `mobil`;
DROP TABLE IF EXISTS `supplier`;
DROP TABLE IF EXISTS `customer`;
DROP TABLE IF EXISTS `user`;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- Tabel: user
-- ============================================================
CREATE TABLE `user` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','owner') NOT NULL DEFAULT 'admin',
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data user:
--   admin → password: admin123  (MD5: 0192023a7bbd73250516f069df18b500)
--   owner → password: owner123  (MD5: 5be057accb25758101fa5eadbbd79503)
INSERT INTO `user` (`id_user`, `username`, `password`, `role`, `nama_lengkap`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin', 'Administrator'),
(2, 'owner', '5be057accb25758101fa5eadbbd79503', 'owner', 'Owner Dealer');

-- ============================================================
-- Tabel: customer
-- ============================================================
CREATE TABLE `customer` (
  `id_customer` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `alamat` TEXT,
  `no_telp` VARCHAR(20) NOT NULL,
  `no_ktp` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100),
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `customer` (`id_customer`, `nama`, `alamat`, `no_telp`, `no_ktp`, `email`) VALUES
(1, 'Budi Santoso', 'Jl. Merdeka No. 12, Jakarta Selatan', '081234567890', '3174012345670001', 'budi.santoso@email.com'),
(2, 'Siti Rahayu', 'Jl. Sudirman No. 45, Bandung', '082345678901', '3273056789012345', 'siti.rahayu@email.com'),
(3, 'Ahmad Fauzi', 'Jl. Diponegoro No. 8, Surabaya', '083456789012', '3578091234567890', 'ahmad.fauzi@email.com');

-- ============================================================
-- Tabel: supplier
-- ============================================================
CREATE TABLE `supplier` (
  `id_supplier` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_supplier` VARCHAR(100) NOT NULL,
  `alamat` TEXT,
  `no_telp` VARCHAR(20),
  `email` VARCHAR(100),
  `keterangan` TEXT,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `alamat`, `no_telp`, `email`, `keterangan`) VALUES
(1, 'PT. Toyota Auto Indonesia', 'Jl. Yos Sudarso, Sunter, Jakarta Utara', '02143456789', 'info@toyotaindonesia.co.id', 'Distributor resmi Toyota'),
(2, 'CV. Honda Motorindo', 'Jl. Gatot Subroto No. 10, Jaksel', '02187654321', 'sales@hondamotorindo.com', 'Distributor Honda mobil'),
(3, 'PT. Mitsubishi Dealer Jaya', 'Jl. Ahmad Yani No. 55, Bekasi', '02198765432', 'contact@mitsubishijaya.co.id', 'Dealer Mitsubishi resmi');

-- ============================================================
-- Tabel: mobil
-- ============================================================
CREATE TABLE `mobil` (
  `id_mobil` INT(11) NOT NULL AUTO_INCREMENT,
  `id_supplier` INT(11) NOT NULL,
  `nama_mobil` VARCHAR(100) NOT NULL,
  `merek` VARCHAR(50) NOT NULL,
  `warna` VARCHAR(30),
  `tipe` VARCHAR(50),
  `tahun` INT(4),
  `no_polisi` VARCHAR(15),
  `no_rangka` VARCHAR(50),
  `no_mesin` VARCHAR(50),
  `harga_beli` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `harga_jual` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `status_stok` ENUM('tersedia','booking','terjual') DEFAULT 'tersedia',
  `status_bpkb` VARCHAR(50),
  `status_mobil` VARCHAR(50),
  `stok` INT(11) DEFAULT 1,
  `foto_mobil` VARCHAR(255) DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mobil`),
  FOREIGN KEY (`id_supplier`) REFERENCES `supplier`(`id_supplier`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `mobil` (`id_mobil`, `id_supplier`, `nama_mobil`, `merek`, `warna`, `tipe`, `tahun`, `no_polisi`, `no_rangka`, `no_mesin`, `harga_beli`, `harga_jual`, `status_stok`, `status_bpkb`, `status_mobil`, `stok`) VALUES
(1, 1, 'Toyota Avanza', 'Toyota', 'Putih', 'G MT', 2023, 'B 1234 ABC', 'MHFM5FB1XN0123456', '1NR-B123456', 180000000, 210000000, 'tersedia', 'ada', 'bekas', 1),
(2, 1, 'Toyota Innova Reborn', 'Toyota', 'Silver', 'V AT', 2022, 'B 5678 DEF', 'MHFM8GB2XM0234567', '2GD-B234567', 285000000, 325000000, 'tersedia', 'ada', 'bekas', 1),
(3, 2, 'Honda Brio', 'Honda', 'Merah', 'RS CVT', 2024, 'B 9012 GHI', 'MHRDD1860P0345678', 'L13Z-C345678', 155000000, 185000000, 'tersedia', 'ada', 'baru', 1),
(4, 2, 'Honda HRV', 'Honda', 'Hitam', '1.5 Turbo RS', 2023, 'D 3456 JKL', 'MHRRE2870N0456789', 'L15B-D456789', 330000000, 375000000, 'tersedia', 'ada', 'baru', 1),
(5, 3, 'Mitsubishi Pajero Sport', 'Mitsubishi', 'Putih', 'Dakar 4x2 AT', 2022, 'B 7890 MNO', 'MMBJNKB40N0567890', '4N15-E567890', 450000000, 510000000, 'tersedia', 'ada', 'bekas', 1);

-- ============================================================
-- Tabel: pembelian
-- ============================================================
CREATE TABLE `pembelian` (
  `id_pembelian` INT(11) NOT NULL AUTO_INCREMENT,
  `id_supplier` INT(11) NOT NULL,
  `id_mobil` INT(11) NOT NULL,
  `id_user` INT(11) NOT NULL,
  `tgl_pembelian` DATE NOT NULL,
  `harga_beli_beli` DECIMAL(15,2) NOT NULL,
  `status_pembayaran` ENUM('menunggu','selesai') DEFAULT 'menunggu',
  `keterangan_kondisi` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembelian`),
  FOREIGN KEY (`id_supplier`) REFERENCES `supplier`(`id_supplier`),
  FOREIGN KEY (`id_mobil`) REFERENCES `mobil`(`id_mobil`),
  FOREIGN KEY (`id_user`) REFERENCES `user`(`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pembelian` (`id_pembelian`, `id_supplier`, `id_mobil`, `id_user`, `tgl_pembelian`, `harga_beli_beli`, `status_pembayaran`, `keterangan_kondisi`) VALUES
(1, 1, 1, 1, '2026-05-10', 180000000.00, 'selesai', 'Kondisi baik, body mulus'),
(2, 1, 2, 1, '2026-05-15', 285000000.00, 'selesai', 'Mesin bagus, AC dingin'),
(3, 2, 3, 1, '2026-05-20', 155000000.00, 'selesai', 'Unit baru dari dealer'),
(4, 2, 4, 1, '2026-05-25', 330000000.00, 'menunggu', 'Unit baru, turbo normal'),
(5, 3, 5, 1, '2026-06-01', 450000000.00, 'menunggu', 'Bekas lelang, kondisi 85%');

-- ============================================================
-- Tabel: pembayaran_pembelian
-- ============================================================
CREATE TABLE `pembayaran_pembelian` (
  `id_pembayaran` INT(11) NOT NULL AUTO_INCREMENT,
  `id_pembelian` INT(11) NOT NULL,
  `jenis_pembayaran` ENUM('tunai','transfer') NOT NULL,
  `metode_pembayaran` VARCHAR(100),
  `tgl_bayar` DATE NOT NULL,
  `jumlah_bayar` DECIMAL(15,2) NOT NULL,
  `bukti_transfer` VARCHAR(255),
  `status_verifikasi` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembayaran`),
  FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian`(`id_pembelian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pembayaran_pembelian` (`id_pembayaran`, `id_pembelian`, `jenis_pembayaran`, `metode_pembayaran`, `tgl_bayar`, `jumlah_bayar`, `status_verifikasi`) VALUES
(1, 1, 'transfer', 'BCA', '2026-05-11', 180000000.00, 1),
(2, 2, 'tunai', 'Cash', '2026-05-16', 285000000.00, 1),
(3, 3, 'transfer', 'Mandiri', '2026-05-21', 155000000.00, 1);

-- ============================================================
-- Tabel: pemesanan
-- ============================================================
CREATE TABLE `pemesanan` (
  `id_pemesanan` INT(11) NOT NULL AUTO_INCREMENT,
  `id_customer` INT(11) NOT NULL,
  `id_mobil` INT(11) NOT NULL,
  `tgl_pesan` DATE NOT NULL,
  `harga_jual_snapshot` DECIMAL(15,2) NOT NULL COMMENT 'Snapshot harga jual saat order dibuat',
  `harga_dp` DECIMAL(15,2) NOT NULL,
  `nilai_tanda_jadi` DECIMAL(15,2) DEFAULT 500000,
  `dp_minimal` DECIMAL(15,2) NOT NULL,
  `tgl_jatuh_tempo` DATE NOT NULL,
  `status_pemesanan` ENUM('menunggu','bukti_pesanan','dp','batal','hangus') DEFAULT 'menunggu',
  `alasan_batal` TEXT NULL COMMENT 'Diisi saat pembatalan',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pemesanan`),
  FOREIGN KEY (`id_customer`) REFERENCES `customer`(`id_customer`),
  FOREIGN KEY (`id_mobil`) REFERENCES `mobil`(`id_mobil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pemesanan` (`id_pemesanan`, `id_customer`, `id_mobil`, `tgl_pesan`, `harga_jual_snapshot`, `harga_dp`, `nilai_tanda_jadi`, `dp_minimal`, `tgl_jatuh_tempo`, `status_pemesanan`) VALUES
(1, 1, 1, '2026-01-10', 185000000.00, 55500000.00, 500000, 55500000.00, '2026-01-17', 'dp'),
(2, 1, 2, '2026-02-10', 325000000.00, 97500000.00, 500000, 97500000.00, '2026-02-17', 'dp'),
(3, 1, 3, '2026-03-10', 210000000.00, 63000000.00, 500000, 63000000.00, '2026-03-17', 'dp'),
(4, 2, 4, '2026-04-10', 375000000.00, 112500000.00, 500000, 112500000.00, '2026-04-17', 'dp'),
(5, 2, 5, '2026-05-10', 510000000.00, 153000000.00, 500000, 153000000.00, '2026-05-17', 'dp'),
(6, 3, 1, '2026-05-25', 185000000.00, 55500000.00, 500000, 55500000.00, '2026-06-01', 'bukti_pesanan'),
(7, 3, 2, '2026-06-01', 325000000.00, 97500000.00, 500000, 97500000.00, '2026-06-08', 'menunggu');

-- ============================================================
-- Tabel: penjualan
-- (otomatis terisi setelah DP dibayar)
-- ============================================================
CREATE TABLE `penjualan` (
  `id_penjualan` INT(11) NOT NULL AUTO_INCREMENT,
  `id_pemesanan` INT(11) NOT NULL,
  `tgl_penjualan` DATE NOT NULL,
  `total_bayaran` DECIMAL(15,2) NOT NULL,
  `status_pelunasan` ENUM('belum_lunas','lunas') DEFAULT 'belum_lunas',
  `status_berkas` VARCHAR(100) DEFAULT 'menunggu',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_penjualan`),
  UNIQUE KEY `unique_pemesanan` (`id_pemesanan`),
  FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan`(`id_pemesanan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `penjualan` (`id_penjualan`, `id_pemesanan`, `tgl_penjualan`, `total_bayaran`, `status_pelunasan`, `status_berkas`) VALUES
(1, 1, '2026-01-15', 185000000.00, 'lunas', 'diserahkan'),
(2, 2, '2026-02-15', 325000000.00, 'lunas', 'diserahkan'),
(3, 3, '2026-03-15', 210000000.00, 'lunas', 'diserahkan'),
(4, 4, '2026-04-15', 375000000.00, 'lunas', 'diserahkan'),
(5, 5, '2026-05-15', 510000000.00, 'lunas', 'diserahkan');

-- ============================================================
-- Tabel: pembayaran_penjualan
-- ============================================================
CREATE TABLE `pembayaran_penjualan` (
  `id_pembayaran` INT(11) NOT NULL AUTO_INCREMENT,
  `id_pemesanan` INT(11) NOT NULL,
  `id_penjualan` INT(11) DEFAULT NULL,
  `jenis_pembayaran` ENUM('tanda_jadi','dp','pelunasan','refund') NOT NULL,
  `metode_pembayaran` ENUM('tunai','transfer') NOT NULL,
  `tgl_bayar` DATE NOT NULL,
  `jumlah_bayar` DECIMAL(15,2) NOT NULL,
  `bukti_transfer` VARCHAR(255),
  `bukti_ktp` VARCHAR(255) COMMENT 'Upload fotokopi KTP untuk DP dan Pelunasan',
  `status_pemesanan` VARCHAR(50),
  `status_verifikasi` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembayaran`),
  FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan`(`id_pemesanan`),
  FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan`(`id_penjualan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pembayaran_penjualan` (`id_pembayaran`, `id_pemesanan`, `id_penjualan`, `jenis_pembayaran`, `metode_pembayaran`, `tgl_bayar`, `jumlah_bayar`, `status_pemesanan`, `status_verifikasi`) VALUES
(1, 1, NULL, 'tanda_jadi', 'tunai', '2026-05-20', 500000.00, 'menunggu', 1),
(2, 1, 1, 'dp', 'transfer', '2026-05-21', 63000000.00, 'bukti_pesanan', 1),
(3, 2, NULL, 'tanda_jadi', 'tunai', '2026-05-25', 500000.00, 'menunggu', 1);

-- ============================================================
-- Tabel: penyerahan_mobil
-- ============================================================
CREATE TABLE `penyerahan_mobil` (
  `id_penyerahan` INT(11) NOT NULL AUTO_INCREMENT,
  `id_penjualan` INT(11) NOT NULL,
  `tgl_serah_unit` DATE,
  `tgl_serah_bpkb` DATE,
  `metode_serah` ENUM('ambil_sendiri','diantar') NOT NULL,
  `nama_penerima` VARCHAR(100) NOT NULL,
  `alamat_tujuan` TEXT,
  `status_penyerahan` ENUM('dalam_proses','selesai') NOT NULL DEFAULT 'dalam_proses',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_penyerahan`),
  UNIQUE KEY `unique_penjualan` (`id_penjualan`),
  FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan`(`id_penjualan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- (belum ada data penyerahan karena penjualan masih belum_lunas)

-- ============================================================
-- Tabel: laporan
-- ============================================================
CREATE TABLE `laporan` (
  `id_laporan` INT(11) NOT NULL AUTO_INCREMENT,
  `jenis_laporan` VARCHAR(50) NOT NULL,
  `periode_awal` DATE,
  `periode_akhir` DATE,
  `id_user` INT(11),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_laporan`),
  FOREIGN KEY (`id_user`) REFERENCES `user`(`id_user`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Selesai
-- ============================================================
COMMIT;
