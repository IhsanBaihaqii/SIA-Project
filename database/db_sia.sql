-- =========================================================
-- DATABASE SIA
-- MariaDB / MySQL
-- =========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

START TRANSACTION;

SET NAMES utf8mb4;

-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS `db_sia`
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE `db_sia`;

-- =========================================================
-- HAPUS TABEL LAMA
-- Urutan penting karena ada foreign key
-- =========================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `tbl_transaction_details`;
DROP TABLE IF EXISTS `tbl_transaction`;
DROP TABLE IF EXISTS `tbl_products`;
DROP TABLE IF EXISTS `tbl_user`;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================
-- TABEL PRODUK
-- =========================================================

CREATE TABLE `tbl_products` (
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

CREATE TABLE `tbl_transaction` (
    `id_transaction` INT(11) NOT NULL AUTO_INCREMENT,
    `tanggal` DATE NOT NULL,
    `total` INT(11) NOT NULL DEFAULT 0,

    PRIMARY KEY (`id_transaction`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- =========================================================
-- DETAIL TRANSAKSI
-- =========================================================

CREATE TABLE `tbl_transaction_details` (
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
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- =========================================================
-- TABEL USER
-- =========================================================

CREATE TABLE `tbl_user` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(20) NOT NULL DEFAULT 'user',

    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_username` (`username`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- =========================================================
-- USER DEFAULT
-- =========================================================

INSERT INTO `tbl_user`
    (`username`, `password`, `role`)
VALUES
    ('admin', 'admin123', 'admin');

COMMIT;