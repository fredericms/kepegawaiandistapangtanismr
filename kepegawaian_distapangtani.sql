-- Instalasi baru pada database KOSONG. Berisi 129 pegawai asli dan 1 superadmin.
-- Untuk database asli yang sudah terpasang, gunakan migrasi.sql saja.
CREATE DATABASE IF NOT EXISTS `kepegawaian_distapangtani` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kepegawaian_distapangtani`;

-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 22, 2026 at 09:08 AM
-- Server version: 10.11.11-MariaDB
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kepegawaian_distapangtani`
--

-- --------------------------------------------------------

--
-- Table structure for table `pegawai_distapangtani`
--

CREATE TABLE `pegawai_distapangtani` (
  `no` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `status_kepegawaian` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `pegawai_distapangtani`
--

INSERT INTO `pegawai_distapangtani` (`no`, `nama`, `nip`, `jabatan`, `status_kepegawaian`) VALUES
(1, 'Drs. H. Muhammad Darham, M.Si', '196611191986031007', 'Kepala Dinas Ketahanan Pangan Dan Pertanian', 'PNS'),
(2, 'Hj. Dwi Rahmi Adiaty, S.P., M.Si', '197203291998032007', 'Sekretaris Dinas Ketahanan Pangan dan Pertanian', 'PNS'),
(3, 'Abdul Jalil, S.Ag., M.A.P', '196812312006041078', 'Kepala Bidang Sarana dan Prasarana', 'PNS'),
(4, 'Maskuri, S.P., M.M', '196910141999021001', 'Kabid Petemakan dan Kesehatan Hewan', 'PNS'),
(5, 'Hj. Nurul Hudayanty, S.P., M.Si', '197004111995032006', 'Kabid Konsumsi dan Keamanan Pangan', 'PNS'),
(6, 'Aji Syarifah Zulaiha, S.P', '197009061998032005', 'Kabid Ketersediaan dan Distribusi Pangan', 'PNS'),
(7, 'Hary Winarto, S.P', '197704042007011017', 'Kabid Penyuluhan', 'PNS'),
(8, 'Alpiani Arieph, S.E', '196906241989031003', 'Kasubag Umum dan Kepegawaian', 'PNS'),
(9, 'drh. Kartika Hatmisari', '197603082003122009', 'Kepala UPTD Balai Keswan & Kesmavet', 'PNS'),
(10, 'drh. Kusdiyanto', '197709072005021005', 'Kasubag. Tata Usaha UPTD Balai Keswan & Kesmavet', 'PNS'),
(11, 'Tukimin', '196809012010011001', 'Operator Layanan Operasional', 'PNS'),
(12, 'H. Didi Subroto, S.P', '196809131998031008', 'Analis Pasar Hasil Pertranian Muda (Penyetaraan)', 'PNS'),
(13, 'Ir. Hj. Endang Mariawaty', '196812131999032002', 'Pengolah Data dan Informasi', 'PNS'),
(14, 'Riyadi', '196812232025211008', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(15, 'Hamid, S.T., M,Si', '196905281993081003', 'Analis Ketahanan Pangan Ahli Muda (Penyetaraan)', 'PNS'),
(16, 'Imam Mustakim, S. Hut., M.E', '196906012009011001', 'Pengolah Data dan Informasi', 'PNS'),
(17, 'Ardiansyah, S.P., M.Si', '196906071998031004', 'Pengawas Benih Tanaman Muda (Penyetaraan)', 'PNS'),
(18, 'Masyhuri, S.H', '196910101994031014', 'Analis Keuangan Pemerintah Pusat dan Daerah Muda (Penyetaraan)', 'PNS'),
(19, 'Abdul Hasyim Putra, S.P', '196912051989021001', 'Pengendali Organisme Penggangu Tumbuhan (Penyetraan)', 'PNS'),
(20, 'Friska Tulak Lewa, S.TP', '197001121998032005', 'Penyuluh Pertanian Muda (Penyetaraan)', 'PNS'),
(21, 'Nakir', '197003032025211046', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(22, 'Serlyna, S.E', '197003042008012027', 'Pengolah Data Keuangan', 'PNS'),
(23, 'Sahril', '197003072025211035', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(24, 'Darmiwanti, S.E', '197004091994022003', 'Analis Keuangan Pemerintah Pusat dan Daerah Muda (Penyetaraan)', 'PNS'),
(25, 'Magdalena, S.P', '197010242010012001', 'Penelaah Teknis Kebijakan', 'PNS'),
(26, 'Aswar Perwira, S.Hut', '197012212008011006', 'Analis Ketahanan Pangan Ahli Muda (Penyetaraan)', 'PNS'),
(27, 'Hj, Ida Laila, S.E', '197102172008012018', 'Penelaah Teknis Kebijakan', 'PNS'),
(28, 'Kumbawan Wibisono, S.Pt., M.Si', '197102181998031011', 'Pengawas Bibit Ternak Ahli Muda (Penyetaraan)', 'PNS'),
(29, 'Nur Alam Syarif, S.Pt', '197106271998031005', 'Analis Pasar Hasil Pertanian Muda (Penyetaraan)', 'PNS'),
(30, 'Soimin', '197109012009011003', 'Pengadministrasi Perkantoran', 'PNS'),
(31, 'Ramli', '197112032007011012', 'Pengadministrasian Umum', 'PNS'),
(32, 'Purwanto, S.P., M.Si', '197112122006041020', 'Analis Ketahanan Pangan Ahli Madya', 'PNS'),
(33, 'Eka Susila Ardiyanti', '197204112025212021', 'Operator Layanan Operasional', 'PPPK Paruh Waktu'),
(34, 'Rusdiansyah', '197303042008011016', 'Pengadministrasi Perkantoran', 'PNS'),
(35, 'Aries Norwandy, S.P., M.Si', '197303222001121005', 'Pengawas Benih Tanaman Muda (Penyetaraan)', 'PNS'),
(36, 'Bonangin', '197304042025211056', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(37, 'Frederik H.J. Bakarbessy, S.P', '197309252002121003', 'Penelaah Teknis Kebijakan', 'PNS'),
(38, 'Yudi Sugiatna, S.Hut', '197309282008011007', 'Analis Ketahanan Pangan Muda (Penyetaraan)', 'PNS'),
(39, 'Irma Saraswati, S.P', '197401242008012006', 'Analis Ketahanan Pangan Muda (Penyetaraan)', 'PNS'),
(40, 'Erni, S.P', '197402262009012002', 'Penelaah Teknis Kebijakan', 'PNS'),
(41, 'Dewi Hastuti, S.P', '197405272010012002', 'Pengolah Data dan Informasi', 'PNS'),
(42, 'Dwi Wahyudi', '197409062007011008', 'Pengadministrasi Perkantoran', 'PNS'),
(43, 'Chitra Norawanty, S.P', '197410272012122001', 'Penelaah Teknis Kebijakan', 'PNS'),
(44, 'Noke Pattipawaej, S.P., M.P', '197411032007011009', 'Plt. Kabid Tanaman Pangan, Hortikultura, dan Perkebunan (TPHP)', 'PNS'),
(45, 'Rina Rosita, S.P', '197503122000032002', 'Analis Pasar Hasil Pertanian Muda (Penyetaraan)', 'PNS'),
(46, 'Alie Syadikin', '197505252025211106', 'Operator Layanan Operasional', 'PPPK Paruh Waktu'),
(47, 'Ahmad Herdani', '197506052007011030', 'Pengadministrasi Perkantoran', 'PNS'),
(48, 'Indar Deni, S.P', '197507222008012013', 'Pengelola Layanan Operasional', 'PNS'),
(49, 'Indriyani, S.P', '197507242007012012', 'Pengelola Layanan Operasional', 'PNS'),
(50, 'Asep Nugraha', '197507272008011020', 'Pengadministrasi Perkantoran', 'PNS'),
(51, 'Mustafa AT', '197508152009011005', 'Pengadministrasi Perkantoran', 'PNS'),
(52, 'Helena Niga Lega', '197511052025212034', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(53, 'Sri Wahyuni A, S.P', '197512012009012001', 'Penelaah Teknis Kebijakan', 'PNS'),
(54, 'Rustanto, S.P', '197512042010011005', 'Pengolah Data dan Informasi', 'PNS'),
(55, 'Lia Desyrakhmawati, S.P., M.Si', '197512242001122001', 'Pengolah Data dan Informasi', 'PNS'),
(56, 'Puryani', '197605112025211064', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(57, 'Henny Hendrawaty, S.P', '197609212009012003', 'Penelaah Teknis Kebijakan', 'PNS'),
(58, 'Muhammad Yudhayono, S.P', '197612172009011002', 'Penelaah Teknis Kebijakan', 'PNS'),
(59, 'Achmad Riansyah, S.E', '197705142007011020', 'Pengolah Data dan Informasi', 'PNS'),
(60, 'Kurniah, S.P', '197708032010012001', 'Penelaah Teknis Kebijakan', 'PNS'),
(61, 'Mugiyono, S.P', '197711052007011009', 'Pengawas Alat dan Mesin Pertanian Muda (Penyetaraan)', 'PNS'),
(62, 'Naomi Randa, S.P', '197711072008012018', 'Penelaah Teknis Kebijakan', 'PNS'),
(63, 'Haeria, S.P', '197711142007012007', 'Pengolah Data dan Informasi', 'PNS'),
(64, 'Hj. Ida Zulfiani Nata, S.Pt., M.Si', '197711232009012002', 'Pengawas Mutu Pakan Pertama', 'PNS'),
(65, 'Dany Dermawan, A.Md', '197712112009011007', 'Pengolah Data dan Informasi', 'PNS'),
(66, 'Hj.Silvia Sari, S.P', '197803152008012027', 'Pengolah Data dan Informasi', 'PNS'),
(67, 'Murjani, S.P', '197805132010011001', 'Pengolah Data dan Informasi', 'PNS'),
(68, 'Chusnul Chotimah, S.Pt', '197807262014082001', 'Penelaah Teknis Kebijakan', 'PNS'),
(69, 'Eti Kur Anniwati, A.Md', '197810122008012027', 'Pengadministrasi Perkantoran', 'PNS'),
(70, 'Misran, S.P', '197812072008011006', 'Pengelola Layanan Operasional', 'PNS'),
(71, 'Dhedy Irawan', '197812142007011014', 'Operator Layanan Operasional', 'PNS'),
(72, 'Sulaiman', '197906052025211134', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(73, 'Ivan Leo Rizky, S.P', '197907272005021007', 'Penelaah Teknis Kebijakan', 'PNS'),
(74, 'Fitriana Agustina, S.P', '197908252008012029', 'Penelaah Teknis Kebijakan', 'PNS'),
(75, 'Hj. Lilis Aryani, S.P., M.Si', '197909222003122006', 'Analis Ketahanan Pangan Muda (Penyetaraan)', 'PNS'),
(76, 'Andi Syachriyaty Firdaus, S.Pt', '197910152003122007', 'Penelaah Teknis Kebijakan', 'PNS'),
(77, 'Surya Hermawan', '197911272007011010', 'Pengadministrasi Perkantoran', 'PNS'),
(78, 'Supian Sauri', '197912042025211061', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(79, 'Muhammad Sadri Nur, S.P', '198002042010011001', 'Penelaah Teknis Kebijakan', 'PNS'),
(80, 'Teguh Santoso', '198004112008011018', 'Pengadministrasi Perkantoran', 'PNS'),
(81, 'Gazali Rahman', '198107022025211007', 'Pengadministrasi Perkantoran', 'PPPK Penuh Waktu'),
(82, 'Achmad Taufik, A.Md', '198107262025211014', 'Pengadministrasi Perkantoran', 'PPPK Paruh Waktu'),
(83, 'Surono Manungkah', '198109082007011007', 'Operator Layanan Operasional', 'PNS'),
(84, 'Nurhayana', '198110032025212006', 'Pengadministrasi Perkantoran', 'PPPK Penuh Waktu'),
(85, 'Stefani Yeni Marlina', '198111142025212040', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(86, 'Riyadi', '198112012008011013', 'Operator Layanan Operasional', 'PNS'),
(87, 'Hj. Indah Handayani, S.P', '198203232010012015', 'Pengelola Layanan Operasional', 'PNS'),
(88, 'Alosius Laose Seha', '198204242025211144', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(89, 'Arif Rahman', '198207112025211092', 'Operator Layanan Operasional', 'PPPK Paruh Waktu'),
(90, 'Mariyo Fahlefi', '198302182009011002', 'Pengadministrasi Perkantoran', 'PNS'),
(91, 'Abdur Rahman Turmudy', '198306212025211123', 'Operator Layanan Operasional', 'PPPK Paruh Waktu'),
(92, 'Aan Andriyanto, A.Md', '198311292011011001', 'Paramedik Veteriner Mahir', 'PNS'),
(93, 'Immanuel Yusuf Mule', '198403242025211111', 'Operator Layanan Operasional', 'PPPK Paruh Waktu'),
(94, 'Yulinda Randa', '198407302025212005', 'Pengadministrasi Perkantoran', 'PPPK Penuh Waktu'),
(95, 'Yuliana Susanti', '198409162025212016', 'Pengadministrasi Perkantoran', 'PPPK Penuh Waktu'),
(96, 'Hefni Effendi', '198501102025211122', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(97, 'Risna Ariani', '198503302025212004', 'Pengadministrasi Perkantoran', 'PPPK Penuh Waktu'),
(98, 'Maksimus Usfinit', '198509232025211094', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(99, 'Astrid Ferera, S.P', '198511212015032005', 'Analis Ketahanan Pangan Ahli Pertama', 'PNS'),
(100, 'Titiek Nur Liendy Fauzia, SP', '198602202010012014', 'Analis Ketahanan Pangan Ahli Muda', 'PNS'),
(101, 'Dwi Setya Ningsih', '198603192025212002', 'Pengadministrasi Perkantoran', 'PPPK Penuh Waktu'),
(102, 'Jamiatul Isra\'iyah, S.P., M.P', '198604042015032003', 'Pengendalian Organisme Pengganggu Tumbuhan Pertama', 'PNS'),
(103, 'Rhamadani', '198605302025211106', 'Operator Layanan Operasional', 'PPPK Paruh Waktu'),
(104, 'Nor Juliana', '198607312024212010', 'Pengawas Mutu Pakan Ahli Pertama', 'PPPK Penuh Waktu'),
(105, 'Gusti Muhammad Handri Fazrin', '198608262015031003', 'Pengendali Organisme Pengganggu Tumbuhan Terampil', 'PNS'),
(106, 'Adita Medal Riksa Tunggara', '198610292025212020', 'Pengadministrasi Perkantoran', 'PPPK Penuh Waktu'),
(107, 'Ekawati Lestari Rahmaniah', '198708032025212007', 'Pengadministrasi Perkantoran', 'PPPK Penuh Waktu'),
(108, 'Indah Gustari', '198708032025212086', 'Operator Layanan Operasional', 'PPPK Paruh Waktu'),
(109, 'Muhammad Dong', '198712072025211128', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(110, 'Ence Dwi Januari Fadillah Idam, S.TP', '198801182011011004', 'Analis Ketahanan Pangan Ahli Muda', 'PNS'),
(111, 'Rahza Anugrah', '198901042025211011', 'Pengadministrasi Perkantoran', 'PPPK Penuh Waktu'),
(112, 'Bastian Eka Saputra, S.Pt', '199005202024211018', 'Pengawas Bibit Ternak Ahli Pertama', 'PPPK Penuh Waktu'),
(113, 'Dian Cahya Ramadhan', '199103292025211115', 'Operator Layanan Operasional', 'PPPK Paruh Waktu'),
(114, 'Dwi Kusuma Putra', '199106182025211031', 'Pengadministrasi Perkantoran', 'PPPK Penuh Waktu'),
(115, 'Tri Widodo', '199110112025211107', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu'),
(116, 'Yudi Azhan, A.Md', '199206062024211017', 'Paramedik Veteriner Terampil', 'PPPK Penuh Waktu'),
(117, 'Rina Parispri', '199305312025212108', 'Operator Layanan Operasional', 'PPPK Paruh Waktu'),
(118, 'Aditia Nur Rachman', '199312212025211089', 'Operator Layanan Operasional', 'PPPK Paruh Waktu'),
(119, 'Rinai Sinang Agustin Abun', '199408172025212020', 'Pengadministrasi Perkantoran', 'PPPK Penuh Waktu'),
(120, 'Muhamad Amir Rosyidin', '199409302025211008', 'Pengadministrasi Perkantoran', 'PPPK Penuh Waktu'),
(121, 'drh. Azhar Hanafi', '199506132024211020', 'Medik Veteriner Ahli Pertama', 'PPPK Penuh Waktu'),
(122, 'drh. Agtarie Betha Kusumawardani', '199508172024212069', 'Medik Veteriner Ahli Pertama', 'PPPK Penuh Waktu'),
(123, 'Muhammad Sandy Sugiarto', '199509202025211108', 'Operator Layanan Operasional', 'PPPK Paruh Waktu'),
(124, 'Zelline Yoselina', '199605212025212124', 'Operator Layanan Operasional', 'PPPK Paruh Waktu'),
(125, 'drh. Amalia Nadila Faradillah', '199802202025062008', 'Medik Veteriner Ahli Pertama', 'PNS'),
(126, 'drh. Stefany Eloidia', '199809262025062004', 'Medik Veteriner Ahli Pertama', 'PNS'),
(127, 'drh. Hera Martyna Svensa', '200003072025062011', 'Medik Veteriner Ahli Pertama', 'PNS'),
(128, 'Frederic Morado Saragih, S.Kom', '200105132025061007', 'Penata Kelola Sistem dan Teknologi Informasi', 'PNS'),
(129, 'Israwan Adi Pamungkas', '200110152025211042', 'Pengelola Umum Operasional', 'PPPK Paruh Waktu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pegawai_distapangtani`
--
ALTER TABLE `pegawai_distapangtani`
  ADD PRIMARY KEY (`no`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- Migrasi satu kali untuk database asli yang sudah diimpor.
-- Backup terlebih dahulu. Jangan jalankan setelah mengimpor SQL lengkap terbaru.
USE `kepegawaian_distapangtani`;

-- Nama lima kolom asli dipertahankan. Nomor menjadi AUTO_INCREMENT.
ALTER TABLE `pegawai_distapangtani`
  CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT,
  ADD `mulai_asn_manual` date DEFAULT NULL COMMENT 'PPPK: tanggal 1 bulan pengangkatan, input manual',
  ADD `tanggal_lahir_manual` date DEFAULT NULL COMMENT 'Opsional untuk PJLP',
  ADD `jenis_kelamin_manual` enum('1','2') DEFAULT NULL COMMENT 'Opsional untuk PJLP',
  ADD `row_version` int unsigned NOT NULL DEFAULT 1,
  ADD `nip_unik` varchar(30) GENERATED ALWAYS AS
      (CASE WHEN `status_kepegawaian` = 'PJLP' THEN NULL ELSE REPLACE(`nip`, ' ', '') END) STORED,
  ADD UNIQUE KEY `uq_pegawai_nip` (`nip_unik`);

-- Satu-satunya tabel baru: akun admin beserta metadata keamanan login.
CREATE TABLE `admin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(40) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('superadmin','admin') NOT NULL DEFAULT 'admin',
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `auth_version` int unsigned NOT NULL DEFAULT 1,
  `failed_attempts` smallint unsigned NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admin_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admin` (`nama`,`username`,`password_hash`,`role`,`aktif`)
VALUES ('Eric','Eric','$2b$12$qpDgdPx3AlI20J/jtQUyz.4rtY4DVh9HVx01C1ov5iN66tR/XcBJy','superadmin',1);

-- Pendidikan terakhir (termasuk dalam instalasi/migrasi versi terbaru).
ALTER TABLE `pegawai_distapangtani` ADD COLUMN `pendidikan_terakhir` ENUM('SD/sederajat','SMP/sederajat','SMA/sederajat','Diploma I - Diploma III','Diploma IV/Sarjana (S1)','Magister (S2)','Doktor (S3)','Program Profesi Dokter Hewan') DEFAULT NULL AFTER `jabatan`;
UPDATE `pegawai_distapangtani` SET `pendidikan_terakhir` = CASE
  WHEN UPPER(REPLACE(TRIM(`nama`),'.','')) REGEXP '(^|[,;[:space:]])(P[[:space:]]*H[[:space:]]*D|D[[:space:]]*P[[:space:]]*H[[:space:]]*I[[:space:]]*L)([,;[:space:]]+(A[[:space:]]*M[[:space:]]*D[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*T[[:space:]]*R[[:space:]]*K[[:space:]]*E[[:space:]]*P|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*O[[:space:]]*M|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*E[[:space:]]*P|D[[:space:]]*P[[:space:]]*H[[:space:]]*I[[:space:]]*L|S[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*S[[:space:]]*T[[:space:]]*A[[:space:]]*T|A[[:space:]]*M[[:space:]]*D[[:space:]]*A[[:space:]]*K|M[[:space:]]*K[[:space:]]*O[[:space:]]*M|M[[:space:]]*S[[:space:]]*O[[:space:]]*S|M[[:space:]]*H[[:space:]]*U[[:space:]]*M|M[[:space:]]*K[[:space:]]*E[[:space:]]*S|M[[:space:]]*E[[:space:]]*N[[:space:]]*G|S[[:space:]]*K[[:space:]]*O[[:space:]]*M|S[[:space:]]*S[[:space:]]*O[[:space:]]*S|S[[:space:]]*H[[:space:]]*U[[:space:]]*T|S[[:space:]]*K[[:space:]]*E[[:space:]]*P|S[[:space:]]*A[[:space:]]*R[[:space:]]*S|P[[:space:]]*H[[:space:]]*D|M[[:space:]]*S[[:space:]]*I|M[[:space:]]*A[[:space:]]*P|M[[:space:]]*P[[:space:]]*D|M[[:space:]]*A[[:space:]]*G|M[[:space:]]*K[[:space:]]*M|M[[:space:]]*B[[:space:]]*A|M[[:space:]]*S[[:space:]]*C|M[[:space:]]*P[[:space:]]*A|D[[:space:]]*R[[:space:]]*H|S[[:space:]]*P[[:space:]]*T|S[[:space:]]*A[[:space:]]*G|S[[:space:]]*P[[:space:]]*D|S[[:space:]]*T[[:space:]]*P|S[[:space:]]*K[[:space:]]*M|S[[:space:]]*I[[:space:]]*P|S[[:space:]]*A[[:space:]]*P|S[[:space:]]*I[[:space:]]*K|S[[:space:]]*S[[:space:]]*I|S[[:space:]]*A[[:space:]]*K|S[[:space:]]*D[[:space:]]*S|S[[:space:]]*T[[:space:]]*R|A[[:space:]]*M[[:space:]]*D|A[[:space:]]*M[[:space:]]*P|A[[:space:]]*M[[:space:]]*A|C[[:space:]]*P[[:space:]]*A|A[[:space:]]*P[[:space:]]*T|M[[:space:]]*M|M[[:space:]]*P|M[[:space:]]*T|M[[:space:]]*H|M[[:space:]]*A|M[[:space:]]*S|S[[:space:]]*P|S[[:space:]]*T|S[[:space:]]*E|S[[:space:]]*H|S[[:space:]]*S|I[[:space:]]*R|A[[:space:]]*K|C[[:space:]]*A|N[[:space:]]*S|G[[:space:]]*R))*[,;[:space:]]*$' THEN 'Doktor (S3)'
  WHEN UPPER(REPLACE(TRIM(`nama`),'.','')) REGEXP '(^|[,;[:space:]])(M[[:space:]]*S[[:space:]]*I|M[[:space:]]*M|M[[:space:]]*A[[:space:]]*P|M[[:space:]]*P|M[[:space:]]*P[[:space:]]*D|M[[:space:]]*K[[:space:]]*O[[:space:]]*M|M[[:space:]]*T|M[[:space:]]*H|M[[:space:]]*A[[:space:]]*G|M[[:space:]]*S[[:space:]]*O[[:space:]]*S|M[[:space:]]*H[[:space:]]*U[[:space:]]*M|M[[:space:]]*K[[:space:]]*E[[:space:]]*S|M[[:space:]]*K[[:space:]]*M|M[[:space:]]*B[[:space:]]*A|M[[:space:]]*S[[:space:]]*C|M[[:space:]]*E[[:space:]]*N[[:space:]]*G|M[[:space:]]*P[[:space:]]*A|M[[:space:]]*A|M[[:space:]]*S)([,;[:space:]]+(A[[:space:]]*M[[:space:]]*D[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*T[[:space:]]*R[[:space:]]*K[[:space:]]*E[[:space:]]*P|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*O[[:space:]]*M|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*E[[:space:]]*P|D[[:space:]]*P[[:space:]]*H[[:space:]]*I[[:space:]]*L|S[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*S[[:space:]]*T[[:space:]]*A[[:space:]]*T|A[[:space:]]*M[[:space:]]*D[[:space:]]*A[[:space:]]*K|M[[:space:]]*K[[:space:]]*O[[:space:]]*M|M[[:space:]]*S[[:space:]]*O[[:space:]]*S|M[[:space:]]*H[[:space:]]*U[[:space:]]*M|M[[:space:]]*K[[:space:]]*E[[:space:]]*S|M[[:space:]]*E[[:space:]]*N[[:space:]]*G|S[[:space:]]*K[[:space:]]*O[[:space:]]*M|S[[:space:]]*S[[:space:]]*O[[:space:]]*S|S[[:space:]]*H[[:space:]]*U[[:space:]]*T|S[[:space:]]*K[[:space:]]*E[[:space:]]*P|S[[:space:]]*A[[:space:]]*R[[:space:]]*S|P[[:space:]]*H[[:space:]]*D|M[[:space:]]*S[[:space:]]*I|M[[:space:]]*A[[:space:]]*P|M[[:space:]]*P[[:space:]]*D|M[[:space:]]*A[[:space:]]*G|M[[:space:]]*K[[:space:]]*M|M[[:space:]]*B[[:space:]]*A|M[[:space:]]*S[[:space:]]*C|M[[:space:]]*P[[:space:]]*A|D[[:space:]]*R[[:space:]]*H|S[[:space:]]*P[[:space:]]*T|S[[:space:]]*A[[:space:]]*G|S[[:space:]]*P[[:space:]]*D|S[[:space:]]*T[[:space:]]*P|S[[:space:]]*K[[:space:]]*M|S[[:space:]]*I[[:space:]]*P|S[[:space:]]*A[[:space:]]*P|S[[:space:]]*I[[:space:]]*K|S[[:space:]]*S[[:space:]]*I|S[[:space:]]*A[[:space:]]*K|S[[:space:]]*D[[:space:]]*S|S[[:space:]]*T[[:space:]]*R|A[[:space:]]*M[[:space:]]*D|A[[:space:]]*M[[:space:]]*P|A[[:space:]]*M[[:space:]]*A|C[[:space:]]*P[[:space:]]*A|A[[:space:]]*P[[:space:]]*T|M[[:space:]]*M|M[[:space:]]*P|M[[:space:]]*T|M[[:space:]]*H|M[[:space:]]*A|M[[:space:]]*S|S[[:space:]]*P|S[[:space:]]*T|S[[:space:]]*E|S[[:space:]]*H|S[[:space:]]*S|I[[:space:]]*R|A[[:space:]]*K|C[[:space:]]*A|N[[:space:]]*S|G[[:space:]]*R))*[,;[:space:]]*$' THEN 'Magister (S2)'
  WHEN UPPER(REPLACE(TRIM(`nama`),'.','')) REGEXP '((^|[,;[:space:]])(D[[:space:]]*R[[:space:]]*H)([,;[:space:]]+(A[[:space:]]*M[[:space:]]*D[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*T[[:space:]]*R[[:space:]]*K[[:space:]]*E[[:space:]]*P|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*O[[:space:]]*M|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*E[[:space:]]*P|D[[:space:]]*P[[:space:]]*H[[:space:]]*I[[:space:]]*L|S[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*S[[:space:]]*T[[:space:]]*A[[:space:]]*T|A[[:space:]]*M[[:space:]]*D[[:space:]]*A[[:space:]]*K|M[[:space:]]*K[[:space:]]*O[[:space:]]*M|M[[:space:]]*S[[:space:]]*O[[:space:]]*S|M[[:space:]]*H[[:space:]]*U[[:space:]]*M|M[[:space:]]*K[[:space:]]*E[[:space:]]*S|M[[:space:]]*E[[:space:]]*N[[:space:]]*G|S[[:space:]]*K[[:space:]]*O[[:space:]]*M|S[[:space:]]*S[[:space:]]*O[[:space:]]*S|S[[:space:]]*H[[:space:]]*U[[:space:]]*T|S[[:space:]]*K[[:space:]]*E[[:space:]]*P|S[[:space:]]*A[[:space:]]*R[[:space:]]*S|P[[:space:]]*H[[:space:]]*D|M[[:space:]]*S[[:space:]]*I|M[[:space:]]*A[[:space:]]*P|M[[:space:]]*P[[:space:]]*D|M[[:space:]]*A[[:space:]]*G|M[[:space:]]*K[[:space:]]*M|M[[:space:]]*B[[:space:]]*A|M[[:space:]]*S[[:space:]]*C|M[[:space:]]*P[[:space:]]*A|D[[:space:]]*R[[:space:]]*H|S[[:space:]]*P[[:space:]]*T|S[[:space:]]*A[[:space:]]*G|S[[:space:]]*P[[:space:]]*D|S[[:space:]]*T[[:space:]]*P|S[[:space:]]*K[[:space:]]*M|S[[:space:]]*I[[:space:]]*P|S[[:space:]]*A[[:space:]]*P|S[[:space:]]*I[[:space:]]*K|S[[:space:]]*S[[:space:]]*I|S[[:space:]]*A[[:space:]]*K|S[[:space:]]*D[[:space:]]*S|S[[:space:]]*T[[:space:]]*R|A[[:space:]]*M[[:space:]]*D|A[[:space:]]*M[[:space:]]*P|A[[:space:]]*M[[:space:]]*A|C[[:space:]]*P[[:space:]]*A|A[[:space:]]*P[[:space:]]*T|M[[:space:]]*M|M[[:space:]]*P|M[[:space:]]*T|M[[:space:]]*H|M[[:space:]]*A|M[[:space:]]*S|S[[:space:]]*P|S[[:space:]]*T|S[[:space:]]*E|S[[:space:]]*H|S[[:space:]]*S|I[[:space:]]*R|A[[:space:]]*K|C[[:space:]]*A|N[[:space:]]*S|G[[:space:]]*R))*[,;[:space:]]*$)|(^((PROF|H|HJ|IR|DRH)[[:space:]]+)*DRH[[:space:]]+[^[:space:]])' THEN 'Program Profesi Dokter Hewan'
  WHEN UPPER(REPLACE(TRIM(`nama`),'.','')) REGEXP '((^|[,;[:space:]])(S[[:space:]]*P|S[[:space:]]*P[[:space:]]*T|S[[:space:]]*K[[:space:]]*O[[:space:]]*M|S[[:space:]]*T|S[[:space:]]*E|S[[:space:]]*H|S[[:space:]]*A[[:space:]]*G|S[[:space:]]*S[[:space:]]*O[[:space:]]*S|S[[:space:]]*P[[:space:]]*D|S[[:space:]]*T[[:space:]]*P|S[[:space:]]*H[[:space:]]*U[[:space:]]*T|S[[:space:]]*K[[:space:]]*M|S[[:space:]]*I[[:space:]]*P|S[[:space:]]*A[[:space:]]*P|S[[:space:]]*I[[:space:]]*K|S[[:space:]]*S|S[[:space:]]*S[[:space:]]*I|S[[:space:]]*K[[:space:]]*E[[:space:]]*P|S[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*A[[:space:]]*K|S[[:space:]]*D[[:space:]]*S|S[[:space:]]*S[[:space:]]*T[[:space:]]*A[[:space:]]*T|S[[:space:]]*A[[:space:]]*R[[:space:]]*S|S[[:space:]]*T[[:space:]]*R|S[[:space:]]*T[[:space:]]*R[[:space:]]*K[[:space:]]*E[[:space:]]*P|I[[:space:]]*R)([,;[:space:]]+(A[[:space:]]*M[[:space:]]*D[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*T[[:space:]]*R[[:space:]]*K[[:space:]]*E[[:space:]]*P|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*O[[:space:]]*M|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*E[[:space:]]*P|D[[:space:]]*P[[:space:]]*H[[:space:]]*I[[:space:]]*L|S[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*S[[:space:]]*T[[:space:]]*A[[:space:]]*T|A[[:space:]]*M[[:space:]]*D[[:space:]]*A[[:space:]]*K|M[[:space:]]*K[[:space:]]*O[[:space:]]*M|M[[:space:]]*S[[:space:]]*O[[:space:]]*S|M[[:space:]]*H[[:space:]]*U[[:space:]]*M|M[[:space:]]*K[[:space:]]*E[[:space:]]*S|M[[:space:]]*E[[:space:]]*N[[:space:]]*G|S[[:space:]]*K[[:space:]]*O[[:space:]]*M|S[[:space:]]*S[[:space:]]*O[[:space:]]*S|S[[:space:]]*H[[:space:]]*U[[:space:]]*T|S[[:space:]]*K[[:space:]]*E[[:space:]]*P|S[[:space:]]*A[[:space:]]*R[[:space:]]*S|P[[:space:]]*H[[:space:]]*D|M[[:space:]]*S[[:space:]]*I|M[[:space:]]*A[[:space:]]*P|M[[:space:]]*P[[:space:]]*D|M[[:space:]]*A[[:space:]]*G|M[[:space:]]*K[[:space:]]*M|M[[:space:]]*B[[:space:]]*A|M[[:space:]]*S[[:space:]]*C|M[[:space:]]*P[[:space:]]*A|D[[:space:]]*R[[:space:]]*H|S[[:space:]]*P[[:space:]]*T|S[[:space:]]*A[[:space:]]*G|S[[:space:]]*P[[:space:]]*D|S[[:space:]]*T[[:space:]]*P|S[[:space:]]*K[[:space:]]*M|S[[:space:]]*I[[:space:]]*P|S[[:space:]]*A[[:space:]]*P|S[[:space:]]*I[[:space:]]*K|S[[:space:]]*S[[:space:]]*I|S[[:space:]]*A[[:space:]]*K|S[[:space:]]*D[[:space:]]*S|S[[:space:]]*T[[:space:]]*R|A[[:space:]]*M[[:space:]]*D|A[[:space:]]*M[[:space:]]*P|A[[:space:]]*M[[:space:]]*A|C[[:space:]]*P[[:space:]]*A|A[[:space:]]*P[[:space:]]*T|M[[:space:]]*M|M[[:space:]]*P|M[[:space:]]*T|M[[:space:]]*H|M[[:space:]]*A|M[[:space:]]*S|S[[:space:]]*P|S[[:space:]]*T|S[[:space:]]*E|S[[:space:]]*H|S[[:space:]]*S|I[[:space:]]*R|A[[:space:]]*K|C[[:space:]]*A|N[[:space:]]*S|G[[:space:]]*R))*[,;[:space:]]*$)|(^((PROF|H|HJ|IR|DRH)[[:space:]]+)*IR[[:space:]]+[^[:space:]])' THEN 'Diploma IV/Sarjana (S1)'
  WHEN UPPER(REPLACE(TRIM(`nama`),'.','')) REGEXP '(^|[,;[:space:]])(A[[:space:]]*M[[:space:]]*D|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*O[[:space:]]*M|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*E[[:space:]]*P|A[[:space:]]*M[[:space:]]*D[[:space:]]*A[[:space:]]*K|A[[:space:]]*M[[:space:]]*D[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|A[[:space:]]*M[[:space:]]*P|A[[:space:]]*M[[:space:]]*A)([,;[:space:]]+(A[[:space:]]*M[[:space:]]*D[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*T[[:space:]]*R[[:space:]]*K[[:space:]]*E[[:space:]]*P|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*O[[:space:]]*M|A[[:space:]]*M[[:space:]]*D[[:space:]]*K[[:space:]]*E[[:space:]]*P|D[[:space:]]*P[[:space:]]*H[[:space:]]*I[[:space:]]*L|S[[:space:]]*F[[:space:]]*A[[:space:]]*R[[:space:]]*M|S[[:space:]]*S[[:space:]]*T[[:space:]]*A[[:space:]]*T|A[[:space:]]*M[[:space:]]*D[[:space:]]*A[[:space:]]*K|M[[:space:]]*K[[:space:]]*O[[:space:]]*M|M[[:space:]]*S[[:space:]]*O[[:space:]]*S|M[[:space:]]*H[[:space:]]*U[[:space:]]*M|M[[:space:]]*K[[:space:]]*E[[:space:]]*S|M[[:space:]]*E[[:space:]]*N[[:space:]]*G|S[[:space:]]*K[[:space:]]*O[[:space:]]*M|S[[:space:]]*S[[:space:]]*O[[:space:]]*S|S[[:space:]]*H[[:space:]]*U[[:space:]]*T|S[[:space:]]*K[[:space:]]*E[[:space:]]*P|S[[:space:]]*A[[:space:]]*R[[:space:]]*S|P[[:space:]]*H[[:space:]]*D|M[[:space:]]*S[[:space:]]*I|M[[:space:]]*A[[:space:]]*P|M[[:space:]]*P[[:space:]]*D|M[[:space:]]*A[[:space:]]*G|M[[:space:]]*K[[:space:]]*M|M[[:space:]]*B[[:space:]]*A|M[[:space:]]*S[[:space:]]*C|M[[:space:]]*P[[:space:]]*A|D[[:space:]]*R[[:space:]]*H|S[[:space:]]*P[[:space:]]*T|S[[:space:]]*A[[:space:]]*G|S[[:space:]]*P[[:space:]]*D|S[[:space:]]*T[[:space:]]*P|S[[:space:]]*K[[:space:]]*M|S[[:space:]]*I[[:space:]]*P|S[[:space:]]*A[[:space:]]*P|S[[:space:]]*I[[:space:]]*K|S[[:space:]]*S[[:space:]]*I|S[[:space:]]*A[[:space:]]*K|S[[:space:]]*D[[:space:]]*S|S[[:space:]]*T[[:space:]]*R|A[[:space:]]*M[[:space:]]*D|A[[:space:]]*M[[:space:]]*P|A[[:space:]]*M[[:space:]]*A|C[[:space:]]*P[[:space:]]*A|A[[:space:]]*P[[:space:]]*T|M[[:space:]]*M|M[[:space:]]*P|M[[:space:]]*T|M[[:space:]]*H|M[[:space:]]*A|M[[:space:]]*S|S[[:space:]]*P|S[[:space:]]*T|S[[:space:]]*E|S[[:space:]]*H|S[[:space:]]*S|I[[:space:]]*R|A[[:space:]]*K|C[[:space:]]*A|N[[:space:]]*S|G[[:space:]]*R))*[,;[:space:]]*$' THEN 'Diploma I - Diploma III'
  ELSE NULL END WHERE `pendidikan_terakhir` IS NULL;
