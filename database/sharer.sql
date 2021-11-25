-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 14 Agu 2019 pada 15.51
-- Versi server: 5.7.26-cll-lve
-- Versi PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nextdriv_sharer`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_broken`
--

CREATE TABLE `tb_broken` (
  `id` varchar(7) CHARACTER SET latin1 NOT NULL,
  `file_owner_mail` varchar(100) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('1','2') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--


--
-- Struktur dari tabel `tb_file`
--

CREATE TABLE `tb_file` (
  `id` varchar(10) NOT NULL,
  `file_id` varchar(255) NOT NULL,
  `file_name` text NOT NULL,
  `file_owner` varchar(255) NOT NULL,
  `file_owner_mail` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` double NOT NULL,
  `created_date` datetime NOT NULL,
  `downloads` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--


--
-- Struktur dari tabel `tb_lastdls`
--

CREATE TABLE `tb_lastdls` (
  `id` varchar(7) CHARACTER SET latin1 NOT NULL,
  `file_name` text CHARACTER SET latin1 NOT NULL,
  `user` varchar(100) CHARACTER SET latin1 NOT NULL,
  `download_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--


--
-- Struktur dari tabel `tb_mirrors`
--

CREATE TABLE `tb_mirrors` (
  `id` bigint(20) NOT NULL,
  `file_id` varchar(50) NOT NULL,
  `hoster` varchar(30) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--


--
-- Struktur dari tabel `tb_options`
--

CREATE TABLE `tb_options` (
  `id` int(11) NOT NULL,
  `option_name` varchar(255) NOT NULL,
  `option_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--


--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 NOT NULL,
  `protect_file` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `join_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_broken`
--
ALTER TABLE `tb_broken`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_file`
--
ALTER TABLE `tb_file`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `FILE_ID` (`file_id`);

--
-- Indeks untuk tabel `tb_lastdls`
--
ALTER TABLE `tb_lastdls`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_mirrors`
--
ALTER TABLE `tb_mirrors`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_options`
--
ALTER TABLE `tb_options`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_mirrors`
--
ALTER TABLE `tb_mirrors`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT untuk tabel `tb_options`
--
ALTER TABLE `tb_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
