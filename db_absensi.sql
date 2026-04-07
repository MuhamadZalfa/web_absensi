-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Des 2025 pada 13.42
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_absensi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int(11) NOT NULL,
  `id_karyawan` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `status` enum('Hadir', 'Telat') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_karyawan`, `tanggal`, `jam_masuk`, `jam_pulang`, `status`) VALUES
(27, 3, '2025-12-09', '01:12:28', '01:13:46', 'Hadir, Telat'),
(28, 2, '2025-12-09', '01:12:55', '14:36:38', 'Hadir, Telat'),
(29, 2, '2025-12-09', '13:48:31', '14:36:38', 'Hadir, Telat'),
(30, 4, '2025-12-09', '14:20:21', '14:36:06', 'Hadir, Telat'),
(31, 1, '2025-12-09', NULL, '14:34:49', 'Hadir, Telat');

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `informasi_kontak` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nama`, `username`, `password`, `role`, `informasi_kontak`) VALUES
(1, 'Admin', 'admin', '$2y$10$ewjOwkpkiwAxHBCeL.gL/.gAW2Kq59ny0gglbexYYFmIF6ZAuJct6', 'admin', '081320534004'),
(2, 'Muhamad Zalfa', 'muhamadzalfa77', '$2y$10$cpr9EjNORw0X25zTtGUdiuD4xvXHF8FZphlzl.6iNNCNd/GwvNhKG', 'karyawan', '081333444555'),
(3, 'Zaki Lukmanul Hakim', 'zaki77', '$2y$10$ewjOwkpkiwAxHBCeL.gL/.gAW2Kq59ny0gglbexYYFmIF6ZAuJct6', 'karyawan', '08122233222'),
(4, 'Rafly Dap', 'kiply77', '$2y$10$U4FfUmvSXBiLXU1C14Xtn.m6Dqgo5gyGKh7rpKOpgDP4bY5h33kq.', 'karyawan', ''),
(7, 'Kalani Ahmad', 'kalani77', '$2y$10$gHV6Y692BBxDwc9FREXnoejkyCUuQQzpBKZcloZ3gS2ADcp3LY1OC', 'karyawan', ''),
(8, 'Ilham Nugraha', 'iam77', '$2y$10$KK2URUWznzXcegZhP69xkuG4kEvAWGKVojbeG3ko7UQXp2yh//uFO', 'karyawan', ''),
(9, 'Daffa Erlangga', 'daffa77', '$2y$10$4YY7d7skUGqelXBWoUdIceASAze4YY9UoOyJUvSZDkNNc8ctfEJpu', 'karyawan', '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
