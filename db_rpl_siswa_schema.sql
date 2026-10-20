-- Database Schema for ci_siswa
-- Application: Data Siswa XI RPL (CodeIgniter 4)
-- Target Database: db_rpl_siswa

CREATE DATABASE IF NOT EXISTS `db_rpl_siswa` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `db_rpl_siswa`;

CREATE TABLE IF NOT EXISTS `siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
