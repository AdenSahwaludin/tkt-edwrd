-- MySQL Dump
-- Generated at: 2025-12-22 17:18:33
-- Database: tkt_warehouse

SET FOREIGN_KEY_CHECKS=0;

-- Structure for table `backup_logs`
DROP TABLE IF EXISTS `backup_logs`;
CREATE TABLE `backup_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `format` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sql',
  `file_size` bigint unsigned DEFAULT NULL,
  `status` enum('success','failed','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `backup_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `backup_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `backup_logs`
INSERT INTO `backup_logs` VALUES ('1', '1', NULL, 'sql', NULL, 'pending', NULL, '2025-12-22 17:18:32', '2025-12-22 17:18:32', NULL);

-- Structure for table `barang`
DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_id` bigint unsigned NOT NULL,
  `lokasi_id` bigint unsigned NOT NULL,
  `jumlah_stok` int NOT NULL DEFAULT '0',
  `reorder_point` int NOT NULL DEFAULT '10' COMMENT 'Batas minimal stok sebelum perlu pemesanan ulang',
  `satuan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pcs',
  `status` enum('baik','rusak','hilang') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baik',
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `harga_satuan` decimal(15,2) DEFAULT NULL,
  `merk` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_pembelian` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barang_kode_barang_unique` (`kode_barang`),
  KEY `barang_kategori_id_foreign` (`kategori_id`),
  KEY `barang_lokasi_id_foreign` (`lokasi_id`),
  KEY `barang_kode_barang_nama_barang_index` (`kode_barang`,`nama_barang`),
  KEY `barang_status_index` (`status`),
  KEY `barang_jumlah_stok_index` (`jumlah_stok`),
  CONSTRAINT `barang_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barang_lokasi_id_foreign` FOREIGN KEY (`lokasi_id`) REFERENCES `lokasi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `barang`
