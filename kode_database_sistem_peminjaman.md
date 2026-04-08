# Kode Database - Sistem Peminjaman

---

## 1. `config/koneksi.php` — Koneksi Database

```php
<?php
$koneksi = new mysqli("localhost", "root", "", "sistem_peminjaman"); // ganti nama db sesuai
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
```

---

## 2. Tabel yang Digunakan (terdeteksi dari query)

| Tabel               | Keterangan                                      |
|---------------------|-------------------------------------------------|
| `users`             | Data user, admin, petugas                       |
| `barang`            | Data barang yang bisa dipinjam                  |
| `kategori`          | Kategori barang                                 |
| `peminjaman`        | Data transaksi peminjaman                       |
| `detail_peminjaman` | Detail barang per peminjaman                    |
| `pengembalian`      | Data pengembalian                               |
| `pembayaran_denda`  | Data pembayaran denda                           |
| `log_aktivitas`     | Log aktivitas user                              |
| `activity_log`      | Riwayat aktivitas user                          |

---

## 3. Query CRUD Kategori (`admin/kategori.php`)

```php
// Tambah kategori
mysqli_query($koneksi, "INSERT INTO kategori (nama_kategori, kode_kategori) VALUES ('$nama', '$kode')");

// Edit kategori
mysqli_query($koneksi, "UPDATE kategori SET nama_kategori='$nama', kode_kategori='$kode' WHERE id_kategori='$id'");

// Hapus kategori
mysqli_query($koneksi, "DELETE FROM kategori WHERE id_kategori='$id'");

// Ambil semua kategori
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY id_kategori DESC");
```

---

## 4. Query Peminjaman Admin (`admin/peminjaman.php`)

```php
// Ambil data peminjaman dengan pagination dan pencarian
$q = mysqli_query($koneksi, "
  SELECT p.id_peminjaman, p.tgl_pinjam, p.status, u.nama
  FROM peminjaman p
  JOIN users u ON p.id_user = u.id_user
  WHERE u.nama LIKE '%$keyword%'
  ORDER BY p.id_peminjaman DESC
  LIMIT $start, $limit
");

// Hitung total untuk pagination
$total_result = mysqli_query($koneksi, "
  SELECT COUNT(*) as total
  FROM peminjaman p
  JOIN users u ON p.id_user = u.id_user
  WHERE u.nama LIKE '%$keyword%'
");
$total = mysqli_fetch_assoc($total_result)['total'];
```

---

## 5. Query Dashboard Admin (`dashboard/admin.php`)

```php
// Hitung total tiap data
$total_user        = ($result = $koneksi->query("SELECT COUNT(*) as total FROM users WHERE role='user'")) ? $result->fetch_assoc()['total'] : 0;
$total_alat        = ($result = $koneksi->query("SELECT COUNT(*) as total FROM alat")) ? $result->fetch_assoc()['total'] : 0;
$total_kategori    = ($result = $koneksi->query("SELECT COUNT(*) as total FROM kategori")) ? $result->fetch_assoc()['total'] : 0;
$total_peminjaman  = ($result = $koneksi->query("SELECT COUNT(*) as total FROM peminjaman")) ? $result->fetch_assoc()['total'] : 0;
$total_pengembalian = ($result = $koneksi->query("SELECT COUNT(*) as total FROM pengembalian")) ? $result->fetch_assoc()['total'] : 0;

// Ambil log aktivitas terbaru
$log = $koneksi->query("
    SELECT u.nama, l.aktivitas, l.waktu
    FROM log_aktivitas l
    JOIN users u ON l.id_user = u.id_user
    ORDER BY l.waktu DESC
    LIMIT 10
");
```

---

## 6. Query Dashboard User (`dashboard/user.php`)

