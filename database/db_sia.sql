-- =========================================================
-- DATABASE SIA - SAFE VERSION
-- Bisa dijalankan tanpa menghapus data yang sudah ada
-- MariaDB 10.4.32
-- =========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `db_sia`
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE `db_sia`;

START TRANSACTION;

-- =========================================================
-- TABEL PELANGGAN
-- =========================================================

CREATE TABLE IF NOT EXISTS `tbl_pelanggan` (
    `id_pelanggan` INT(11) NOT NULL AUTO_INCREMENT,
    `nama` VARCHAR(100) NOT NULL,
    `no_hp` VARCHAR(20) DEFAULT NULL,
    `alamat` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


-- =========================================================
-- TABEL PRODUK
-- =========================================================

CREATE TABLE IF NOT EXISTS `tbl_products` (
    `id_product` INT(11) NOT NULL AUTO_INCREMENT,
    `nama` VARCHAR(100) NOT NULL,
    `kategori` VARCHAR(100) NOT NULL,
    `harga` INT(11) NOT NULL DEFAULT 0,
    `stok` INT(11) NOT NULL DEFAULT 0,

    PRIMARY KEY (`id_product`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


-- =========================================================
-- TABEL TRANSAKSI
-- =========================================================

CREATE TABLE IF NOT EXISTS `tbl_transaction` (
    `id_transaction` INT(11) NOT NULL AUTO_INCREMENT,
    `id_pelanggan` INT(11) DEFAULT NULL,
    `tanggal` DATE NOT NULL,
    `total` INT(11) NOT NULL DEFAULT 0,

    PRIMARY KEY (`id_transaction`),

    KEY `idx_transaction_pelanggan` (`id_pelanggan`),

    CONSTRAINT `fk_transaction_pelanggan`
        FOREIGN KEY (`id_pelanggan`)
        REFERENCES `tbl_pelanggan` (`id_pelanggan`)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


-- =========================================================
-- DETAIL TRANSAKSI
-- =========================================================

CREATE TABLE IF NOT EXISTS `tbl_transaction_details` (
    `id_transaction_detail` INT(11) NOT NULL AUTO_INCREMENT,
    `id_product` INT(11) NOT NULL,
    `id_transaction` INT(11) NOT NULL,
    `harga` INT(11) NOT NULL DEFAULT 0,
    `qty` INT(11) NOT NULL DEFAULT 1,
    `subtotal` INT(11) NOT NULL DEFAULT 0,

    PRIMARY KEY (`id_transaction_detail`),

    KEY `idx_detail_product` (`id_product`),
    KEY `idx_detail_transaction` (`id_transaction`),

    CONSTRAINT `fk_detail_product`
        FOREIGN KEY (`id_product`)
        REFERENCES `tbl_products` (`id_product`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT `fk_detail_transaction`
        FOREIGN KEY (`id_transaction`)
        REFERENCES `tbl_transaction` (`id_transaction`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


-- =========================================================
-- TABEL USER
-- =========================================================

CREATE TABLE IF NOT EXISTS `tbl_user` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(20) NOT NULL DEFAULT 'user',

    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_username` (`username`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_user`
(`username`, `password`, `role`)
SELECT
    'admin',
    'admin123',
    'admin'
WHERE NOT EXISTS (
    SELECT 1
    FROM `tbl_user`
    WHERE `username` = 'admin'
);


-- =========================================================
-- DATA PRODUK CONTOH
-- Tidak akan dimasukkan ulang jika ID sudah ada
-- =========================================================

INSERT INTO `tbl_products`
(`id_product`, `nama`, `kategori`, `harga`, `stok`)
SELECT
    2,
    'Lemon Tea',
    'Minuman',
    10000,
    33
WHERE NOT EXISTS (
    SELECT 1
    FROM `tbl_products`
    WHERE `id_product` = 2
);


INSERT INTO `tbl_products`
(`id_product`, `nama`, `kategori`, `harga`, `stok`)
SELECT
    3,
    'Pecel Lele',
    'Makanan',
    6000,
    4
WHERE NOT EXISTS (
    SELECT 1
    FROM `tbl_products`
    WHERE `id_product` = 3
);


COMMIT;