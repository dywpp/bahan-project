-- ============================================================
--  DATABASE  : sistem_peminjaman
--  Dibuat dari : analisis seluruh file PHP proyek
--  Engine     : InnoDB | Charset: utf8mb4
-- ============================================================

CREATE DATABASE IF NOT EXISTS sistem_peminjaman
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sistem_peminjaman;

-- ============================================================
-- TABEL 1 : users
-- Dipakai di  : semua file login, dashboard, transaksi
-- Kolom asal  : id_user, nama, email, password, role, status,
--               blacklist, otp, otp_expiry
-- Catatan     : satu tabel untuk admin + petugas + user
-- ============================================================
CREATE TABLE users (
    id_user     INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nama        VARCHAR(100)    NOT NULL,
    email       VARCHAR(150)    NOT NULL,
    password    VARCHAR(255)    NOT NULL,
    role        ENUM('admin','petugas','user') NOT NULL DEFAULT 'user',
    status      ENUM('aktif','suspend')        NOT NULL DEFAULT 'aktif',
    blacklist   ENUM('tidak','ya')             NOT NULL DEFAULT 'tidak',
    otp         VARCHAR(10)     DEFAULT NULL,
    otp_expiry  DATETIME        DEFAULT NULL,
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_user),
    UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- TABEL 2 : kategori
-- Dipakai di  : admin/kategori.php, dashboard/admin.php
-- Kolom asal  : id_kategori, nama_kategori, kode_kategori
-- ============================================================
CREATE TABLE kategori (
    id_kategori     INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nama_kategori   VARCHAR(100)    NOT NULL,
    kode_kategori   VARCHAR(20)     NOT NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_kategori),
    UNIQUE KEY uq_kode (kode_kategori)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- TABEL 3 : barang
-- Dipakai di  : data/search_barang.php, transaksi/pinjam.php,
--               transaksi/kembali.php, user/ajukan_peminjaman.php,
--               laporan/riwayatpengembalian.php
-- Kolom asal  : id_barang, nama_barang, stok, id_kategori
-- Catatan     : di dashboard/admin.php tabel ini juga disebut "alat"
--               (SELECT COUNT(*) FROM alat) - dibuat VIEW alias di bawah
-- ============================================================
CREATE TABLE barang (
    id_barang   INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    id_kategori INT UNSIGNED    NOT NULL,
    nama_barang VARCHAR(150)    NOT NULL,
    stok        INT             NOT NULL DEFAULT 0,
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_barang),
    KEY idx_barang_kategori (id_kategori),

    CONSTRAINT fk_barang_kategori
        FOREIGN KEY (id_kategori) REFERENCES kategori (id_kategori)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- VIEW alias agar query "FROM alat" di dashboard/admin.php tetap jalan
CREATE OR REPLACE VIEW alat AS
    SELECT * FROM barang;


-- ============================================================
-- TABEL 4 : peminjaman
-- Dipakai di  : admin/peminjaman.php, dashboard/user.php,
--               user/pengembalian.php, user/proses_pinjam.php,
--               user/proses_kembali.php, petugas/approvepeminjaman.php,
--               petugas/pengembalian.php, transaksi/kembali.php,
--               laporan/riwayatpengembalian.php, dashboard/admin.php
-- Kolom asal  : id_peminjaman, id_user, tgl_pinjam, tanggal_pinjam
--               (KEDUA nama kolom dipakai di kode yang berbeda),
--               tgl_kembali, status, denda, bukti
-- Status flow : pending -> approved -> dipinjam -> returned / kembali
-- ============================================================
CREATE TABLE peminjaman (
    id_peminjaman   INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    id_user         INT UNSIGNED    NOT NULL,
    tgl_pinjam      DATE            NOT NULL,
    tanggal_pinjam  DATE            DEFAULT NULL,
    tgl_kembali     DATE            DEFAULT NULL,
    status          ENUM(
                        'pending',
                        'approved',
                        'dipinjam',
                        'returned',
                        'kembali'
                    ) NOT NULL DEFAULT 'pending',
    denda           DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    bukti           VARCHAR(255)    DEFAULT NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_peminjaman),
    KEY idx_peminjaman_user   (id_user),
    KEY idx_peminjaman_status (status),

    CONSTRAINT fk_peminjaman_user
        FOREIGN KEY (id_user) REFERENCES users (id_user)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- TABEL 5 : detail_peminjaman