INSERT INTO `barang` VALUES ('1', 'ELE-KL-GU-1766401449', 'Kursi Lipat', '1', '1', '10', '5', 'pcs', 'baik', NULL, '50000.00', NULL, NULL, '2025-12-22 11:04:09', '2025-12-22 11:04:09', NULL);
INSERT INTO `barang` VALUES ('2', 'ELE-KD-GU-001', 'Komputer Dell 1766401450', '1', '1', '5', '2', 'pcs', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 11:04:10', '2025-12-22 11:04:10', NULL);
INSERT INTO `barang` VALUES ('3', 'ELE-KO-GU-1766401451', 'Komputer', '1', '1', '95', '10', 'unit', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 11:04:11', '2025-12-22 11:04:11', NULL);
INSERT INTO `barang` VALUES ('4', 'ELE-KO-GU-1766401452', 'Komputer', '1', '1', '110', '10', 'unit', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 11:04:12', '2025-12-22 11:04:12', NULL);
INSERT INTO `barang` VALUES ('5', 'ELE-KO-GU-1766401453', 'Komputer', '1', '1', '95', '10', 'unit', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 11:04:13', '2025-12-22 11:04:13', NULL);
INSERT INTO `barang` VALUES ('6', 'ELE-KL-GU-1766408817', 'Kursi Lipat', '1', '1', '10', '5', 'pcs', 'baik', NULL, '50000.00', NULL, NULL, '2025-12-22 13:06:57', '2025-12-22 13:06:57', NULL);
INSERT INTO `barang` VALUES ('7', 'ELE-KD-GU-002', 'Komputer Dell 1766408818', '1', '1', '5', '2', 'pcs', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 13:06:58', '2025-12-22 13:06:58', NULL);
INSERT INTO `barang` VALUES ('8', 'ELE-KO-GU-1766408819', 'Komputer', '1', '1', '100', '10', 'unit', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 13:06:59', '2025-12-22 13:06:59', NULL);
INSERT INTO `barang` VALUES ('9', 'ELE-KO-GU-1766408820', 'Komputer', '1', '1', '95', '10', 'unit', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 13:07:00', '2025-12-22 13:07:00', NULL);
INSERT INTO `barang` VALUES ('10', 'ELE-KO-GU-1766408821', 'Komputer', '1', '1', '105', '10', 'unit', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 13:07:01', '2025-12-22 13:07:02', NULL);

-- Structure for table `barang_rusaks`
DROP TABLE IF EXISTS `barang_rusaks`;
CREATE TABLE `barang_rusaks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `barang_id` bigint unsigned NOT NULL,
  `jumlah` int NOT NULL,
  `tanggal_kejadian` date NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `penanggung_jawab` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barang_rusaks_user_id_foreign` (`user_id`),
  KEY `barang_rusaks_barang_id_index` (`barang_id`),
  KEY `barang_rusaks_tanggal_kejadian_index` (`tanggal_kejadian`),
  CONSTRAINT `barang_rusaks_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barang_rusaks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `cache`
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `cache`
INSERT INTO `cache` VALUES ('tkt-warehouse-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6', 'i:2;', '1766422748');
INSERT INTO `cache` VALUES ('tkt-warehouse-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6:timer', 'i:1766422748;', '1766422748');
INSERT INTO `cache` VALUES ('tkt-warehouse-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:32:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"view_users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"create_users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:10:\"edit_users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"delete_users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:14:\"reset_password\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:14:\"view_kategoris\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:16:\"create_kategoris\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:14:\"edit_kategoris\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:16:\"delete_kategoris\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:12:\"view_barangs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"create_barangs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:12:\"edit_barangs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:14:\"delete_barangs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:13;a:3:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"view_lokasis\";s:1:\"c\";s:3:\"web\";}i:14;a:3:{s:1:\"a\";i:15;s:1:\"b\";s:14:\"create_lokasis\";s:1:\"c\";s:3:\"web\";}i:15;a:3:{s:1:\"a\";i:16;s:1:\"b\";s:12:\"edit_lokasis\";s:1:\"c\";s:3:\"web\";}i:16;a:3:{s:1:\"a\";i:17;s:1:\"b\";s:14:\"delete_lokasis\";s:1:\"c\";s:3:\"web\";}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:22:\"view_transaksi_barangs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:24:\"create_transaksi_barangs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:22:\"edit_transaksi_barangs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:24:\"delete_transaksi_barangs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:25:\"approve_transaksi_barangs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:3;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:18:\"view_barang_rusaks\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:20:\"create_barang_rusaks\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:18:\"edit_barang_rusaks\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:20:\"delete_barang_rusaks\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:18:\"view_log_aktivitas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:20:\"delete_log_aktivitas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:14:\"view_dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:12:\"view_laporan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:13:\"backup_system\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:14:\"restore_system\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:3:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:12:\"Admin Sistem\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:18:\"Petugas Inventaris\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:14:\"Kepala Sekolah\";s:1:\"c\";s:3:\"web\";}}}', '1766486637');

-- Structure for table `cache_locks`
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `failed_jobs`
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `job_batches`
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `jobs`
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `kategori`
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kategori_nama_kategori_index` (`nama_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `kategori`
INSERT INTO `kategori` VALUES ('1', 'Elektronik', NULL, '2025-12-22 11:04:08', '2025-12-22 11:04:08', NULL);
INSERT INTO `kategori` VALUES ('2', 'Furniture Kantor', NULL, '2025-12-22 11:04:11', '2025-12-22 11:04:11', NULL);
INSERT INTO `kategori` VALUES ('3', 'C0NT0H', 'Contoh Keterangan', '2025-12-22 17:05:27', '2025-12-22 17:08:08', NULL);

-- Structure for table `log_aktivitas`
DROP TABLE IF EXISTS `log_aktivitas`;
CREATE TABLE `log_aktivitas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `jenis_aktivitas` enum('login','logout','create','update','delete','view') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_tabel` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama tabel yang terpengaruh',
  `record_id` bigint unsigned DEFAULT NULL COMMENT 'ID record yang terpengaruh',
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `perubahan_data` json DEFAULT NULL COMMENT 'Data perubahan dalam format JSON',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_aktivitas_user_id_jenis_aktivitas_created_at_index` (`user_id`,`jenis_aktivitas`,`created_at`),
  KEY `log_aktivitas_nama_tabel_record_id_index` (`nama_tabel`,`record_id`),
  KEY `log_aktivitas_created_at_index` (`created_at`),
  CONSTRAINT `log_aktivitas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `log_aktivitas`
INSERT INTO `log_aktivitas` VALUES ('1', '1', 'login', NULL, NULL, 'User berhasil login ke sistem', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 10:43:57', '2025-12-22 10:43:57');
INSERT INTO `log_aktivitas` VALUES ('2', '4', 'create', 'kategori', '1', 'Menambahkan kategori baru: Elektronik', '{\"id\": 1, \"created_at\": \"2025-12-22T11:04:08.000000Z\", \"updated_at\": \"2025-12-22T11:04:08.000000Z\", \"nama_kategori\": \"Elektronik\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:08', '2025-12-22 11:04:08');
INSERT INTO `log_aktivitas` VALUES ('3', '4', 'create', 'lokasi', '1', 'Menambahkan lokasi baru: Ruang Guru', '{\"id\": 1, \"created_at\": \"2025-12-22T11:04:08.000000Z\", \"updated_at\": \"2025-12-22T11:04:08.000000Z\", \"nama_lokasi\": \"Ruang Guru\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:08', '2025-12-22 11:04:08');
INSERT INTO `log_aktivitas` VALUES ('4', '4', 'create', 'barang', '1', 'Menambahkan barang baru: Kursi Lipat (ELE-KL-GU-1766401449)', '{\"id\": 1, \"status\": \"baik\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:09.000000Z\", \"updated_at\": \"2025-12-22T11:04:09.000000Z\", \"jumlah_stok\": 10, \"kategori_id\": 1, \"kode_barang\": \"ELE-KL-GU-1766401449\", \"nama_barang\": \"Kursi Lipat\", \"harga_satuan\": \"50000.00\", \"reorder_point\": 5}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:09', '2025-12-22 11:04:09');
INSERT INTO `log_aktivitas` VALUES ('5', '4', 'create', 'barang', '2', 'Menambahkan barang baru: Komputer Dell 1766401450 (ELE-KD-GU-001)', '{\"id\": 2, \"status\": \"baik\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:10.000000Z\", \"updated_at\": \"2025-12-22T11:04:10.000000Z\", \"jumlah_stok\": 5, \"kategori_id\": 1, \"kode_barang\": \"ELE-KD-GU-001\", \"nama_barang\": \"Komputer Dell 1766401450\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 2}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:10', '2025-12-22 11:04:10');
INSERT INTO `log_aktivitas` VALUES ('6', '4', 'create', 'lokasi', '2', 'Menambahkan lokasi baru: Lab Komputer', '{\"id\": 2, \"created_at\": \"2025-12-22T11:04:10.000000Z\", \"updated_at\": \"2025-12-22T11:04:10.000000Z\", \"nama_lokasi\": \"Lab Komputer\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:10', '2025-12-22 11:04:10');
INSERT INTO `log_aktivitas` VALUES ('7', '4', 'create', 'lokasi', '3', 'Menambahkan lokasi baru: Ruang Kepala Sekolah', '{\"id\": 3, \"created_at\": \"2025-12-22T11:04:10.000000Z\", \"updated_at\": \"2025-12-22T11:04:10.000000Z\", \"nama_lokasi\": \"Ruang Kepala Sekolah\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:10', '2025-12-22 11:04:10');
INSERT INTO `log_aktivitas` VALUES ('8', '4', 'create', 'lokasi', '4', 'Menambahkan lokasi baru: Ruang Kelas 7A', '{\"id\": 4, \"created_at\": \"2025-12-22T11:04:10.000000Z\", \"updated_at\": \"2025-12-22T11:04:10.000000Z\", \"nama_lokasi\": \"Ruang Kelas 7A\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:10', '2025-12-22 11:04:10');
INSERT INTO `log_aktivitas` VALUES ('9', '4', 'create', 'kategori', '2', 'Menambahkan kategori baru: Furniture Kantor', '{\"id\": 2, \"created_at\": \"2025-12-22T11:04:11.000000Z\", \"updated_at\": \"2025-12-22T11:04:11.000000Z\", \"nama_kategori\": \"Furniture Kantor\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:11', '2025-12-22 11:04:11');
INSERT INTO `log_aktivitas` VALUES ('10', '5', 'create', 'barang', '3', 'Menambahkan barang baru: Komputer (ELE-KO-GU-1766401451)', '{\"id\": 3, \"satuan\": \"unit\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:11.000000Z\", \"updated_at\": \"2025-12-22T11:04:11.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401451\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:11', '2025-12-22 11:04:11');
INSERT INTO `log_aktivitas` VALUES ('11', '5', 'create', 'transaksi_barang', '1', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 1, \"barang\": {\"id\": 3, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:11.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T11:04:11.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401451\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 3, \"created_at\": \"2025-12-22T11:04:11.000000Z\", \"updated_at\": \"2025-12-22T11:04:11.000000Z\", \"kode_transaksi\": \"TM-1766401451-001\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:11', '2025-12-22 11:04:11');
INSERT INTO `log_aktivitas` VALUES ('12', '5', 'update', 'barang', '3', 'Mengubah data barang: Komputer (ELE-KO-GU-1766401451)', '{\"after\": {\"id\": 3, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22 11:04:11\", \"deleted_at\": null, \"updated_at\": \"2025-12-22 11:04:11\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401451\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"before\": {\"id\": 3, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:11.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T11:04:11.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401451\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:11', '2025-12-22 11:04:11');
INSERT INTO `log_aktivitas` VALUES ('13', '5', 'create', 'transaksi_barang', '2', 'Transaksi keluar barang Komputer sejumlah 5 unit', '{\"id\": 2, \"barang\": {\"id\": 3, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:11.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T11:04:11.000000Z\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401451\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 5, \"user_id\": 5, \"barang_id\": 3, \"created_at\": \"2025-12-22T11:04:11.000000Z\", \"updated_at\": \"2025-12-22T11:04:11.000000Z\", \"kode_transaksi\": \"TK-1766401451-001\", \"tipe_transaksi\": \"keluar\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:11', '2025-12-22 11:04:11');
INSERT INTO `log_aktivitas` VALUES ('14', '5', 'create', 'barang', '4', 'Menambahkan barang baru: Komputer (ELE-KO-GU-1766401452)', '{\"id\": 4, \"satuan\": \"unit\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:12.000000Z\", \"updated_at\": \"2025-12-22T11:04:12.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401452\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:12', '2025-12-22 11:04:12');
INSERT INTO `log_aktivitas` VALUES ('15', '5', 'create', 'transaksi_barang', '3', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 3, \"barang\": {\"id\": 4, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:12.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T11:04:12.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401452\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 4, \"created_at\": \"2025-12-22T11:04:12.000000Z\", \"updated_at\": \"2025-12-22T11:04:12.000000Z\", \"kode_transaksi\": \"TM-1766401452-002\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"penanggung_jawab\": \"John Doe\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:12', '2025-12-22 11:04:12');
INSERT INTO `log_aktivitas` VALUES ('16', '5', 'create', 'transaksi_barang', '4', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 4, \"barang\": {\"id\": 4, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:12.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T11:04:12.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401452\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 4, \"created_at\": \"2025-12-22T11:04:12.000000Z\", \"keterangan\": \"Pembelian baru\", \"updated_at\": \"2025-12-22T11:04:12.000000Z\", \"kode_transaksi\": \"TM-1766401452-003\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:12', '2025-12-22 11:04:12');
INSERT INTO `log_aktivitas` VALUES ('17', '5', 'create', 'transaksi_barang', '5', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 5, \"barang\": {\"id\": 4, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:12.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T11:04:12.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401452\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 4, \"created_at\": \"2025-12-22T11:04:12.000000Z\", \"updated_at\": \"2025-12-22T11:04:12.000000Z\", \"kode_transaksi\": \"TM-1766401452-004\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"penanggung_jawab\": \"Test\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:12', '2025-12-22 11:04:12');
INSERT INTO `log_aktivitas` VALUES ('18', '5', 'update', 'barang', '4', 'Mengubah data barang: Komputer (ELE-KO-GU-1766401452)', '{\"after\": {\"id\": 4, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22 11:04:12\", \"deleted_at\": null, \"updated_at\": \"2025-12-22 11:04:12\", \"jumlah_stok\": 110, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401452\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"before\": {\"id\": 4, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:12.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T11:04:12.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401452\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:12', '2025-12-22 11:04:12');
INSERT INTO `log_aktivitas` VALUES ('19', '5', 'create', 'transaksi_barang', '6', 'Transaksi masuk barang Komputer sejumlah 10 unit', '{\"id\": 6, \"barang\": {\"id\": 4, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:12.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T11:04:12.000000Z\", \"jumlah_stok\": 110, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401452\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 4, \"created_at\": \"2025-12-22T11:04:12.000000Z\", \"updated_at\": \"2025-12-22T11:04:12.000000Z\", \"kode_transaksi\": \"TM-1766401452-005\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"approved\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:12', '2025-12-22 11:04:12');
INSERT INTO `log_aktivitas` VALUES ('20', '5', 'create', 'barang', '5', 'Menambahkan barang baru: Komputer (ELE-KO-GU-1766401453)', '{\"id\": 5, \"satuan\": \"unit\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:13.000000Z\", \"updated_at\": \"2025-12-22T11:04:13.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401453\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:13', '2025-12-22 11:04:13');
INSERT INTO `log_aktivitas` VALUES ('21', '5', 'update', 'barang', '5', 'Mengubah data barang: Komputer (ELE-KO-GU-1766401453)', '{\"after\": {\"id\": 5, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22 11:04:13\", \"deleted_at\": null, \"updated_at\": \"2025-12-22 11:04:13\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401453\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"before\": {\"id\": 5, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:13.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T11:04:13.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401453\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:13', '2025-12-22 11:04:13');
INSERT INTO `log_aktivitas` VALUES ('22', '5', 'create', 'transaksi_barang', '7', 'Transaksi keluar barang Komputer sejumlah 5 unit', '{\"id\": 7, \"barang\": {\"id\": 5, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T11:04:13.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T11:04:13.000000Z\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766401453\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 5, \"user_id\": 5, \"barang_id\": 5, \"created_at\": \"2025-12-22T11:04:13.000000Z\", \"updated_at\": \"2025-12-22T11:04:13.000000Z\", \"kode_transaksi\": \"TK-1766401453-006\", \"tipe_transaksi\": \"keluar\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 11:04:13', '2025-12-22 11:04:13');
INSERT INTO `log_aktivitas` VALUES ('23', '4', 'create', 'barang', '6', 'Menambahkan barang baru: Kursi Lipat (ELE-KL-GU-1766408817)', '{\"id\": 6, \"status\": \"baik\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:06:57.000000Z\", \"updated_at\": \"2025-12-22T13:06:57.000000Z\", \"jumlah_stok\": 10, \"kategori_id\": 1, \"kode_barang\": \"ELE-KL-GU-1766408817\", \"nama_barang\": \"Kursi Lipat\", \"harga_satuan\": \"50000.00\", \"reorder_point\": 5}', '127.0.0.1', 'Symfony', '2025-12-22 13:06:58', '2025-12-22 13:06:58');
INSERT INTO `log_aktivitas` VALUES ('24', '4', 'create', 'barang', '7', 'Menambahkan barang baru: Komputer Dell 1766408818 (ELE-KD-GU-002)', '{\"id\": 7, \"status\": \"baik\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:06:58.000000Z\", \"updated_at\": \"2025-12-22T13:06:58.000000Z\", \"jumlah_stok\": 5, \"kategori_id\": 1, \"kode_barang\": \"ELE-KD-GU-002\", \"nama_barang\": \"Komputer Dell 1766408818\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 2}', '127.0.0.1', 'Symfony', '2025-12-22 13:06:58', '2025-12-22 13:06:58');
INSERT INTO `log_aktivitas` VALUES ('25', '5', 'create', 'barang', '8', 'Menambahkan barang baru: Komputer (ELE-KO-GU-1766408819)', '{\"id\": 8, \"satuan\": \"unit\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:06:59.000000Z\", \"updated_at\": \"2025-12-22T13:06:59.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408819\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\"}', '127.0.0.1', 'Symfony', '2025-12-22 13:06:59', '2025-12-22 13:06:59');
INSERT INTO `log_aktivitas` VALUES ('26', '5', 'create', 'transaksi_barang', '8', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 8, \"barang\": {\"id\": 8, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:06:59.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T13:06:59.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408819\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 8, \"created_at\": \"2025-12-22T13:06:59.000000Z\", \"updated_at\": \"2025-12-22T13:06:59.000000Z\", \"kode_transaksi\": \"TM-1766408819-001\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 13:06:59', '2025-12-22 13:06:59');
INSERT INTO `log_aktivitas` VALUES ('27', '5', 'create', 'barang', '9', 'Menambahkan barang baru: Komputer (ELE-KO-GU-1766408820)', '{\"id\": 9, \"satuan\": \"unit\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:07:00.000000Z\", \"updated_at\": \"2025-12-22T13:07:00.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408820\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\"}', '127.0.0.1', 'Symfony', '2025-12-22 13:07:00', '2025-12-22 13:07:00');
INSERT INTO `log_aktivitas` VALUES ('28', '5', 'update', 'barang', '9', 'Mengubah data barang: Komputer (ELE-KO-GU-1766408820)', '{\"after\": {\"id\": 9, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22 13:07:00\", \"deleted_at\": null, \"updated_at\": \"2025-12-22 13:07:00\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408820\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"before\": {\"id\": 9, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:07:00.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T13:07:00.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408820\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}}', '127.0.0.1', 'Symfony', '2025-12-22 13:07:00', '2025-12-22 13:07:00');
INSERT INTO `log_aktivitas` VALUES ('29', '5', 'create', 'transaksi_barang', '9', 'Transaksi keluar barang Komputer sejumlah 5 unit', '{\"id\": 9, \"barang\": {\"id\": 9, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:07:00.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T13:07:00.000000Z\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408820\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 5, \"user_id\": 5, \"barang_id\": 9, \"created_at\": \"2025-12-22T13:07:00.000000Z\", \"updated_at\": \"2025-12-22T13:07:00.000000Z\", \"kode_transaksi\": \"TK-1766408820-001\", \"tipe_transaksi\": \"keluar\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 13:07:00', '2025-12-22 13:07:00');
INSERT INTO `log_aktivitas` VALUES ('30', '5', 'create', 'transaksi_barang', '10', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 10, \"barang\": {\"id\": 9, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:07:00.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T13:07:00.000000Z\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408820\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 9, \"created_at\": \"2025-12-22T13:07:00.000000Z\", \"updated_at\": \"2025-12-22T13:07:00.000000Z\", \"kode_transaksi\": \"TM-1766408820-002\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"penanggung_jawab\": \"John Doe\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 13:07:00', '2025-12-22 13:07:00');
INSERT INTO `log_aktivitas` VALUES ('31', '5', 'create', 'transaksi_barang', '11', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 11, \"barang\": {\"id\": 9, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:07:00.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T13:07:00.000000Z\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408820\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 9, \"created_at\": \"2025-12-22T13:07:00.000000Z\", \"keterangan\": \"Pembelian baru\", \"updated_at\": \"2025-12-22T13:07:00.000000Z\", \"kode_transaksi\": \"TM-1766408820-003\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 13:07:00', '2025-12-22 13:07:00');
INSERT INTO `log_aktivitas` VALUES ('32', '5', 'create', 'barang', '10', 'Menambahkan barang baru: Komputer (ELE-KO-GU-1766408821)', '{\"id\": 10, \"satuan\": \"unit\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:07:01.000000Z\", \"updated_at\": \"2025-12-22T13:07:01.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408821\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\"}', '127.0.0.1', 'Symfony', '2025-12-22 13:07:01', '2025-12-22 13:07:01');
INSERT INTO `log_aktivitas` VALUES ('33', '5', 'create', 'transaksi_barang', '12', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 12, \"barang\": {\"id\": 10, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:07:01.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T13:07:01.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408821\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 10, \"created_at\": \"2025-12-22T13:07:01.000000Z\", \"updated_at\": \"2025-12-22T13:07:01.000000Z\", \"kode_transaksi\": \"TM-1766408821-004\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"penanggung_jawab\": \"Test\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 13:07:01', '2025-12-22 13:07:01');
INSERT INTO `log_aktivitas` VALUES ('34', '5', 'update', 'barang', '10', 'Mengubah data barang: Komputer (ELE-KO-GU-1766408821)', '{\"after\": {\"id\": 10, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22 13:07:01\", \"deleted_at\": null, \"updated_at\": \"2025-12-22 13:07:01\", \"jumlah_stok\": 110, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408821\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"before\": {\"id\": 10, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:07:01.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T13:07:01.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408821\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}}', '127.0.0.1', 'Symfony', '2025-12-22 13:07:01', '2025-12-22 13:07:01');
INSERT INTO `log_aktivitas` VALUES ('35', '5', 'create', 'transaksi_barang', '13', 'Transaksi masuk barang Komputer sejumlah 10 unit', '{\"id\": 13, \"barang\": {\"id\": 10, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:07:01.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T13:07:01.000000Z\", \"jumlah_stok\": 110, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408821\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 10, \"created_at\": \"2025-12-22T13:07:01.000000Z\", \"updated_at\": \"2025-12-22T13:07:01.000000Z\", \"kode_transaksi\": \"TM-1766408821-005\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"approved\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 13:07:01', '2025-12-22 13:07:01');
INSERT INTO `log_aktivitas` VALUES ('36', '5', 'update', 'barang', '10', 'Mengubah data barang: Komputer (ELE-KO-GU-1766408821)', '{\"after\": {\"id\": 10, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22 13:07:01\", \"deleted_at\": null, \"updated_at\": \"2025-12-22 13:07:01\", \"jumlah_stok\": 105, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408821\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"before\": {\"id\": 10, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:07:01.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T13:07:01.000000Z\", \"jumlah_stok\": 110, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408821\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}}', '127.0.0.1', 'Symfony', '2025-12-22 13:07:02', '2025-12-22 13:07:02');
INSERT INTO `log_aktivitas` VALUES ('37', '5', 'create', 'transaksi_barang', '14', 'Transaksi keluar barang Komputer sejumlah 5 unit', '{\"id\": 14, \"barang\": {\"id\": 10, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T13:07:01.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T13:07:01.000000Z\", \"jumlah_stok\": 105, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766408821\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 5, \"user_id\": 5, \"barang_id\": 10, \"created_at\": \"2025-12-22T13:07:01.000000Z\", \"updated_at\": \"2025-12-22T13:07:01.000000Z\", \"kode_transaksi\": \"TK-1766408821-006\", \"tipe_transaksi\": \"keluar\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 13:07:02', '2025-12-22 13:07:02');
INSERT INTO `log_aktivitas` VALUES ('38', '1', 'login', NULL, NULL, 'User berhasil login ke sistem', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 16:58:14', '2025-12-22 16:58:14');
INSERT INTO `log_aktivitas` VALUES ('39', '1', 'create', 'kategori', '3', 'Menambahkan kategori baru: C0NT0H', '{\"id\": 3, \"deskripsi\": \"Contoh\", \"created_at\": \"2025-12-22T17:05:27.000000Z\", \"updated_at\": \"2025-12-22T17:05:27.000000Z\", \"nama_kategori\": \"C0NT0H\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 17:05:27', '2025-12-22 17:05:27');
INSERT INTO `log_aktivitas` VALUES ('40', '1', 'update', 'kategori', '3', 'Mengubah data kategori: C0NT0H', '{\"after\": {\"id\": 3, \"deskripsi\": \"Contoh Keterangan\", \"created_at\": \"2025-12-22 17:05:27\", \"deleted_at\": null, \"updated_at\": \"2025-12-22 17:08:08\", \"nama_kategori\": \"C0NT0H\"}, \"before\": {\"id\": 3, \"deskripsi\": \"Contoh\", \"created_at\": \"2025-12-22T17:05:27.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:05:27.000000Z\", \"nama_kategori\": \"C0NT0H\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 17:08:08', '2025-12-22 17:08:08');

-- Structure for table `lokasi`
DROP TABLE IF EXISTS `lokasi`;
CREATE TABLE `lokasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_lokasi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gedung` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lantai` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lokasi_nama_lokasi_index` (`nama_lokasi`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `lokasi`
INSERT INTO `lokasi` VALUES ('1', 'Ruang Guru', NULL, NULL, NULL, '2025-12-22 11:04:08', '2025-12-22 11:04:08', NULL);
INSERT INTO `lokasi` VALUES ('2', 'Lab Komputer', NULL, NULL, NULL, '2025-12-22 11:04:10', '2025-12-22 11:04:10', NULL);
INSERT INTO `lokasi` VALUES ('3', 'Ruang Kepala Sekolah', NULL, NULL, NULL, '2025-12-22 11:04:10', '2025-12-22 11:04:10', NULL);
INSERT INTO `lokasi` VALUES ('4', 'Ruang Kelas 7A', NULL, NULL, NULL, '2025-12-22 11:04:10', '2025-12-22 11:04:10', NULL);

-- Structure for table `migrations`
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `migrations`
INSERT INTO `migrations` VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` VALUES ('4', '2025_12_18_120025_create_permission_tables', '1');
INSERT INTO `migrations` VALUES ('5', '2025_12_18_120100_create_kategori_table', '1');
INSERT INTO `migrations` VALUES ('6', '2025_12_18_120101_create_lokasi_table', '1');
INSERT INTO `migrations` VALUES ('7', '2025_12_18_120102_create_barang_table', '1');
INSERT INTO `migrations` VALUES ('8', '2025_12_18_120102_create_transaksi_barang_table', '1');
INSERT INTO `migrations` VALUES ('9', '2025_12_18_120104_create_log_aktivitas_table', '1');
INSERT INTO `migrations` VALUES ('10', '2025_12_18_120105_add_role_to_users_table', '1');
INSERT INTO `migrations` VALUES ('11', '2025_12_21_061109_create_barang_rusaks_table', '1');
INSERT INTO `migrations` VALUES ('12', '2025_12_21_113720_create_backup_logs_table', '1');
INSERT INTO `migrations` VALUES ('13', '2025_12_21_143000_add_approval_fields_to_transaksi_barang_table', '1');

-- Structure for table `model_has_permissions`
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `model_has_roles`
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `model_has_roles`
INSERT INTO `model_has_roles` VALUES ('1', 'App\\Models\\User', '1');
INSERT INTO `model_has_roles` VALUES ('2', 'App\\Models\\User', '2');
INSERT INTO `model_has_roles` VALUES ('3', 'App\\Models\\User', '3');

-- Structure for table `password_reset_tokens`
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `permissions`
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `permissions`
INSERT INTO `permissions` VALUES ('1', 'view_users', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('2', 'create_users', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('3', 'edit_users', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('4', 'delete_users', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('5', 'reset_password', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('6', 'view_kategoris', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('7', 'create_kategoris', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('8', 'edit_kategoris', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('9', 'delete_kategoris', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('10', 'view_barangs', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('11', 'create_barangs', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('12', 'edit_barangs', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('13', 'delete_barangs', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('14', 'view_lokasis', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('15', 'create_lokasis', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('16', 'edit_lokasis', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('17', 'delete_lokasis', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('18', 'view_transaksi_barangs', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('19', 'create_transaksi_barangs', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('20', 'edit_transaksi_barangs', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('21', 'delete_transaksi_barangs', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('22', 'approve_transaksi_barangs', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('23', 'view_barang_rusaks', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('24', 'create_barang_rusaks', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('25', 'edit_barang_rusaks', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('26', 'delete_barang_rusaks', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('27', 'view_log_aktivitas', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('28', 'delete_log_aktivitas', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('29', 'view_dashboard', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('30', 'view_laporan', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('31', 'backup_system', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `permissions` VALUES ('32', 'restore_system', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');

-- Structure for table `role_has_permissions`
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `role_has_permissions`
INSERT INTO `role_has_permissions` VALUES ('1', '1');
INSERT INTO `role_has_permissions` VALUES ('2', '1');
INSERT INTO `role_has_permissions` VALUES ('3', '1');
INSERT INTO `role_has_permissions` VALUES ('4', '1');
INSERT INTO `role_has_permissions` VALUES ('5', '1');
INSERT INTO `role_has_permissions` VALUES ('6', '1');
INSERT INTO `role_has_permissions` VALUES ('7', '1');
INSERT INTO `role_has_permissions` VALUES ('8', '1');
INSERT INTO `role_has_permissions` VALUES ('9', '1');
INSERT INTO `role_has_permissions` VALUES ('27', '1');
INSERT INTO `role_has_permissions` VALUES ('28', '1');
INSERT INTO `role_has_permissions` VALUES ('29', '1');
INSERT INTO `role_has_permissions` VALUES ('30', '1');
INSERT INTO `role_has_permissions` VALUES ('31', '1');
INSERT INTO `role_has_permissions` VALUES ('32', '1');
INSERT INTO `role_has_permissions` VALUES ('10', '2');
INSERT INTO `role_has_permissions` VALUES ('11', '2');
INSERT INTO `role_has_permissions` VALUES ('12', '2');
INSERT INTO `role_has_permissions` VALUES ('13', '2');
INSERT INTO `role_has_permissions` VALUES ('18', '2');
INSERT INTO `role_has_permissions` VALUES ('19', '2');
INSERT INTO `role_has_permissions` VALUES ('20', '2');
INSERT INTO `role_has_permissions` VALUES ('21', '2');
INSERT INTO `role_has_permissions` VALUES ('23', '2');
INSERT INTO `role_has_permissions` VALUES ('24', '2');
INSERT INTO `role_has_permissions` VALUES ('25', '2');
INSERT INTO `role_has_permissions` VALUES ('26', '2');
INSERT INTO `role_has_permissions` VALUES ('29', '2');
INSERT INTO `role_has_permissions` VALUES ('30', '2');
INSERT INTO `role_has_permissions` VALUES ('10', '3');
INSERT INTO `role_has_permissions` VALUES ('18', '3');
INSERT INTO `role_has_permissions` VALUES ('22', '3');
INSERT INTO `role_has_permissions` VALUES ('23', '3');
INSERT INTO `role_has_permissions` VALUES ('29', '3');
INSERT INTO `role_has_permissions` VALUES ('30', '3');

-- Structure for table `roles`
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `roles`
INSERT INTO `roles` VALUES ('1', 'Admin Sistem', 'web', '2025-12-22 10:42:18', '2025-12-22 10:42:18');
INSERT INTO `roles` VALUES ('2', 'Petugas Inventaris', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');
INSERT INTO `roles` VALUES ('3', 'Kepala Sekolah', 'web', '2025-12-22 10:42:19', '2025-12-22 10:42:19');

-- Structure for table `sessions`
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `sessions`
INSERT INTO `sessions` VALUES ('lT5un2OeEVf7LPdnr4M9N03KCte7R8x7k6odSWvG', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo5OntzOjY6Il90b2tlbiI7czo0MDoiaTE3VHdSYklQSFBEMW9qamR3d2VkeGZrWEFmajVuTEdQRHhwdUVoNiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQyOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vYmFja3VwLW1hbmFnZXIiO3M6NToicm91dGUiO3M6MzU6ImZpbGFtZW50LmFkbWluLnBhZ2VzLmJhY2t1cC1tYW5hZ2VyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjEyOiJsb2dpbl9sb2dnZWQiO2I6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJHpKOGNBQTdYc1JTaTFCeGRNYlFRUGV1Q0FDb0NBUG9qLmN1S3gzNU9ybEhRVW5hdzJnZmQ2IjtzOjY6InRhYmxlcyI7YTozOntzOjQwOiJhMDg5MjlmMTRmY2U0NzI3YWUxYzIwMGM1MmY5ZDU0NF9jb2x1bW5zIjthOjU6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMzoibmFtYV9rYXRlZ29yaSI7czo1OiJsYWJlbCI7czoxMzoiTmFtYSBLYXRlZ29yaSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToiZGVza3JpcHNpIjtzOjU6ImxhYmVsIjtzOjEwOiJLZXRlcmFuZ2FuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MDt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEyOiJiYXJhbmdfY291bnQiO3M6NToibGFiZWwiO3M6MTM6Ikp1bWxhaCBCYXJhbmciO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjY6IkRpYnVhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidXBkYXRlZF9hdCI7czo1OiJsYWJlbCI7czo2OiJEaXViYWgiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319czo0MDoiZTY0NDgzM2Y0ZTRlMDg3MTIzMTVkYTcxYjMzZmFjZDJfY29sdW1ucyI7YTo3OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czoxMzoiTmFtYSBQZW5nZ3VuYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NToiZW1haWwiO3M6NToibGFiZWwiO3M6NToiRW1haWwiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJyb2xlcy5uYW1lIjtzOjU6ImxhYmVsIjtzOjU6IlBlcmFuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJpc19hY3RpdmUiO3M6NToibGFiZWwiO3M6NToiQWt0aWYiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE3OiJlbWFpbF92ZXJpZmllZF9hdCI7czo1OiJsYWJlbCI7czoxODoiRW1haWwgRGl2ZXJpZmlrYXNpIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjExOiJEaWJ1YXQgUGFkYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidXBkYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxNToiRGlwZXJiYXJ1aSBQYWRhIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6ImQxYTZmY2QzZDYzYTVkODg4ZjMwZWI5NmQ4NTk3MjZlX2NvbHVtbnMiO2E6ODp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjc6IlRhbmdnYWwiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE1OiJqZW5pc19ha3Rpdml0YXMiO3M6NToibGFiZWwiO3M6OToiQWt0aXZpdGFzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoibmFtYV90YWJlbCI7czo1OiJsYWJlbCI7czo1OiJUYWJlbCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjA7fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJ1c2VyLm5hbWUiO3M6NToibGFiZWwiO3M6NDoiVXNlciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToidXNlci5yb2xlIjtzOjU6ImxhYmVsIjtzOjQ6IlJvbGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjowO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToiZGVza3JpcHNpIjtzOjU6ImxhYmVsIjtzOjk6IkRlc2tyaXBzaSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjA7fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiaXBfYWRkcmVzcyI7czo1OiJsYWJlbCI7czoxMDoiSVAgQWRkcmVzcyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6NzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidXNlcl9hZ2VudCI7czo1OiJsYWJlbCI7czoxMDoiVXNlciBBZ2VudCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX19czo4OiJmaWxhbWVudCI7YTowOnt9fQ==', '1766423909');
INSERT INTO `sessions` VALUES ('vmcVbama6Oy8dYjP2mJ35El3chOfVVVpPkNBV4Nq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.1 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia2I4bEhJa3cyMVN0WlBvY0pSZ0lZMGk0aVB3SFlSdFkzamFoVXNCRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', '1766420553');

-- Structure for table `transaksi_barang`
DROP TABLE IF EXISTS `transaksi_barang`;
CREATE TABLE `transaksi_barang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barang_id` bigint unsigned NOT NULL,
  `tipe_transaksi` enum('masuk','keluar') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `penanggung_jawab` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approval_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaksi_barang_kode_transaksi_unique` (`kode_transaksi`),
  KEY `transaksi_barang_user_id_foreign` (`user_id`),
  KEY `transaksi_barang_barang_id_tipe_transaksi_index` (`barang_id`,`tipe_transaksi`),
  KEY `transaksi_barang_tanggal_transaksi_index` (`tanggal_transaksi`),
  KEY `transaksi_barang_kode_transaksi_index` (`kode_transaksi`),
  KEY `transaksi_barang_approved_by_foreign` (`approved_by`),
  CONSTRAINT `transaksi_barang_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaksi_barang_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_barang_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `transaksi_barang`
INSERT INTO `transaksi_barang` VALUES ('1', 'TM-1766401451-001', '3', 'masuk', '10', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 11:04:11', '2025-12-22 11:04:11');
INSERT INTO `transaksi_barang` VALUES ('2', 'TK-1766401451-001', '3', 'keluar', '5', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 11:04:11', '2025-12-22 11:04:11');
INSERT INTO `transaksi_barang` VALUES ('3', 'TM-1766401452-002', '4', 'masuk', '10', '2025-12-22', 'John Doe', NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 11:04:12', '2025-12-22 11:04:12');
INSERT INTO `transaksi_barang` VALUES ('4', 'TM-1766401452-003', '4', 'masuk', '10', '2025-12-22', NULL, 'Pembelian baru', '5', NULL, NULL, 'pending', NULL, '2025-12-22 11:04:12', '2025-12-22 11:04:12');
INSERT INTO `transaksi_barang` VALUES ('5', 'TM-1766401452-004', '4', 'masuk', '10', '2025-12-22', 'Test', NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 11:04:12', '2025-12-22 11:04:12');
INSERT INTO `transaksi_barang` VALUES ('6', 'TM-1766401452-005', '4', 'masuk', '10', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'approved', NULL, '2025-12-22 11:04:12', '2025-12-22 11:04:12');
INSERT INTO `transaksi_barang` VALUES ('7', 'TK-1766401453-006', '5', 'keluar', '5', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 11:04:13', '2025-12-22 11:04:13');
INSERT INTO `transaksi_barang` VALUES ('8', 'TM-1766408819-001', '8', 'masuk', '10', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 13:06:59', '2025-12-22 13:06:59');
INSERT INTO `transaksi_barang` VALUES ('9', 'TK-1766408820-001', '9', 'keluar', '5', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 13:07:00', '2025-12-22 13:07:00');
INSERT INTO `transaksi_barang` VALUES ('10', 'TM-1766408820-002', '9', 'masuk', '10', '2025-12-22', 'John Doe', NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 13:07:00', '2025-12-22 13:07:00');
INSERT INTO `transaksi_barang` VALUES ('11', 'TM-1766408820-003', '9', 'masuk', '10', '2025-12-22', NULL, 'Pembelian baru', '5', NULL, NULL, 'pending', NULL, '2025-12-22 13:07:00', '2025-12-22 13:07:00');
INSERT INTO `transaksi_barang` VALUES ('12', 'TM-1766408821-004', '10', 'masuk', '10', '2025-12-22', 'Test', NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 13:07:01', '2025-12-22 13:07:01');
INSERT INTO `transaksi_barang` VALUES ('13', 'TM-1766408821-005', '10', 'masuk', '10', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'approved', NULL, '2025-12-22 13:07:01', '2025-12-22 13:07:01');
INSERT INTO `transaksi_barang` VALUES ('14', 'TK-1766408821-006', '10', 'keluar', '5', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 13:07:01', '2025-12-22 13:07:01');

-- Structure for table `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `users`
INSERT INTO `users` VALUES ('1', 'Administrator', 'admin@inventaris.test', '2025-12-22 10:42:19', '$2y$12$zJ8cAA7XsRSi1BxdMbQQPeuCACoCAPoj.cuKx35OrlHQUnaw2gfd6', NULL, '2025-12-22 10:42:19', '2025-12-22 10:42:19', '1', NULL);
INSERT INTO `users` VALUES ('2', 'Petugas Inventaris', 'petugas@inventaris.test', '2025-12-22 10:42:20', '$2y$12$88Yxb9.CKoMoxVLN4oY2A.zLivYCWXf50uAoWfsRbfjFycptfL6ay', NULL, '2025-12-22 10:42:20', '2025-12-22 10:42:20', '1', NULL);
INSERT INTO `users` VALUES ('3', 'Kepala Sekolah', 'kepala@inventaris.test', '2025-12-22 10:42:20', '$2y$12$SHZRdGIDqSuZcP.fnR5HoOYlEKd7y/Wh6LaHKUNH5nlLVgaRxG8Qm', NULL, '2025-12-22 10:42:20', '2025-12-22 10:42:20', '1', NULL);
INSERT INTO `users` VALUES ('4', 'Admin User', 'admin@example.com', NULL, '$2y$12$E/1j8uIfL7xorZ7t7AVJS.dVW7QeyMow/nLk.MWt5PJMwgaohmDX.', NULL, '2025-12-22 11:04:08', '2025-12-22 11:04:08', '1', NULL);
INSERT INTO `users` VALUES ('5', 'Test User', 'testuser@example.com', NULL, '$2y$12$LrdIUsEArDk3KU81etlKf.SMCQ4UImILVVCcCGlXYPcizc/MLvsv.', NULL, '2025-12-22 11:04:11', '2025-12-22 11:04:11', '1', NULL);

SET FOREIGN_KEY_CHECKS=1;
