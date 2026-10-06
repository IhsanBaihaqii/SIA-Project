-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 06, 2026 at 01:21 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sia`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_akun`
--

CREATE TABLE `tbl_akun` (
  `id_akun` int NOT NULL,
  `kode_akun` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_akun` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tipe` enum('aset','kewajiban','ekuitas','pendapatan','beban') COLLATE utf8mb4_general_ci NOT NULL,
  `saldo_normal` enum('debit','kredit') COLLATE utf8mb4_general_ci NOT NULL,
  `is_kas` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_akun`
--

INSERT INTO `tbl_akun` (`id_akun`, `kode_akun`, `nama_akun`, `tipe`, `saldo_normal`, `is_kas`, `created_at`) VALUES
(1, '1-1000', 'Kas', 'aset', 'debit', 1, '2026-10-06 12:38:58'),
(2, '1-1100', 'Piutang Usaha', 'aset', 'debit', 0, '2026-10-06 12:38:58'),
(3, '1-1200', 'Perlengkapan', 'aset', 'debit', 0, '2026-10-06 12:38:58'),
(4, '1-1300', 'Persediaan Barang', 'aset', 'debit', 0, '2026-10-06 12:38:58'),
(5, '1-1400', 'Peralatan', 'aset', 'debit', 0, '2026-10-06 12:38:58'),
(6, '2-1000', 'Utang Usaha', 'kewajiban', 'kredit', 0, '2026-10-06 12:38:58'),
(7, '3-1000', 'Modal Pemilik', 'ekuitas', 'kredit', 0, '2026-10-06 12:38:58'),
(8, '4-1000', 'Pendapatan Penjualan', 'pendapatan', 'kredit', 0, '2026-10-06 12:38:58'),
(9, '5-1000', 'Harga Pokok Penjualan', 'beban', 'debit', 0, '2026-10-06 12:38:58'),
(10, '5-1100', 'Beban Listrik', 'beban', 'debit', 0, '2026-10-06 12:38:58'),
(11, '5-1200', 'Beban Gaji', 'beban', 'debit', 0, '2026-10-06 12:38:58');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_journal`
--

CREATE TABLE `tbl_journal` (
  `id_journal` int NOT NULL,
  `no_bukti` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_transaction` int DEFAULT NULL,
  `id_akun` int NOT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `debit` int NOT NULL DEFAULT '0',
  `kredit` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pelanggan`
--

CREATE TABLE `tbl_pelanggan` (
  `id_pelanggan` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `id_product` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` int NOT NULL DEFAULT '0',
  `harga_pokok` int NOT NULL DEFAULT '0',
  `stok` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transaction`
--

CREATE TABLE `tbl_transaction` (
  `id_transaction` int NOT NULL,
  `id_pelanggan` int DEFAULT NULL,
  `tanggal` date NOT NULL,
  `total` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transaction_details`
--

CREATE TABLE `tbl_transaction_details` (
  `id_transaction_detail` int NOT NULL,
  `id_product` int NOT NULL,
  `id_transaction` int NOT NULL,
  `harga` int NOT NULL DEFAULT '0',
  `qty` int NOT NULL DEFAULT '1',
  `subtotal` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_jurnal`
-- (See below for the actual view)
--
CREATE TABLE `v_jurnal` (
`debit` int
,`id_akun` int
,`id_journal` int
,`id_transaction` int
,`keterangan` varchar(255)
,`kode_akun` varchar(20)
,`kredit` int
,`nama_akun` varchar(100)
,`no_bukti` varchar(50)
,`saldo_normal` enum('debit','kredit')
,`tanggal` datetime
,`tipe` enum('aset','kewajiban','ekuitas','pendapatan','beban')
);

-- --------------------------------------------------------

--
-- Structure for view `v_jurnal`
--
DROP TABLE IF EXISTS `v_jurnal`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_jurnal`  AS SELECT `j`.`id_journal` AS `id_journal`, `j`.`no_bukti` AS `no_bukti`, `j`.`id_transaction` AS `id_transaction`, `j`.`tanggal` AS `tanggal`, `j`.`keterangan` AS `keterangan`, `j`.`debit` AS `debit`, `j`.`kredit` AS `kredit`, `a`.`id_akun` AS `id_akun`, `a`.`kode_akun` AS `kode_akun`, `a`.`nama_akun` AS `nama_akun`, `a`.`tipe` AS `tipe`, `a`.`saldo_normal` AS `saldo_normal` FROM (`tbl_journal` `j` join `tbl_akun` `a` on((`a`.`id_akun` = `j`.`id_akun`)))  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_akun`
--
ALTER TABLE `tbl_akun`
  ADD PRIMARY KEY (`id_akun`),
  ADD UNIQUE KEY `uk_kode_akun` (`kode_akun`);

--
-- Indexes for table `tbl_journal`
--
ALTER TABLE `tbl_journal`
  ADD PRIMARY KEY (`id_journal`),
  ADD KEY `idx_journal_transaction` (`id_transaction`),
  ADD KEY `idx_journal_akun` (`id_akun`),
  ADD KEY `idx_journal_tanggal` (`tanggal`),
  ADD KEY `idx_journal_no_bukti` (`no_bukti`);

--
-- Indexes for table `tbl_pelanggan`
--
ALTER TABLE `tbl_pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`id_product`);

--
-- Indexes for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD PRIMARY KEY (`id_transaction`),
  ADD KEY `idx_transaction_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `tbl_transaction_details`
--
ALTER TABLE `tbl_transaction_details`
  ADD PRIMARY KEY (`id_transaction_detail`),
  ADD KEY `idx_detail_product` (`id_product`),
  ADD KEY `idx_detail_transaction` (`id_transaction`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_akun`
--
ALTER TABLE `tbl_akun`
  MODIFY `id_akun` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_journal`
--
ALTER TABLE `tbl_journal`
  MODIFY `id_journal` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_pelanggan`
--
ALTER TABLE `tbl_pelanggan`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `id_product` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  MODIFY `id_transaction` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_transaction_details`
--
ALTER TABLE `tbl_transaction_details`
  MODIFY `id_transaction_detail` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_journal`
--
ALTER TABLE `tbl_journal`
  ADD CONSTRAINT `fk_journal_akun` FOREIGN KEY (`id_akun`) REFERENCES `tbl_akun` (`id_akun`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_journal_transaction` FOREIGN KEY (`id_transaction`) REFERENCES `tbl_transaction` (`id_transaction`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD CONSTRAINT `fk_transaction_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `tbl_pelanggan` (`id_pelanggan`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_transaction_details`
--
ALTER TABLE `tbl_transaction_details`
  ADD CONSTRAINT `fk_detail_product` FOREIGN KEY (`id_product`) REFERENCES `tbl_products` (`id_product`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_transaction` FOREIGN KEY (`id_transaction`) REFERENCES `tbl_transaction` (`id_transaction`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