-- Dipakai di  : petugas/detailpeminjaman.php (nama: "peminjaman_detail"),
--               transaksi/kembali.php         (nama: "detail_peminjaman"),
--               laporan/riwayatpengembalian.php
-- Kolom asal  : id_detail, id_peminjaman, id_barang, jumlah
-- Catatan     : dua nama berbeda dipakai di kode untuk tabel yang sama,
--               dibuat VIEW alias "peminjaman_detail" di bawah
-- ============================================================
CREATE TABLE detail_peminjaman (
    id_detail       INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    id_peminjaman   INT UNSIGNED    NOT NULL,
    id_barang       INT UNSIGNED    NOT NULL,
    jumlah          INT             NOT NULL DEFAULT 1,

    PRIMARY KEY (id_detail),
    KEY idx_detail_pinjam (id_peminjaman),
    KEY idx_detail_barang (id_barang),

    CONSTRAINT fk_detail_peminjaman
        FOREIGN KEY (id_peminjaman) REFERENCES peminjaman (id_peminjaman)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detail_barang
        FOREIGN KEY (id_barang) REFERENCES barang (id_barang)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- VIEW alias agar query "FROM peminjaman_detail" di petugas/detailpeminjaman.php jalan
CREATE OR REPLACE VIEW peminjaman_detail AS
    SELECT * FROM detail_peminjaman;


-- ============================================================
-- TABEL 6 : pengembalian
-- Dipakai di  : dashboard/admin.php (COUNT(*)),
--               petugas/pengembalian.php
-- Kolom asal  : id_pengembalian, id_peminjaman, tgl_kembali,
--               denda, keterangan
-- ============================================================
CREATE TABLE pengembalian (
    id_pengembalian INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    id_peminjaman   INT UNSIGNED    NOT NULL,
    tgl_kembali     DATE            NOT NULL,
    denda           DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    keterangan      TEXT            DEFAULT NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_pengembalian),
    KEY idx_pengembalian_pinjam (id_peminjaman),

    CONSTRAINT fk_pengembalian_peminjaman
        FOREIGN KEY (id_peminjaman) REFERENCES peminjaman (id_peminjaman)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- TABEL 7 : pembayaran_denda
-- Dipakai di  : transaksi/bayar_denda.php
-- Kolom asal  : id_peminjaman, id_user, jumlah, tgl_bayar, metode
-- ============================================================
CREATE TABLE pembayaran_denda (
    id_pembayaran   INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    id_peminjaman   INT UNSIGNED    NOT NULL,
    id_user         INT UNSIGNED    NOT NULL,
    jumlah          DECIMAL(10,2)   NOT NULL,
    tgl_bayar       DATE            NOT NULL,
    metode          VARCHAR(50)     NOT NULL DEFAULT 'cash',
    status          ENUM('lunas','belum') NOT NULL DEFAULT 'lunas',
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_pembayaran),
    KEY idx_denda_peminjaman (id_peminjaman),
    KEY idx_denda_user       (id_user),

    CONSTRAINT fk_denda_peminjaman
        FOREIGN KEY (id_peminjaman) REFERENCES peminjaman (id_peminjaman)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_denda_user
        FOREIGN KEY (id_user) REFERENCES users (id_user)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- TABEL 8 : log_aktivitas
-- Dipakai di  : dashboard/admin.php  -> "FROM log_aktivitas"
--               data/user.php        -> "FROM activity_log"
-- Kolom asal di dashboard : aktivitas, waktu
-- Kolom asal di data/user : activity, created_at
-- Catatan     : kedua kolom aktivitas & activity disimpan bersama,
--               VIEW alias "activity_log" dibuat di bawah
-- ============================================================
CREATE TABLE log_aktivitas (
    id_log      INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    id_user     INT UNSIGNED    NOT NULL,
    aktivitas   VARCHAR(255)    NOT NULL DEFAULT '',
    activity    VARCHAR(255)    DEFAULT NULL,
    waktu       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_log),
    KEY idx_log_user  (id_user),
    KEY idx_log_waktu (waktu),

    CONSTRAINT fk_log_user
        FOREIGN KEY (id_user) REFERENCES users (id_user)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- VIEW alias agar query "FROM activity_log" di data/user.php tetap jalan
CREATE OR REPLACE VIEW activity_log AS
    SELECT * FROM log_aktivitas;


-- ============================================================
-- DATA AWAL : users
-- Password : admin123
-- Hash bcrypt (PASSWORD_DEFAULT PHP) — ganti di production!
-- ============================================================
INSERT INTO users (nama, email, password, role, status, blacklist) VALUES
('Administrator',  'admin@peminjaman.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin',   'aktif', 'tidak'),
('Petugas Utama',  'petugas@peminjaman.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petugas', 'aktif', 'tidak'),
('User Contoh',    'user@peminjaman.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user',    'aktif', 'tidak');


-- ============================================================
-- DATA AWAL : kategori
-- ============================================================
INSERT INTO kategori (nama_kategori, kode_kategori) VALUES
('Elektronik',       'ELK'),
('Alat Tulis',       'ATK'),
('Peralatan Lab',    'LAB'),
('Olahraga',         'OLR'),
('Peralatan Kantor', 'KTR');


-- ============================================================
-- DATA AWAL : barang
-- ============================================================
INSERT INTO barang (id_kategori, nama_barang, stok) VALUES
(1, 'Laptop Asus',          5),
(1, 'Proyektor Epson',      3),
(1, 'Kamera Canon',         2),
(2, 'Spidol Board Marker', 20),
(2, 'Penggaris 30cm',      15),
(3, 'Mikroskop',            4),
(3, 'Gelas Ukur 100ml',    10),
(4, 'Bola Basket',          6),
(4, 'Net Badminton',        3),
(5, 'Mesin Fotokopi',       1);


-- ============================================================
-- RINGKASAN TABEL & VIEW
-- ============================================================
--
--  TABEL ASLI         KETERANGAN
--  ─────────────────  ─────────────────────────────────────────
--  users              Semua pengguna (admin / petugas / user)
--  kategori           Kategori barang
--  barang             Inventaris barang / alat
--  peminjaman         Header transaksi peminjaman
--  detail_peminjaman  Rincian barang per transaksi
--  pengembalian       Catatan pengembalian barang
--  pembayaran_denda   Riwayat bayar denda
--  log_aktivitas      Log aktivitas semua user
--
--  VIEW (alias)       MENGGANTIKAN QUERY KE
--  ─────────────────  ─────────────────────────────────────────
--  alat               barang          (dashboard/admin.php)
--  peminjaman_detail  detail_peminjaman (petugas/detailpeminjaman.php)
--  activity_log       log_aktivitas   (data/user.php)
--
-- ============================================================
