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