```php
// Total peminjaman user yang login
$total_peminjaman = ($result = $koneksi->query("SELECT COUNT(*) as total FROM peminjaman WHERE id_user='$user_id'")) ? $result->fetch_assoc()['total'] : 0;

// Ambil data peminjaman dengan pagination
$q = mysqli_query($koneksi, "
  SELECT id_peminjaman, tgl_pinjam, status
  FROM peminjaman
  WHERE id_user='$user_id' AND id_peminjaman LIKE '%$keyword%'
  ORDER BY id_peminjaman DESC
  LIMIT $start, $limit
");

// Hitung total
$total_result = mysqli_query($koneksi, "
  SELECT COUNT(*) as total
  FROM peminjaman
  WHERE id_user='$user_id' AND id_peminjaman LIKE '%$keyword%'
");
$total = mysqli_fetch_assoc($total_result)['total'];
```

---

## 7. Query Data User (`data/user.php`)

```php
// Riwayat aktivitas user
$riwayat = mysqli_query($koneksi, "SELECT * FROM activity_log WHERE id_user='$id_user' ORDER BY created_at DESC LIMIT 10");
```

---

## 8. Query Search Barang (`data/search_barang.php`)

```php
// Pencarian barang (AJAX)
$safe  = mysqli_real_escape_string($koneksi, $q);
$where = ($q != '') ? "WHERE nama_barang LIKE '%$safe%'" : "";
$sql   = "SELECT * FROM barang $where ORDER BY nama_barang ASC";
$query = mysqli_query($koneksi, $sql);
```

---

## 9. Query Riwayat Pengembalian (`laporan/riwayatpengembalian.php`)

```php
$q = mysqli_query($koneksi, "
    SELECT
        p.id_peminjaman,
        p.tanggal_pinjam,
        p.status,
        b.nama_barang,
        d.jumlah
    FROM peminjaman p
    JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman
    JOIN barang b ON d.id_barang = b.id_barang
    WHERE p.status = 'kembali'
    ORDER BY p.id_peminjaman DESC
");
```

---

## 10. Query Login & Autentikasi

### Login User (`login/proses_login.php`)
```php
$stmt = $koneksi->prepare("SELECT id_user, nama, password, role, status FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
```

### Login Admin (`login/proses_login_admin.php` & `proses_admin.php`)
```php
$stmt = $koneksi->prepare("SELECT id_user, nama, password FROM users WHERE email=? AND role='admin'");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
```

### Login Petugas (`login/proses_login_petugas.php`)
```php
$stmt = $koneksi->prepare("SELECT id_user, nama, password, role FROM users WHERE email=? AND role='petugas'");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
```

---

## 11. Query Register

### Register User (`login/proses_register.php`)
```php
// Cek email sudah ada
$stmt = $koneksi->prepare("SELECT id_user FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Insert user baru
$stmt = $koneksi->prepare("INSERT INTO users (nama, email, password, role, status) VALUES (?, ?, ?, 'user', 'aktif')");
$stmt->bind_param("sss", $nama, $email, $password);
$stmt->execute();
```

### Register Admin (`login/proses_register_admin.php`)
```php
// Cek email
$stmt = $koneksi->prepare("SELECT id_user FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Insert admin
$stmt = $koneksi->prepare("INSERT INTO users(nama, email, password, role, status) VALUES(?, ?, ?, 'admin', 'aktif')");
$stmt->bind_param("sss", $nama, $email, $password);
$stmt->execute();
```

### Register Petugas (`login/proses_register_petugas.php`)
```php
// Cek email khusus role petugas
$stmt = $koneksi->prepare("SELECT id_user FROM users WHERE email=? AND role='petugas'");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Insert petugas
$stmt = $koneksi->prepare("INSERT INTO users (nama, email, password, role, status) VALUES (?, ?, ?, 'petugas', 'aktif')");
$stmt->bind_param("sss", $nama, $email, $password);
$stmt->execute();
```

---

## 12. Query Forgot Password & Reset

### Forgot User (`login/proses_forgot.php`)
```php
$stmt = $koneksi->prepare("SELECT id_user FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
```

### Forgot Petugas (`login/proses_forgot_petugas.php`)
```php
$stmt = $koneksi->prepare("SELECT id_user FROM users WHERE email=? AND role='petugas'");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
```

