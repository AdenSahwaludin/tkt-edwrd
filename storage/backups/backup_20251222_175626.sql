-- MySQL Dump
-- Generated at: 2025-12-22 17:56:26
-- Database: tkt_warehouse

SET FOREIGN_KEY_CHECKS=0;

-- Structure for table `backup_logs`
DROP TABLE IF EXISTS `backup_logs`;
CREATE TABLE `backup_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `format` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sql',
  `file_size` bigint unsigned DEFAULT NULL,
  `status` enum('success','failed','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `backup_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `backup_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `barang`
DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_id` bigint unsigned NOT NULL,
  `lokasi_id` bigint unsigned NOT NULL,
  `jumlah_stok` int NOT NULL DEFAULT '0',
  `reorder_point` int NOT NULL DEFAULT '10' COMMENT 'Batas minimal stok sebelum perlu pemesanan ulang',
  `satuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pcs',
  `status` enum('baik','rusak','hilang') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baik',
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `harga_satuan` decimal(15,2) DEFAULT NULL,
  `merk` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `barang`
INSERT INTO `barang` VALUES ('1', 'ELE-KL-GU-1766424721', 'Kursi Lipat', '1', '1', '10', '5', 'pcs', 'baik', NULL, '50000.00', NULL, NULL, '2025-12-22 17:32:01', '2025-12-22 17:32:01', NULL);
INSERT INTO `barang` VALUES ('2', 'ELE-KD-GU-001', 'Komputer Dell 1766424721', '1', '1', '5', '2', 'pcs', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 17:32:01', '2025-12-22 17:32:01', NULL);
INSERT INTO `barang` VALUES ('3', 'ELE-KO-GU-1766424722', 'Komputer', '1', '1', '100', '10', 'unit', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 17:32:02', '2025-12-22 17:32:02', NULL);
INSERT INTO `barang` VALUES ('4', 'ELE-KO-GU-1766424723', 'Komputer', '1', '1', '95', '10', 'unit', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 17:32:03', '2025-12-22 17:32:03', NULL);
INSERT INTO `barang` VALUES ('5', 'ELE-KO-GU-1766424724', 'Komputer', '1', '1', '105', '10', 'unit', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 17:32:04', '2025-12-22 17:32:04', NULL);
INSERT INTO `barang` VALUES ('6', 'ELE-KL-GU-1766425467', 'Kursi Lipat', '1', '1', '10', '5', 'pcs', 'baik', NULL, '50000.00', NULL, NULL, '2025-12-22 17:44:27', '2025-12-22 17:44:27', NULL);
INSERT INTO `barang` VALUES ('7', 'ELE-KD-GU-002', 'Komputer Dell 1766425467', '1', '1', '5', '2', 'pcs', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 17:44:27', '2025-12-22 17:44:27', NULL);
INSERT INTO `barang` VALUES ('8', 'ELE-KO-GU-1766425468', 'Komputer', '1', '1', '95', '10', 'unit', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 17:44:28', '2025-12-22 17:44:28', NULL);
INSERT INTO `barang` VALUES ('9', 'ELE-KO-GU-1766425469', 'Komputer', '1', '1', '100', '10', 'unit', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 17:44:29', '2025-12-22 17:44:29', NULL);
INSERT INTO `barang` VALUES ('10', 'ELE-KO-GU-1766425470', 'Komputer', '1', '1', '105', '10', 'unit', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 17:44:30', '2025-12-22 17:44:30', NULL);
INSERT INTO `barang` VALUES ('11', 'ELE-KL-GU-1766426182', 'Kursi Lipat', '1', '1', '10', '5', 'pcs', 'baik', NULL, '50000.00', NULL, NULL, '2025-12-22 17:56:22', '2025-12-22 17:56:22', NULL);
INSERT INTO `barang` VALUES ('12', 'ELE-KD-GU-003', 'Komputer Dell 1766426182', '1', '1', '5', '2', 'pcs', 'baik', NULL, '5000000.00', NULL, NULL, '2025-12-22 17:56:22', '2025-12-22 17:56:22', NULL);

-- Structure for table `barang_rusaks`
DROP TABLE IF EXISTS `barang_rusaks`;
CREATE TABLE `barang_rusaks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `barang_id` bigint unsigned NOT NULL,
  `jumlah` int NOT NULL,
  `tanggal_kejadian` date NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `penanggung_jawab` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `cache_locks`
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `failed_jobs`
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `job_batches`
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `jobs`
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `nama_kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kategori_nama_kategori_index` (`nama_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `kategori`
INSERT INTO `kategori` VALUES ('1', 'Elektronik', NULL, '2025-12-22 17:32:00', '2025-12-22 17:32:00', NULL);
INSERT INTO `kategori` VALUES ('2', 'Furniture Kantor', NULL, '2025-12-22 17:32:02', '2025-12-22 17:32:02', NULL);

-- Structure for table `log_aktivitas`
DROP TABLE IF EXISTS `log_aktivitas`;
CREATE TABLE `log_aktivitas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `jenis_aktivitas` enum('login','logout','create','update','delete','view') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_tabel` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama tabel yang terpengaruh',
  `record_id` bigint unsigned DEFAULT NULL COMMENT 'ID record yang terpengaruh',
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `perubahan_data` json DEFAULT NULL COMMENT 'Data perubahan dalam format JSON',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_aktivitas_user_id_jenis_aktivitas_created_at_index` (`user_id`,`jenis_aktivitas`,`created_at`),
  KEY `log_aktivitas_nama_tabel_record_id_index` (`nama_tabel`,`record_id`),
  KEY `log_aktivitas_created_at_index` (`created_at`),
  CONSTRAINT `log_aktivitas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `log_aktivitas`
INSERT INTO `log_aktivitas` VALUES ('1', '4', 'create', 'kategori', '1', 'Menambahkan kategori baru: Elektronik', '{\"id\": 1, \"created_at\": \"2025-12-22T17:32:00.000000Z\", \"updated_at\": \"2025-12-22T17:32:00.000000Z\", \"nama_kategori\": \"Elektronik\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:00', '2025-12-22 17:32:00');
INSERT INTO `log_aktivitas` VALUES ('2', '4', 'create', 'lokasi', '1', 'Menambahkan lokasi baru: Ruang Guru', '{\"id\": 1, \"created_at\": \"2025-12-22T17:32:00.000000Z\", \"updated_at\": \"2025-12-22T17:32:00.000000Z\", \"nama_lokasi\": \"Ruang Guru\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:00', '2025-12-22 17:32:00');
INSERT INTO `log_aktivitas` VALUES ('3', '4', 'create', 'barang', '1', 'Menambahkan barang baru: Kursi Lipat (ELE-KL-GU-1766424721)', '{\"id\": 1, \"status\": \"baik\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:01.000000Z\", \"updated_at\": \"2025-12-22T17:32:01.000000Z\", \"jumlah_stok\": 10, \"kategori_id\": 1, \"kode_barang\": \"ELE-KL-GU-1766424721\", \"nama_barang\": \"Kursi Lipat\", \"harga_satuan\": \"50000.00\", \"reorder_point\": 5}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:01', '2025-12-22 17:32:01');
INSERT INTO `log_aktivitas` VALUES ('4', '4', 'create', 'barang', '2', 'Menambahkan barang baru: Komputer Dell 1766424721 (ELE-KD-GU-001)', '{\"id\": 2, \"status\": \"baik\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:01.000000Z\", \"updated_at\": \"2025-12-22T17:32:01.000000Z\", \"jumlah_stok\": 5, \"kategori_id\": 1, \"kode_barang\": \"ELE-KD-GU-001\", \"nama_barang\": \"Komputer Dell 1766424721\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 2}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:01', '2025-12-22 17:32:01');
INSERT INTO `log_aktivitas` VALUES ('5', '4', 'create', 'lokasi', '2', 'Menambahkan lokasi baru: Lab Komputer', '{\"id\": 2, \"created_at\": \"2025-12-22T17:32:02.000000Z\", \"updated_at\": \"2025-12-22T17:32:02.000000Z\", \"nama_lokasi\": \"Lab Komputer\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:02', '2025-12-22 17:32:02');
INSERT INTO `log_aktivitas` VALUES ('6', '4', 'create', 'lokasi', '3', 'Menambahkan lokasi baru: Ruang Kepala Sekolah', '{\"id\": 3, \"created_at\": \"2025-12-22T17:32:02.000000Z\", \"updated_at\": \"2025-12-22T17:32:02.000000Z\", \"nama_lokasi\": \"Ruang Kepala Sekolah\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:02', '2025-12-22 17:32:02');
INSERT INTO `log_aktivitas` VALUES ('7', '4', 'create', 'lokasi', '4', 'Menambahkan lokasi baru: Ruang Kelas 7A', '{\"id\": 4, \"created_at\": \"2025-12-22T17:32:02.000000Z\", \"updated_at\": \"2025-12-22T17:32:02.000000Z\", \"nama_lokasi\": \"Ruang Kelas 7A\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:02', '2025-12-22 17:32:02');
INSERT INTO `log_aktivitas` VALUES ('8', '4', 'create', 'kategori', '2', 'Menambahkan kategori baru: Furniture Kantor', '{\"id\": 2, \"created_at\": \"2025-12-22T17:32:02.000000Z\", \"updated_at\": \"2025-12-22T17:32:02.000000Z\", \"nama_kategori\": \"Furniture Kantor\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:02', '2025-12-22 17:32:02');
INSERT INTO `log_aktivitas` VALUES ('9', '5', 'create', 'barang', '3', 'Menambahkan barang baru: Komputer (ELE-KO-GU-1766424722)', '{\"id\": 3, \"satuan\": \"unit\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:02.000000Z\", \"updated_at\": \"2025-12-22T17:32:02.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424722\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:02', '2025-12-22 17:32:02');
INSERT INTO `log_aktivitas` VALUES ('10', '5', 'create', 'transaksi_barang', '1', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 1, \"barang\": {\"id\": 3, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:02.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:32:02.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424722\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 3, \"created_at\": \"2025-12-22T17:32:02.000000Z\", \"updated_at\": \"2025-12-22T17:32:02.000000Z\", \"kode_transaksi\": \"TM-1766424722-001\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:02', '2025-12-22 17:32:02');
INSERT INTO `log_aktivitas` VALUES ('11', '5', 'create', 'barang', '4', 'Menambahkan barang baru: Komputer (ELE-KO-GU-1766424723)', '{\"id\": 4, \"satuan\": \"unit\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:03.000000Z\", \"updated_at\": \"2025-12-22T17:32:03.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424723\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:03', '2025-12-22 17:32:03');
INSERT INTO `log_aktivitas` VALUES ('12', '5', 'update', 'barang', '4', 'Mengubah data barang: Komputer (ELE-KO-GU-1766424723)', '{\"after\": {\"id\": 4, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22 17:32:03\", \"deleted_at\": null, \"updated_at\": \"2025-12-22 17:32:03\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424723\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"before\": {\"id\": 4, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:03.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:32:03.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424723\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:03', '2025-12-22 17:32:03');
INSERT INTO `log_aktivitas` VALUES ('13', '5', 'create', 'transaksi_barang', '2', 'Transaksi keluar barang Komputer sejumlah 5 unit', '{\"id\": 2, \"barang\": {\"id\": 4, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:03.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:32:03.000000Z\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424723\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 5, \"user_id\": 5, \"barang_id\": 4, \"created_at\": \"2025-12-22T17:32:03.000000Z\", \"updated_at\": \"2025-12-22T17:32:03.000000Z\", \"kode_transaksi\": \"TK-1766424723-001\", \"tipe_transaksi\": \"keluar\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:03', '2025-12-22 17:32:03');
INSERT INTO `log_aktivitas` VALUES ('14', '5', 'create', 'transaksi_barang', '3', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 3, \"barang\": {\"id\": 4, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:03.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:32:03.000000Z\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424723\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 4, \"created_at\": \"2025-12-22T17:32:03.000000Z\", \"updated_at\": \"2025-12-22T17:32:03.000000Z\", \"kode_transaksi\": \"TM-1766424723-002\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"penanggung_jawab\": \"John Doe\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:03', '2025-12-22 17:32:03');
INSERT INTO `log_aktivitas` VALUES ('15', '5', 'create', 'transaksi_barang', '4', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 4, \"barang\": {\"id\": 4, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:03.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:32:03.000000Z\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424723\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 4, \"created_at\": \"2025-12-22T17:32:03.000000Z\", \"keterangan\": \"Pembelian baru\", \"updated_at\": \"2025-12-22T17:32:03.000000Z\", \"kode_transaksi\": \"TM-1766424723-003\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:03', '2025-12-22 17:32:03');
INSERT INTO `log_aktivitas` VALUES ('16', '5', 'create', 'barang', '5', 'Menambahkan barang baru: Komputer (ELE-KO-GU-1766424724)', '{\"id\": 5, \"satuan\": \"unit\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:04.000000Z\", \"updated_at\": \"2025-12-22T17:32:04.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424724\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:04', '2025-12-22 17:32:04');
INSERT INTO `log_aktivitas` VALUES ('17', '5', 'create', 'transaksi_barang', '5', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 5, \"barang\": {\"id\": 5, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:04.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:32:04.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424724\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 5, \"created_at\": \"2025-12-22T17:32:04.000000Z\", \"updated_at\": \"2025-12-22T17:32:04.000000Z\", \"kode_transaksi\": \"TM-1766424724-004\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"penanggung_jawab\": \"Test\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:04', '2025-12-22 17:32:04');
INSERT INTO `log_aktivitas` VALUES ('18', '5', 'update', 'barang', '5', 'Mengubah data barang: Komputer (ELE-KO-GU-1766424724)', '{\"after\": {\"id\": 5, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22 17:32:04\", \"deleted_at\": null, \"updated_at\": \"2025-12-22 17:32:04\", \"jumlah_stok\": 110, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424724\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"before\": {\"id\": 5, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:04.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:32:04.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424724\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:04', '2025-12-22 17:32:04');
INSERT INTO `log_aktivitas` VALUES ('19', '5', 'create', 'transaksi_barang', '6', 'Transaksi masuk barang Komputer sejumlah 10 unit', '{\"id\": 6, \"barang\": {\"id\": 5, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:04.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:32:04.000000Z\", \"jumlah_stok\": 110, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424724\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 5, \"created_at\": \"2025-12-22T17:32:04.000000Z\", \"updated_at\": \"2025-12-22T17:32:04.000000Z\", \"kode_transaksi\": \"TM-1766424724-005\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"approved\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:04', '2025-12-22 17:32:04');
INSERT INTO `log_aktivitas` VALUES ('20', '5', 'update', 'barang', '5', 'Mengubah data barang: Komputer (ELE-KO-GU-1766424724)', '{\"after\": {\"id\": 5, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22 17:32:04\", \"deleted_at\": null, \"updated_at\": \"2025-12-22 17:32:04\", \"jumlah_stok\": 105, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424724\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"before\": {\"id\": 5, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:04.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:32:04.000000Z\", \"jumlah_stok\": 110, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424724\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:04', '2025-12-22 17:32:04');
INSERT INTO `log_aktivitas` VALUES ('21', '5', 'create', 'transaksi_barang', '7', 'Transaksi keluar barang Komputer sejumlah 5 unit', '{\"id\": 7, \"barang\": {\"id\": 5, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:32:04.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:32:04.000000Z\", \"jumlah_stok\": 105, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766424724\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 5, \"user_id\": 5, \"barang_id\": 5, \"created_at\": \"2025-12-22T17:32:04.000000Z\", \"updated_at\": \"2025-12-22T17:32:04.000000Z\", \"kode_transaksi\": \"TK-1766424724-006\", \"tipe_transaksi\": \"keluar\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:32:04', '2025-12-22 17:32:04');
INSERT INTO `log_aktivitas` VALUES ('22', '4', 'create', 'barang', '6', 'Menambahkan barang baru: Kursi Lipat (ELE-KL-GU-1766425467)', '{\"id\": 6, \"status\": \"baik\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:27.000000Z\", \"updated_at\": \"2025-12-22T17:44:27.000000Z\", \"jumlah_stok\": 10, \"kategori_id\": 1, \"kode_barang\": \"ELE-KL-GU-1766425467\", \"nama_barang\": \"Kursi Lipat\", \"harga_satuan\": \"50000.00\", \"reorder_point\": 5}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:27', '2025-12-22 17:44:27');
INSERT INTO `log_aktivitas` VALUES ('23', '4', 'create', 'barang', '7', 'Menambahkan barang baru: Komputer Dell 1766425467 (ELE-KD-GU-002)', '{\"id\": 7, \"status\": \"baik\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:27.000000Z\", \"updated_at\": \"2025-12-22T17:44:27.000000Z\", \"jumlah_stok\": 5, \"kategori_id\": 1, \"kode_barang\": \"ELE-KD-GU-002\", \"nama_barang\": \"Komputer Dell 1766425467\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 2}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:27', '2025-12-22 17:44:27');
INSERT INTO `log_aktivitas` VALUES ('24', '5', 'create', 'barang', '8', 'Menambahkan barang baru: Komputer (ELE-KO-GU-1766425468)', '{\"id\": 8, \"satuan\": \"unit\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:28.000000Z\", \"updated_at\": \"2025-12-22T17:44:28.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425468\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:28', '2025-12-22 17:44:28');
INSERT INTO `log_aktivitas` VALUES ('25', '5', 'create', 'transaksi_barang', '8', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 8, \"barang\": {\"id\": 8, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:28.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:44:28.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425468\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 8, \"created_at\": \"2025-12-22T17:44:28.000000Z\", \"updated_at\": \"2025-12-22T17:44:28.000000Z\", \"kode_transaksi\": \"TM-1766425468-001\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:28', '2025-12-22 17:44:28');
INSERT INTO `log_aktivitas` VALUES ('26', '5', 'update', 'barang', '8', 'Mengubah data barang: Komputer (ELE-KO-GU-1766425468)', '{\"after\": {\"id\": 8, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22 17:44:28\", \"deleted_at\": null, \"updated_at\": \"2025-12-22 17:44:28\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425468\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"before\": {\"id\": 8, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:28.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:44:28.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425468\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:28', '2025-12-22 17:44:28');
INSERT INTO `log_aktivitas` VALUES ('27', '5', 'create', 'transaksi_barang', '9', 'Transaksi keluar barang Komputer sejumlah 5 unit', '{\"id\": 9, \"barang\": {\"id\": 8, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:28.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:44:28.000000Z\", \"jumlah_stok\": 95, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425468\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 5, \"user_id\": 5, \"barang_id\": 8, \"created_at\": \"2025-12-22T17:44:28.000000Z\", \"updated_at\": \"2025-12-22T17:44:28.000000Z\", \"kode_transaksi\": \"TK-1766425468-001\", \"tipe_transaksi\": \"keluar\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:28', '2025-12-22 17:44:28');
INSERT INTO `log_aktivitas` VALUES ('28', '5', 'create', 'barang', '9', 'Menambahkan barang baru: Komputer (ELE-KO-GU-1766425469)', '{\"id\": 9, \"satuan\": \"unit\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:29.000000Z\", \"updated_at\": \"2025-12-22T17:44:29.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425469\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:29', '2025-12-22 17:44:29');
INSERT INTO `log_aktivitas` VALUES ('29', '5', 'create', 'transaksi_barang', '10', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 10, \"barang\": {\"id\": 9, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:29.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:44:29.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425469\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 9, \"created_at\": \"2025-12-22T17:44:29.000000Z\", \"updated_at\": \"2025-12-22T17:44:29.000000Z\", \"kode_transaksi\": \"TM-1766425469-002\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"penanggung_jawab\": \"John Doe\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:29', '2025-12-22 17:44:29');
INSERT INTO `log_aktivitas` VALUES ('30', '5', 'create', 'transaksi_barang', '11', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 11, \"barang\": {\"id\": 9, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:29.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:44:29.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425469\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 9, \"created_at\": \"2025-12-22T17:44:29.000000Z\", \"keterangan\": \"Pembelian baru\", \"updated_at\": \"2025-12-22T17:44:29.000000Z\", \"kode_transaksi\": \"TM-1766425469-003\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:29', '2025-12-22 17:44:29');
INSERT INTO `log_aktivitas` VALUES ('31', '5', 'create', 'transaksi_barang', '12', 'Transaksi masuk barang Komputer sejumlah 10 unit (menunggu persetujuan)', '{\"id\": 12, \"barang\": {\"id\": 9, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:29.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:44:29.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425469\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 9, \"created_at\": \"2025-12-22T17:44:29.000000Z\", \"updated_at\": \"2025-12-22T17:44:29.000000Z\", \"kode_transaksi\": \"TM-1766425469-004\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"pending\", \"penanggung_jawab\": \"Test\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:29', '2025-12-22 17:44:29');
INSERT INTO `log_aktivitas` VALUES ('32', '5', 'create', 'barang', '10', 'Menambahkan barang baru: Komputer (ELE-KO-GU-1766425470)', '{\"id\": 10, \"satuan\": \"unit\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:30.000000Z\", \"updated_at\": \"2025-12-22T17:44:30.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425470\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:30', '2025-12-22 17:44:30');
INSERT INTO `log_aktivitas` VALUES ('33', '5', 'update', 'barang', '10', 'Mengubah data barang: Komputer (ELE-KO-GU-1766425470)', '{\"after\": {\"id\": 10, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22 17:44:30\", \"deleted_at\": null, \"updated_at\": \"2025-12-22 17:44:30\", \"jumlah_stok\": 110, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425470\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"before\": {\"id\": 10, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:30.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:44:30.000000Z\", \"jumlah_stok\": 100, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425470\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:30', '2025-12-22 17:44:30');
INSERT INTO `log_aktivitas` VALUES ('34', '5', 'create', 'transaksi_barang', '13', 'Transaksi masuk barang Komputer sejumlah 10 unit', '{\"id\": 13, \"barang\": {\"id\": 10, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:30.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:44:30.000000Z\", \"jumlah_stok\": 110, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425470\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 10, \"user_id\": 5, \"barang_id\": 10, \"created_at\": \"2025-12-22T17:44:30.000000Z\", \"updated_at\": \"2025-12-22T17:44:30.000000Z\", \"kode_transaksi\": \"TM-1766425470-005\", \"tipe_transaksi\": \"masuk\", \"approval_status\": \"approved\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:30', '2025-12-22 17:44:30');
INSERT INTO `log_aktivitas` VALUES ('35', '5', 'update', 'barang', '10', 'Mengubah data barang: Komputer (ELE-KO-GU-1766425470)', '{\"after\": {\"id\": 10, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22 17:44:30\", \"deleted_at\": null, \"updated_at\": \"2025-12-22 17:44:30\", \"jumlah_stok\": 105, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425470\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"before\": {\"id\": 10, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:30.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:44:30.000000Z\", \"jumlah_stok\": 110, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425470\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:30', '2025-12-22 17:44:30');
INSERT INTO `log_aktivitas` VALUES ('36', '5', 'create', 'transaksi_barang', '14', 'Transaksi keluar barang Komputer sejumlah 5 unit', '{\"id\": 14, \"barang\": {\"id\": 10, \"merk\": null, \"satuan\": \"unit\", \"status\": \"baik\", \"deskripsi\": null, \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:44:30.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T17:44:30.000000Z\", \"jumlah_stok\": 105, \"kategori_id\": 1, \"kode_barang\": \"ELE-KO-GU-1766425470\", \"nama_barang\": \"Komputer\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 10, \"tanggal_pembelian\": null}, \"jumlah\": 5, \"user_id\": 5, \"barang_id\": 10, \"created_at\": \"2025-12-22T17:44:30.000000Z\", \"updated_at\": \"2025-12-22T17:44:30.000000Z\", \"kode_transaksi\": \"TK-1766425470-006\", \"tipe_transaksi\": \"keluar\", \"tanggal_transaksi\": \"2025-12-22T00:00:00.000000Z\"}', '127.0.0.1', 'Symfony', '2025-12-22 17:44:30', '2025-12-22 17:44:30');
INSERT INTO `log_aktivitas` VALUES ('37', '4', 'create', 'barang', '11', 'Menambahkan barang baru: Kursi Lipat (ELE-KL-GU-1766426182)', '{\"id\": 11, \"status\": \"baik\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:56:22.000000Z\", \"updated_at\": \"2025-12-22T17:56:22.000000Z\", \"jumlah_stok\": 10, \"kategori_id\": 1, \"kode_barang\": \"ELE-KL-GU-1766426182\", \"nama_barang\": \"Kursi Lipat\", \"harga_satuan\": \"50000.00\", \"reorder_point\": 5}', '127.0.0.1', 'Symfony', '2025-12-22 17:56:22', '2025-12-22 17:56:22');
INSERT INTO `log_aktivitas` VALUES ('38', '4', 'create', 'barang', '12', 'Menambahkan barang baru: Komputer Dell 1766426182 (ELE-KD-GU-003)', '{\"id\": 12, \"status\": \"baik\", \"lokasi_id\": 1, \"created_at\": \"2025-12-22T17:56:22.000000Z\", \"updated_at\": \"2025-12-22T17:56:22.000000Z\", \"jumlah_stok\": 5, \"kategori_id\": 1, \"kode_barang\": \"ELE-KD-GU-003\", \"nama_barang\": \"Komputer Dell 1766426182\", \"harga_satuan\": \"5000000.00\", \"reorder_point\": 2}', '127.0.0.1', 'Symfony', '2025-12-22 17:56:22', '2025-12-22 17:56:22');

-- Structure for table `lokasi`
DROP TABLE IF EXISTS `lokasi`;
CREATE TABLE `lokasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_lokasi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gedung` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lantai` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lokasi_nama_lokasi_index` (`nama_lokasi`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `lokasi`
INSERT INTO `lokasi` VALUES ('1', 'Ruang Guru', NULL, NULL, NULL, '2025-12-22 17:32:00', '2025-12-22 17:32:00', NULL);
INSERT INTO `lokasi` VALUES ('2', 'Lab Komputer', NULL, NULL, NULL, '2025-12-22 17:32:02', '2025-12-22 17:32:02', NULL);
INSERT INTO `lokasi` VALUES ('3', 'Ruang Kepala Sekolah', NULL, NULL, NULL, '2025-12-22 17:32:02', '2025-12-22 17:32:02', NULL);
INSERT INTO `lokasi` VALUES ('4', 'Ruang Kelas 7A', NULL, NULL, NULL, '2025-12-22 17:32:02', '2025-12-22 17:32:02', NULL);

-- Structure for table `migrations`
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `model_has_roles`
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `permissions`
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `permissions`
INSERT INTO `permissions` VALUES ('1', 'view_users', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('2', 'create_users', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('3', 'edit_users', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('4', 'delete_users', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('5', 'reset_password', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('6', 'view_kategoris', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('7', 'create_kategoris', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('8', 'edit_kategoris', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('9', 'delete_kategoris', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('10', 'view_barangs', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('11', 'create_barangs', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('12', 'edit_barangs', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('13', 'delete_barangs', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('14', 'view_lokasis', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('15', 'create_lokasis', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('16', 'edit_lokasis', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('17', 'delete_lokasis', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('18', 'view_transaksi_barangs', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('19', 'create_transaksi_barangs', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('20', 'edit_transaksi_barangs', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('21', 'delete_transaksi_barangs', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('22', 'approve_transaksi_barangs', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('23', 'view_barang_rusaks', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('24', 'create_barang_rusaks', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('25', 'edit_barang_rusaks', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('26', 'delete_barang_rusaks', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('27', 'view_log_aktivitas', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('28', 'delete_log_aktivitas', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('29', 'view_dashboard', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('30', 'view_laporan', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('31', 'backup_system', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `permissions` VALUES ('32', 'restore_system', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');

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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `roles`
INSERT INTO `roles` VALUES ('1', 'Admin Sistem', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `roles` VALUES ('2', 'Petugas Inventaris', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');
INSERT INTO `roles` VALUES ('3', 'Kepala Sekolah', 'web', '2025-12-22 17:31:36', '2025-12-22 17:31:36');

-- Structure for table `sessions`
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure for table `transaksi_barang`
DROP TABLE IF EXISTS `transaksi_barang`;
CREATE TABLE `transaksi_barang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `barang_id` bigint unsigned NOT NULL,
  `tipe_transaksi` enum('masuk','keluar') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `penanggung_jawab` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approval_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
INSERT INTO `transaksi_barang` VALUES ('1', 'TM-1766424722-001', '3', 'masuk', '10', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 17:32:02', '2025-12-22 17:32:02');
INSERT INTO `transaksi_barang` VALUES ('2', 'TK-1766424723-001', '4', 'keluar', '5', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 17:32:03', '2025-12-22 17:32:03');
INSERT INTO `transaksi_barang` VALUES ('3', 'TM-1766424723-002', '4', 'masuk', '10', '2025-12-22', 'John Doe', NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 17:32:03', '2025-12-22 17:32:03');
INSERT INTO `transaksi_barang` VALUES ('4', 'TM-1766424723-003', '4', 'masuk', '10', '2025-12-22', NULL, 'Pembelian baru', '5', NULL, NULL, 'pending', NULL, '2025-12-22 17:32:03', '2025-12-22 17:32:03');
INSERT INTO `transaksi_barang` VALUES ('5', 'TM-1766424724-004', '5', 'masuk', '10', '2025-12-22', 'Test', NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 17:32:04', '2025-12-22 17:32:04');
INSERT INTO `transaksi_barang` VALUES ('6', 'TM-1766424724-005', '5', 'masuk', '10', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'approved', NULL, '2025-12-22 17:32:04', '2025-12-22 17:32:04');
INSERT INTO `transaksi_barang` VALUES ('7', 'TK-1766424724-006', '5', 'keluar', '5', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 17:32:04', '2025-12-22 17:32:04');
INSERT INTO `transaksi_barang` VALUES ('8', 'TM-1766425468-001', '8', 'masuk', '10', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 17:44:28', '2025-12-22 17:44:28');
INSERT INTO `transaksi_barang` VALUES ('9', 'TK-1766425468-001', '8', 'keluar', '5', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 17:44:28', '2025-12-22 17:44:28');
INSERT INTO `transaksi_barang` VALUES ('10', 'TM-1766425469-002', '9', 'masuk', '10', '2025-12-22', 'John Doe', NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 17:44:29', '2025-12-22 17:44:29');
INSERT INTO `transaksi_barang` VALUES ('11', 'TM-1766425469-003', '9', 'masuk', '10', '2025-12-22', NULL, 'Pembelian baru', '5', NULL, NULL, 'pending', NULL, '2025-12-22 17:44:29', '2025-12-22 17:44:29');
INSERT INTO `transaksi_barang` VALUES ('12', 'TM-1766425469-004', '9', 'masuk', '10', '2025-12-22', 'Test', NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 17:44:29', '2025-12-22 17:44:29');
INSERT INTO `transaksi_barang` VALUES ('13', 'TM-1766425470-005', '10', 'masuk', '10', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'approved', NULL, '2025-12-22 17:44:30', '2025-12-22 17:44:30');
INSERT INTO `transaksi_barang` VALUES ('14', 'TK-1766425470-006', '10', 'keluar', '5', '2025-12-22', NULL, NULL, '5', NULL, NULL, 'pending', NULL, '2025-12-22 17:44:30', '2025-12-22 17:44:30');

-- Structure for table `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `users`
INSERT INTO `users` VALUES ('1', 'Administrator', 'admin@inventaris.test', '2025-12-22 17:31:36', '$2y$12$MoRMO8kH4uNC.tYa.F3re.z2SWjRC.pw0KNAwbBxf0nr.kutIv3n.', NULL, '2025-12-22 17:31:36', '2025-12-22 17:31:36', '1', NULL);
INSERT INTO `users` VALUES ('2', 'Petugas Inventaris', 'petugas@inventaris.test', '2025-12-22 17:31:37', '$2y$12$LbXVP16P2icTo4unQwB1d.frLc9VmOMEi6Z0SLanL5sdIieAXFVTm', NULL, '2025-12-22 17:31:37', '2025-12-22 17:31:37', '1', NULL);
INSERT INTO `users` VALUES ('3', 'Kepala Sekolah', 'kepala@inventaris.test', '2025-12-22 17:31:37', '$2y$12$u7eBY3pfGdn9ccqn1vhYD.ymNhLRbyfQN2Eq9TjojTaS4FmiBOLG2', NULL, '2025-12-22 17:31:37', '2025-12-22 17:31:37', '1', NULL);
INSERT INTO `users` VALUES ('4', 'Admin User', 'admin@example.com', NULL, '$2y$12$8QiJWXKlMzJCPFYgRx/Dzen64rEe.C0zpq3UdgjuiT3Hfso.zEi8G', NULL, '2025-12-22 17:32:00', '2025-12-22 17:32:00', '1', NULL);
INSERT INTO `users` VALUES ('5', 'Test User', 'testuser@example.com', NULL, '$2y$12$cbqcW1Y.A8Ngg0L/2BB2EeCFui3X/bFJMkWWYI0QHhMJwCRoLhAPW', NULL, '2025-12-22 17:32:02', '2025-12-22 17:32:02', '1', NULL);

SET FOREIGN_KEY_CHECKS=1;
