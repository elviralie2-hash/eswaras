CREATE DATABASE IF NOT EXISTS `db_eswaras`;
USE `db_eswaras`;

-- Tabel Menu Kedai Es Waras
CREATE TABLE IF NOT EXISTS `tbl_menu` (
  `id_menu` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_menu` VARCHAR(100) NOT NULL,
  `kategori` VARCHAR(50) NOT NULL,
  `harga` INT NOT NULL,
  `stok` INT NOT NULL DEFAULT 10
);

INSERT INTO `tbl_menu` (`nama_menu`, `kategori`, `harga`, `stok`) VALUES
('Es Waras Original', 'Minuman Utama', 10000, 25),
('Es Waras Milk Tea', 'Minuman', 12000, 20),
('Es Jeruk Peras', 'Minuman', 8000, 30),
('Es Campur Premium', 'Dessert', 15000, 15);

-- Tabel Transaksi Penjualan
CREATE TABLE IF NOT EXISTS `tbl_transaksi` (
  `id_transaksi` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_pemesan` VARCHAR(100) NOT NULL,
  `total_bayar` INT NOT NULL,
  `tanggal` DATETIME DEFAULT CURRENT_TIMESTAMP
);