SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin','petugas','dokter','pasien') NOT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_email` (`email`),
    INDEX `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLE: patients =====
CREATE TABLE `patients` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nik` VARCHAR(16) UNIQUE NOT NULL,
    `nama` VARCHAR(100) NOT NULL,
    `tanggal_lahir` DATE NOT NULL,
    `jenis_kelamin` ENUM('L','P') NOT NULL,
    `no_hp` VARCHAR(15) NOT NULL,
    `alamat` TEXT NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    INDEX `idx_nik` (`nik`),
    INDEX `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLE: poli =====
CREATE TABLE `poli` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama_poli` VARCHAR(100) NOT NULL,
    `kode_poli` VARCHAR(5) UNIQUE NOT NULL,
    `avg_service_time` INT DEFAULT 10,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_kode_poli` (`kode_poli`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLE: queues =====
CREATE TABLE `queues` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nomor_antrian` VARCHAR(10) NOT NULL,
    `pasien_id` INT UNSIGNED NOT NULL,
    `poli_id` INT UNSIGNED NOT NULL,
    `tanggal` DATE NOT NULL,
    `jam_daftar` DATETIME NOT NULL,
    `status` ENUM('menunggu','dipanggil','dalam_pemeriksaan','selesai','dibatalkan','tidak_hadir') DEFAULT 'menunggu',
    `kategori_prioritas` ENUM('normal','lansia','hamil','disabilitas','darurat') DEFAULT 'normal',
    `estimasi_waktu` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`pasien_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`poli_id`) REFERENCES `poli` (`id`) ON DELETE CASCADE,
    INDEX `idx_tanggal` (`tanggal`),
    INDEX `idx_poli_id` (`poli_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_nomor_antrian` (`nomor_antrian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLE: service_records =====
CREATE TABLE `service_records` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `queue_id` INT UNSIGNED NOT NULL,
    `dokter_id` INT UNSIGNED NOT NULL,
    `waktu_mulai` DATETIME NOT NULL,
    `waktu_selesai` DATETIME NULL,
    `catatan` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`queue_id`) REFERENCES `queues` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`dokter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    INDEX `idx_dokter_id` (`dokter_id`),
    INDEX `idx_queue_id` (`queue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== SEED DATA =====

-- Insert Poli
INSERT INTO `poli` (`nama_poli`, `kode_poli`, `avg_service_time`, `is_active`) VALUES
('Poli Umum', 'A', 10, 1),
('Poli Gigi', 'G', 15, 1),
('Poli Anak', 'K', 12, 1),
('Poli Kandungan', 'KD', 20, 1),
('Poli Penyakit Dalam', 'PD', 15, 1);

-- Insert Admin User
INSERT INTO `users` (`nama`, `email`, `password`, `role`, `is_active`) VALUES
('Administrator', 'admin@smartqueue.local', '$2y$10$p16SaaCmRelyQhaW40KzFO6zij.pJQsi0FiVoJxgIv/yZtZb2QurO', 'admin', 1),
('Petugas 1', 'petugas1@smartqueue.local', '$2y$10$869Md3zEvG8XDQwKu6eLVOYRUsMf99YzN5F9QppMuwWfDoNHgh0gO', 'petugas', 1),
('Dr. Siti', 'dokter1@smartqueue.local', '$2y$10$HsDj7IZttGw8koX4Al6aM.7YvmOmfrCsTG88uSvfg3f4lEuQFeRem', 'dokter', 1),
('Pasien Test', 'pasien@smartqueue.local', '$2y$10$UGbUj93SOdTYghk89dPHh.LozJxoMv4O2/GbvOWXvbN5JdBuaC/Su', 'pasien', 1);

-- Insert Sample Patient
INSERT INTO `patients` (`nik`, `nama`, `tanggal_lahir`, `jenis_kelamin`, `no_hp`, `alamat`, `user_id`) VALUES
('1234567890123456', 'Budi Santoso', '1990-05-15', 'L', '081234567890', 'Jl. Merdeka No. 1, Jakarta', 4);

SET FOREIGN_KEY_CHECKS=1;