### Reset Password User (`login/proses_reset.php`)
```php
$stmt = $koneksi->prepare("UPDATE users SET password=? WHERE email=?");
$stmt->bind_param("ss", $password, $email);
$stmt->execute();
```

### Reset Password Admin (`login/proses_reset_admin.php`)
```php
$stmt = $koneksi->prepare("UPDATE users SET password=? WHERE email=? AND role='admin'");
$stmt->bind_param("ss", $password, $email);
$stmt->execute();
```

### Reset Password Petugas (`login/proses_reset_petugas.php`)
```php
$stmt = $koneksi->prepare("UPDATE users SET password=? WHERE email=? AND role='petugas'");
$stmt->bind_param("ss", $password, $email);
$stmt->execute();
```

---

## 13. Query Register dengan OTP (`config/mailer.php`)

```php
// Simpan user dengan OTP (belum aktif)
$stmt = $koneksi->prepare("INSERT INTO users (nama, email, password, otp, otp_expiry) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nama, $email, $password, $otp, $otp_expiry);
$stmt->execute();
```

---

## 14. Query Petugas

### Approve Peminjaman (`petugas/approvepeminjaman.php`)
```php
mysqli_query($koneksi, "
  UPDATE peminjaman
  SET status='approved'
  WHERE id_peminjaman='$id'
");
```

### Detail Peminjaman (`petugas/detailpeminjaman.php`)
```php
$q = mysqli_query($koneksi, "
  SELECT b.nama_barang, d.jumlah
  FROM peminjaman_detail d
  JOIN barang b ON d.id_barang = b.id_barang
  WHERE d.id_peminjaman='$id'
");
```

### Pengembalian Petugas (`petugas/pengembalian.php`)
```php
$q = mysqli_query($koneksi, "
  SELECT p.*, u.nama
  FROM peminjaman p
  JOIN users u ON p.id_user = u.id_user
  WHERE p.status='returned'
");
```

---

## 15. Query Transaksi

### Bayar Denda (`transaksi/bayar_denda.php`)
```php
mysqli_query($koneksi, "
    INSERT INTO pembayaran_denda (id_peminjaman, id_user, jumlah, tgl_bayar, metode)
    VALUES ('$id_peminjaman', '$id_user', '$jumlah', CURDATE(), 'cash')
");
```

### Daftar Barang Dipinjam (`transaksi/kembali.php`)
```php
$q = mysqli_query($koneksi, "
    SELECT
        p.id_peminjaman,
        b.nama_barang,
        d.jumlah
    FROM peminjaman p
    JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman
    JOIN barang b ON d.id_barang = b.id_barang
    WHERE p.status = 'dipinjam'
");
```

### Ambil Barang untuk Form Pinjam (`transaksi/pinjam.php` & `user/ajukan_peminjaman.php`)
```php
$qBarang = mysqli_query($koneksi, "SELECT * FROM barang");
```

---

## 16. Query User

### Pengembalian Barang User (`user/pengembalian.php`)
```php
$q = mysqli_query($koneksi, "
  SELECT * FROM peminjaman
  WHERE id_user='$id_user' AND status='approved'
");
```

### Proses Kembalikan Barang (`user/proses_kembali.php`)
```php
mysqli_query($koneksi, "
  UPDATE peminjaman
  SET status='returned',
      tgl_kembali=CURDATE(),
      bukti='$namaFile'
  WHERE id_peminjaman='$id'
");
```

### Proses Ajukan Peminjaman (`user/proses_pinjam.php`)
```php
// Cek status user (blacklist / suspend)
$q = mysqli_query($koneksi, "
    SELECT status, blacklist
    FROM users
    WHERE id_user='$id_user'
");
$u = mysqli_fetch_assoc($q);

// Insert peminjaman baru
mysqli_query($koneksi, "
    INSERT INTO peminjaman (id_user, tgl_pinjam, status)
    VALUES ('$id_user', CURDATE(), 'pending')
");
```
