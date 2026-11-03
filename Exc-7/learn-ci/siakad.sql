CREATE DATABASE IF NOT EXISTS `siakad_ci4` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `siakad_ci4`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `role` enum('admin','dosen','mahasiswa') NOT NULL DEFAULT 'admin',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`) VALUES
(1, 'admin', '$2y$12$QaZsipMKcqRgxOsgiMVaau.B4x4VASLCvhoyl/U2eV8G8RUrhzJti', 'Administrator Akademik', 'admin')
ON DUPLICATE KEY UPDATE `username`=`username`;

CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nim` (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `email`, `jurusan`, `jenis_kelamin`, `foto`) VALUES
(1, '220101001', 'Ahmad Fauzi', 'ahmad.fauzi@student.ac.id', 'Teknik Informatika', 'Laki-laki', NULL),
(2, '220101002', 'Siti Nurhaliza', 'siti.nur@student.ac.id', 'Sistem Informasi', 'Perempuan', NULL),
(3, '220101003', 'Budi Santoso', 'budi.santoso@student.ac.id', 'Teknik Komputer', 'Laki-laki', NULL)
ON DUPLICATE KEY UPDATE `nim`=`nim`;
