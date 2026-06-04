CREATE DATABASE IF NOT EXISTS `pos_showroom`;
USE `pos_showroom`;

-- --------------------------------------------------------
-- Table structure for table `user`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','owner') NOT NULL DEFAULT 'admin',
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Password for admin is md5('admin123') = 0192023a7bbd73250516f069df18b500
-- Password for owner is md5('owner123') = 72122ce96bfec66e2396d2e25225d70a
INSERT INTO `user` (`id_user`, `username`, `password`, `role`, `nama_lengkap`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin', 'Administrator'),
(2, 'owner', '72122ce96bfec66e2396d2e25225d70a', 'owner', 'Owner Dealer')
ON DUPLICATE KEY UPDATE `id_user`=`id_user`;

-- --------------------------------------------------------
-- Table structure for table `customer`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customer` (
  `id_customer` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `alamat` TEXT,
  `no_telp` VARCHAR(20) NOT NULL,
  `no_ktp` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100),
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `supplier`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `supplier` (
  `id_supplier` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_supplier` VARCHAR(100) NOT NULL,
  `alamat` TEXT,
  `no_telp` VARCHAR(20),
  `email` VARCHAR(100),
  `keterangan` TEXT,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `mobil`
-- status_stok: tersedia = stok ada & bisa dipesan
--              booking  = sedang dalam proses pemesanan
--              terjual  = sudah diserahkan ke customer
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mobil` (
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
  `harga_beli` DECIMAL(15,2) NOT NULL,
  `harga_jual` DECIMAL(15,2) NOT NULL,
  `status_stok` ENUM('tersedia','booking','terjual') DEFAULT 'tersedia',
  `status_bpkb` VARCHAR(50),
  `status_mobil` VARCHAR(50),
  `stok` INT(11) DEFAULT 1,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mobil`),
  FOREIGN KEY (`id_supplier`) REFERENCES `supplier`(`id_supplier`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `pembelian`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pembelian` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `pembayaran_pembelian`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pembayaran_pembelian` (
  `id_pembayaran` INT(11) NOT NULL AUTO_INCREMENT,
  `id_pembelian` INT(11) NOT NULL,
  `jenis_pembayaran` ENUM('tunai','transfer') NOT NULL,
  `metode_pembayaran` VARCHAR(100),
  `tgl_bayar` DATE NOT NULL,
  `jumlah_bayar` DECIMAL(15,2) NOT NULL,
  `bukti_transfer` VARCHAR(255),
  `status_verifikasi` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembayaran`),
  FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian`(`id_pembelian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `pemesanan`
-- harga_jual_snapshot: snapshot harga jual saat pemesanan dibuat
--   agar perubahan harga di master tidak mempengaruhi transaksi berjalan
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pemesanan` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `penjualan`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `penjualan` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `pembayaran_penjualan`
-- jenis_pembayaran: tahap pembayaran (tanda_jadi, dp, pelunasan)
-- metode_pembayaran: cara bayar (tunai, transfer)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pembayaran_penjualan` (
  `id_pembayaran` INT(11) NOT NULL AUTO_INCREMENT,
  `id_pemesanan` INT(11) NOT NULL,
  `id_penjualan` INT(11) DEFAULT NULL,
  `jenis_pembayaran` ENUM('tanda_jadi','dp','pelunasan') NOT NULL,
  `metode_pembayaran` ENUM('tunai','transfer') NOT NULL,
  `tgl_bayar` DATE NOT NULL,
  `jumlah_bayar` DECIMAL(15,2) NOT NULL,
  `bukti_transfer` VARCHAR(255),
  `bukti_ktp` VARCHAR(255) COMMENT 'Upload fotokopi KTP untuk DP dan Pelunasan',
  `status_pemesanan` VARCHAR(50),
  `status_verifikasi` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembayaran`),
  FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan`(`id_pemesanan`),
  FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan`(`id_penjualan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `penyerahan_mobil`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `penyerahan_mobil` (
  `id_penyerahan` INT(11) NOT NULL AUTO_INCREMENT,
  `id_penjualan` INT(11) NOT NULL,
  `tgl_serah_unit` DATE,
  `tgl_serah_bpkb` DATE,
  `metode_serah` ENUM('ambil_sendiri','diantar') NOT NULL,
  `nama_penerima` VARCHAR(100) NOT NULL,
  `alamat_tujuan` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_penyerahan`),
  UNIQUE KEY `unique_penjualan` (`id_penjualan`),
  FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan`(`id_penjualan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `laporan`
-- Mencatat log setiap kali laporan digenerate
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `laporan` (
  `id_laporan` INT(11) NOT NULL AUTO_INCREMENT,
  `jenis_laporan` VARCHAR(50) NOT NULL,
  `periode_awal` DATE,
  `periode_akhir` DATE,
  `id_user` INT(11),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_laporan`),
  FOREIGN KEY (`id_user`) REFERENCES `user`(`id_user`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Migration: Tambah kolom baru jika tabel sudah ada (untuk upgrade)
-- --------------------------------------------------------

-- Tambah harga_jual_snapshot ke tabel pemesanan jika belum ada
ALTER TABLE `pemesanan` 
  ADD COLUMN IF NOT EXISTS `harga_jual_snapshot` DECIMAL(15,2) NOT NULL DEFAULT 0 COMMENT 'Snapshot harga jual saat order dibuat' AFTER `tgl_pesan`,
  ADD COLUMN IF NOT EXISTS `alasan_batal` TEXT NULL COMMENT 'Diisi saat pembatalan' AFTER `status_pemesanan`;

-- Tambah status terjual ke ENUM status_stok
ALTER TABLE `mobil` MODIFY COLUMN `status_stok` ENUM('tersedia','booking','terjual') DEFAULT 'tersedia';

-- Tambah kolom bukti_ktp ke tabel pembayaran_penjualan jika belum ada
ALTER TABLE `pembayaran_penjualan`
  ADD COLUMN IF NOT EXISTS `bukti_ktp` VARCHAR(255) NULL COMMENT 'Upload fotokopi KTP untuk DP dan Pelunasan' AFTER `bukti_transfer`;

-- Tambah UNIQUE constraint untuk mencegah duplikasi penyerahan
ALTER TABLE `penyerahan_mobil` 
  ADD UNIQUE INDEX IF NOT EXISTS `unique_penjualan` (`id_penjualan`);

-- Tambah UNIQUE constraint untuk mencegah duplikasi penjualan per pemesanan
ALTER TABLE `penjualan`
  ADD UNIQUE INDEX IF NOT EXISTS `unique_pemesanan` (`id_pemesanan`);

-- Tambah kolom status_berkas default jika belum ada
ALTER TABLE `penjualan` MODIFY COLUMN `status_berkas` VARCHAR(100) DEFAULT 'menunggu';
