<?php
/*
=============================================================
  SISTEM PEMINJAMAN - SEMUA KODE (DIGABUNG)
  Di-generate otomatis dari seluruh file PHP
=============================================================
*/


// =============================================================
// FILE: admin/kategori.php
// =============================================================

session_start();
if(!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login/login.php");
    exit;
}
include "../config/koneksi.php";

// TAMBAH KATEGORI
if(isset($_POST['tambah'])){
    $nama = $_POST['nama_kategori'];
    $kode = $_POST['kode_kategori'];
    if($nama && $kode){
        mysqli_query($koneksi,"INSERT INTO kategori (nama_kategori,kode_kategori) VALUES ('$nama','$kode')");
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

// EDIT KATEGORI
if(isset($_POST['edit'])){
    $id = $_POST['id_kategori'];
    $nama = $_POST['nama_kategori'];
    $kode = $_POST['kode_kategori'];
    mysqli_query($koneksi,"UPDATE kategori SET nama_kategori='$nama', kode_kategori='$kode' WHERE id_kategori='$id'");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// HAPUS KATEGORI
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    mysqli_query($koneksi,"DELETE FROM kategori WHERE id_kategori='$id'");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// AMBIL DATA
$kategori = mysqli_query($koneksi,"SELECT * FROM kategori ORDER BY id_kategori DESC");


<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CRUD Kategori</title>
<style>
/* Reset & Base */
body{
    font-family:'Montserrat',sans-serif;
    background:#f5f2ec;
    color:#333;
    margin:0;
    display:flex;
}
.sidebar{
    width:220px;
    background:#4a3f35;
    color:#fff;
    min-height:100vh;
    padding:20px;
    display:flex;
    flex-direction:column;
    gap:10px;
}
.sidebar a{
    color:#fff;
    text-decoration:none;
    padding:10px 15px;
    border-radius:8px;
    display:block;
    transition:0.3s;
}
.sidebar a:hover{
    background:#a67c52;
}
.main{
    flex:1;
    padding:30px 40px;
}
h1{
    margin-bottom:20px;
    color:#4a3f35;
}
.panel{
    background:#fff;
    border-radius:15px;
    padding:20px;
    margin-bottom:30px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}
.btn{
    background:#5f8cff;
    color:#fff;
    border:none;
    padding:8px 14px;
    border-radius:8px;
    cursor:pointer;
    transition:0.3s;
}
.btn:hover{
    opacity:0.85;
}
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:12px 15px;
    text-align:left;
}
th{
    background:#f0e6d2;
    color:#4a3f35;
}
tr:nth-child(even){
    background:#faf5f0;
}
tr:hover{
    background:#f0e6d2;
}
.act a{
    margin-right:6px;
    font-size:12px;
    padding:4px 8px;
    border-radius:6px;
    color:#fff;
    text-decoration:none;
}
.edit{background:#2a3a6a;}
.del{background:#3a1a1a;}
.form-grid{
    display:flex;
    gap:10px;
    margin-bottom:10px;
}
.form-grid input{
    flex:1;
    padding:8px;
    border-radius:6px;
    border:1px solid #ddd;
}
@media (max-width:768px){
    body{flex-direction:column;}
    .sidebar{width:100%;flex-direction:row;flex-wrap:wrap;justify-content:center;}
    .main{padding:20px;}
}
</style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Menu</h2>
    <a href="admin_crud_user.php">Data User</a>
    <a href="admin_crud_alat.php">Alat</a>
    <a href="kategori.php">Kategori</a>
    <a href="peminjaman.php">Peminjaman</a>
    <a href="admin_crud_pengembalian.php">Pengembalian</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <h1>Kategori Barang</h1>

    <!-- FORM TAMBAH -->
    <div class="panel">
        <form method="post" class="form-grid">
            <input type="text" name="nama_kategori" placeholder="Nama Kategori" required>
            <input type="text" name="kode_kategori" placeholder="Kode Kategori" required>
            <button class="btn" name="tambah">+ Tambah</button>
        </form>
    </div>

    <!-- TABEL DATA -->
    <div class="panel">
        <table>
            <tr>
                <th>#</th>
                <th>Nama Kategori</th>
                <th>Kode</th>
                <th>Aksi</th>
            </tr>
            <?php $no=1; while($row=mysqli_fetch_assoc($kategori)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                <td><?= htmlspecialchars($row['kode_kategori']) ?></td>
                <td class="act">
                    <a href="#" class="edit" onclick="document.getElementById('edit<?= $row['id_kategori'] ?>').style.display='block'">Edit</a>
                    <a href="?hapus=<?= $row['id_kategori'] ?>" class="del" onclick="return confirm('Hapus kategori ini?')">Hapus</a>

                    <div id="edit<?= $row['id_kategori'] ?>" style="display:none;margin-top:6px;">
                        <form method="post" class="form-grid">
                            <input type="hidden" name="id_kategori" value="<?= $row['id_kategori'] ?>">
                            <input type="text" name="nama_kategori" value="<?= htmlspecialchars($row['nama_kategori']) ?>" required>
                            <input type="text" name="kode_kategori" value="<?= htmlspecialchars($row['kode_kategori']) ?>" required>
                            <button class="btn" name="edit">Simpan</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

</body>
</html>




// =============================================================
// FILE: admin/peminjaman.php
// =============================================================

session_start();
if(!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login/login.php");
    exit;
}
include "../config/koneksi.php";

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page-1) * $limit;

$keyword = $_GET['q'] ?? '';

$q = mysqli_query($koneksi,"
  SELECT p.id_peminjaman, p.tgl_pinjam, p.status, u.nama
  FROM peminjaman p
  JOIN users u ON p.id_user=u.id_user
  WHERE u.nama LIKE '%$keyword%'
  ORDER BY p.id_peminjaman DESC
  LIMIT $start, $limit
");

$total_result = mysqli_query($koneksi,"
  SELECT COUNT(*) as total
  FROM peminjaman p
  JOIN users u ON p.id_user=u.id_user
  WHERE u.nama LIKE '%$keyword%'
");
$total = mysqli_fetch_assoc($total_result)['total'];
$pages = ceil($total / $limit);


<!DOCTYPE html>
<html>
<head>
<title>Peminjaman</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
:root{
 --bg:#f5f2ec; --panel:#fff; --card:#fff; --border:#ccc;
 --text:#333; --muted:#666; --accent:#5f8cff;
}
body{margin:0;font-family:'Montserrat',sans-serif;background:var(--bg);color:var(--text);}
.container{max-width:1200px;margin:auto;padding:20px;}
.sidebar{
    width:220px;background:#4a3f35;color:#fff;position:fixed;top:0;bottom:0;left:0;padding:20px;
}
.sidebar h2{margin-top:0;font-size:20px;}
.sidebar a{display:block;color:#fff;text-decoration:none;padding:10px;margin:5px 0;border-radius:6px;}
.sidebar a:hover{background:#5f8cff;}
.main{margin-left:240px;padding:20px;}
.panel{background:var(--panel);padding:20px;border-radius:12px;box-shadow:0 3px 8px rgba(0,0,0,0.1);}
table{width:100%;border-collapse:collapse;margin-top:10px;}
th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd;}
th{background:#f0e6d2;color:#4a3f35;}
tr:hover{background:#f9f2e8;}
form input{padding:8px;border-radius:6px;border:1px solid #ccc;margin-right:6px;}
form button{padding:8px 12px;border:none;border-radius:6px;background:var(--accent);color:#fff;cursor:pointer;}
</style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Dashboard</h2>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_crud_user.php">User</a>
    <a href="admin_crud_alat.php">Alat</a>
    <a href="kategori.php">Kategori</a>
    <a href="peminjaman.php">Peminjaman</a>
    <a href="admin_crud_pengembalian.php">Pengembalian</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
<h1>Peminjaman</h1>

<!-- FORM CARI -->
<div class="panel">
<form method="GET" action="">
<input type="text" name="q" placeholder="Cari nama user" value="<?= htmlspecialchars($keyword) ?>">
<button type="submit">Cari</button>
</form>
</div>

<!-- TABEL -->
<div class="panel">
<table>
<tr>
<th>#</th>
<th>Peminjam</th>
<th>Tanggal Pinjam</th>
<th>Status</th>
</tr>
 $no = $start + 1; while($r = mysqli_fetch_assoc($q)): ?>
<tr>
<td><?= $no++ ?></td>
<td><?= htmlspecialchars($r['nama']) ?></td>
<td><?= htmlspecialchars($r['tgl_pinjam'] ?? '-') ?></td>
<td><?= htmlspecialchars($r['status'] ?? '-') ?></td>
</tr>
 endwhile; ?>
</table>

<!-- PAGINASI -->
<div style="margin-top:10px;">
 for($i=1; $i<=$pages; $i++): ?>
    <a href="?page=<?= $i ?><?= ($keyword) ? '&q='.$keyword : '' ?>" style="margin-right:5px;"><?= $i ?></a>
 endfor; ?>
</div>

</div>
</body>
</html>


// =============================================================
// FILE: config/koneksi.php
// =============================================================

$koneksi = new mysqli("localhost","root","","sistem_peminjaman"); // ganti nama db sesuai
if($koneksi->connect_error){
    die("Koneksi gagal: ".$koneksi->connect_error);
}



// =============================================================
// FILE: config/mailer.php
// =============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/koneksi.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/PHPMailer/Exception.php";
require __DIR__ . "/PHPMailer/PHPMailer.php";
require __DIR__ . "/PHPMailer/SMTP.php";

/**
 * Kirim OTP ke user dengan pesan lengkap
 */
function sendOTP($to, $otp, $nama = 'User'){
    $mail = new PHPMailer(true);

    try {
        // ================= SMTP CONFIG =================
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'akungabutt85@gmail.com';
        $mail->Password   = 'zyycfxwqirtajakv'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->SMTPDebug  = 0; 

          $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'desmonsinaga417@gmail.com';
        $mail->Password   = 'myueywcyxclnqayc'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->SMTPDebug  = 0; 

        // ================= SENDER =================
        $mail->setFrom('speminjaman@gmail.com', 'Sistem Peminjaman');
        $mail->addAddress($to, $nama);

        // ================= CONTENT =================
        $mail->isHTML(true);
        $mail->Subject = 'Kode OTP Verifikasi Akun';

        $mail->Body = "
        <div style='font-family:Arial; background:#f4f6f9; padding:20px'>
            <div style='max-width:500px; margin:auto; background:#fff; padding:25px; border-radius:8px'>
                <h2 style='color:#222'>Halo, $nama</h2>
                <p>Kamu baru saja mendaftar di <b>Sistem Peminjaman Barang</b>.</p>
                <p>Gunakan kode OTP berikut untuk verifikasi akunmu:</p>
                <div style='text-align:center; margin:20px 0'>
                    <span style='font-size:32px; letter-spacing:6px; font-weight:bold;'>$otp</span>
                </div>
                <p><b>Catatan:</b> OTP berlaku 5 menit. Jangan bagikan kode ini ke siapapun.</p>
                <p>Jika kamu tidak mendaftar, abaikan email ini.</p>
                <hr>
                <small style='color:#666'>Sistem Peminjaman Barang © ".date('Y')."</small>
            </div>
        </div>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}

// ================= REGISTER USER =================
if(isset($_POST['register'])){
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // generate OTP
    $otp = rand(100000,999999);
    $otp_expiry = date('Y-m-d H:i:s', strtotime('+5 minutes')); // OTP 5 menit

    // simpan user inactive + OTP
    $stmt = $koneksi->prepare("INSERT INTO users (nama,email,password,otp,otp_expiry) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss",$nama,$email,$password,$otp,$otp_expiry);

    if($stmt->execute()){
        // kirim OTP via PHPMailer
        if(sendOTP($email, $nama, $otp)){
            $_SESSION['email_verifikasi'] = $email;
            echo "Register berhasil! Cek email untuk OTP.";
        } else {
            echo "Register berhasil, tapi gagal kirim email OTP!";
        }
    } else {
        echo "Gagal register!";
    }
}



// =============================================================
// FILE: dashboard/admin.php
// =============================================================

session_start();
if(!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login/login.php");
    exit;
}
include "../config/koneksi.php";

// Hitung total data
$total_user = ($result=$koneksi->query("SELECT COUNT(*) as total FROM users WHERE role='user'")) ? $result->fetch_assoc()['total'] : 0;
$total_alat = ($result=$koneksi->query("SELECT COUNT(*) as total FROM alat")) ? $result->fetch_assoc()['total'] : 0;
$total_kategori = ($result=$koneksi->query("SELECT COUNT(*) as total FROM kategori")) ? $result->fetch_assoc()['total'] : 0;
$total_peminjaman = ($result=$koneksi->query("SELECT COUNT(*) as total FROM peminjaman")) ? $result->fetch_assoc()['total'] : 0;
$total_pengembalian = ($result=$koneksi->query("SELECT COUNT(*) as total FROM pengembalian")) ? $result->fetch_assoc()['total'] : 0;

// Ambil log aktivitas terbaru
$log = $koneksi->query("
    SELECT u.nama, l.aktivitas, l.waktu
    FROM log_aktivitas l
    JOIN users u ON l.id_user = u.id_user
    ORDER BY l.waktu DESC
    LIMIT 10
");


<!DOCTYPE html>
<html>
<head>
<title>Dashboard Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* Reset & Base */
body {
    font-family: 'Montserrat', sans-serif;
    margin:0;
    background:#f5f2ec;
    display:flex;
}

/* Sidebar */
.sidebar {
    width: 220px;
    background: #4a3f35;
    color:#fff;
    height:100vh;
    display:flex;
    flex-direction:column;
    position: fixed;
}
.sidebar h2 {
    text-align:center;
    padding:20px 0;
    font-size:22px;
    border-bottom:1px solid rgba(255,255,255,0.2);
}
.sidebar a {
    color:#fff;
    text-decoration:none;
    padding:15px 20px;
    display:block;
    transition:0.2s;
}
.sidebar a:hover {
    background:#a67c52;
    color:#fff;
}
.sidebar .logout {
    margin-top:auto;
    background:#8b5e3c;
    text-align:center;
}

/* Main content */
.main {
    margin-left:220px;
    padding:30px 20px;
    flex:1;
}
header {
    margin-bottom:30px;
}
header h1{
    font-size:28px;
    color:#4a3f35;
}
header .welcome{
    font-size:16px;
    color:#4a3f35;
    margin-top:5px;
}

/* Cards */
.cards{
    display:flex;
    flex-wrap:wrap;
    gap:30px;
    margin-bottom:40px;
}
.card{
    flex:1;
    min-width:180px;
    background:#fff;
    border-radius:15px;
    padding:25px;
    box-shadow:0 6px 15px rgba(0,0,0,0.1);
    text-align:center;
    transition:0.3s;
    cursor:pointer;
}
.card:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}
.card h2{
    font-size:2.2rem;
    margin-bottom:10px;
    color:#6b4c3b;
}
.card p{
    font-size:1rem;
    color:#666;
}

/* Table */
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
}
th,td{
    padding:14px 16px;
    text-align:left;
}
th{
    background:#f0e6d2;
    color:#4a3f35;
}
tr:nth-child(even){
    background:#faf5f0;
}
tr:hover{
    background:#f0e6d2;
}

/* Responsive */
@media(max-width:768px){
    body{flex-direction:column;}
    .sidebar{width:100%; height:auto; flex-direction:row; overflow-x:auto;}
    .sidebar a{flex:1; text-align:center; padding:10px;}
    .main{margin-left:0;}
    .cards{flex-direction:column;}
}
</style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_crud_user.php">Data User</a>
    <a href="admin_crud_alat.php">Alat</a>
    <a href="../admin/kategori.php">Kategori</a>
    <a href="../admin/peminjaman.php" class="peminjaman.php">Peminjaman</a>
    <a href="admin_crud_pengembalian.php">Pengembalian</a>
    <a href="login/logout.php" class="logout">Logout</a>
</div>

<div class="main">
    <header>
        <h1>Dashboard Admin</h1>
        <div class="welcome">Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama']); ?></strong></div>
    </header>

    <div class="cards">
        <a href="admin_crud_user.php" style="text-decoration:none;">
            <div class="card">
                <h2><?= $total_user ?></h2>
                <p>Total User</p>
            </div>
        </a>
        <a href="admin_crud_alat.php" style="text-decoration:none;">
            <div class="card">
                <h2><?= $total_alat ?></h2>
                <p>Total Alat</p>
            </div>
        </a>
        <a href="admin_crud_kategori.php" style="text-decoration:none;">
            <div class="card">
                <h2><?= $total_kategori ?></h2>
                <p>Total Kategori</p>
            </div>
        </a>
        <a href="admin_crud_peminjaman.php" style="text-decoration:none;">
            <div class="card">
                <h2><?= $total_peminjaman ?></h2>
                <p>Total Peminjaman</p>
            </div>
        </a>
        <a href="admin_crud_pengembalian.php" style="text-decoration:none;">
            <div class="card">
                <h2><?= $total_pengembalian ?></h2>
                <p>Total Pengembalian</p>
            </div>
        </a>
    </div>

    <h2>Log Aktivitas Terbaru</h2>
    <table>
    <tr>
        <th>Nomor</th>
        <th>User</th>
        <th>Aktivitas</th>
        <th>Waktu</th>
    </tr>
    <?php $no=1; while($row=$log->fetch_assoc()): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['aktivitas']) ?></td>
        <td><?= $row['waktu'] ?></td>
    </tr>
    <?php endwhile; ?>
    </table>

</div>
</body>
</html>


// =============================================================
// FILE: dashboard/petugas.php
// =============================================================

session_start();
if(!isset($_SESSION['id_user']) || $_SESSION['role']!='petugas'){
    header("Location: ../login/login.php");
    exit;
}

<h1>KontrolPetugas <?= $_SESSION['nama']; ?></h1>
<a href="../login/logout.php">Logout</a>


// =============================================================
// FILE: dashboard/user.php
// =============================================================

session_start();
if(!isset($_SESSION['id_user'])){
    header("Location: ../login/login.php");
    exit;
}
include "../config/koneksi.php";

$user_id = $_SESSION['id_user'];

// Hitung total peminjaman user
$total_peminjaman = ($result=$koneksi->query("SELECT COUNT(*) as total FROM peminjaman WHERE id_user='$user_id'")) ? $result->fetch_assoc()['total'] : 0;

// Ambil data peminjaman untuk tabel
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page-1) * $limit;
$keyword = $_GET['q'] ?? '';

$q = mysqli_query($koneksi,"
  SELECT id_peminjaman, tgl_pinjam, status
  FROM peminjaman
  WHERE id_user='$user_id' AND id_peminjaman LIKE '%$keyword%'
  ORDER BY id_peminjaman DESC
  LIMIT $start, $limit
");

$total_result = mysqli_query($koneksi,"
  SELECT COUNT(*) as total
  FROM peminjaman
  WHERE id_user='$user_id' AND id_peminjaman LIKE '%$keyword%'
");
$total = mysqli_fetch_assoc($total_result)['total'];
$pages = ceil($total / $limit);


<!DOCTYPE html>
<html>
<head>
<title>Dashboard Peminjaman</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* Reset & Base */
body{
    font-family:'Montserrat',sans-serif;
    background:#f5f2ec;
    color:#333;
    margin:0;
}
.container{
    display:flex;
    max-width:1200px;
    margin:auto;
    min-height:100vh;
}
/* Sidebar */
.sidebar{
    width:220px;
    background:#4a3f35;
    color:#fff;
    padding:20px;
}
.sidebar h2{margin-top:0;font-size:20px;}
.sidebar a{
    display:block;
    color:#fff;
    text-decoration:none;
    padding:10px;
    margin:8px 0;
    border-radius:6px;
    transition:0.3s;
}
.sidebar a:hover{background:#5f8cff;}

/* Main */
.main{
    flex:1;
    padding:20px;
}

/* Cards */
.cards{
    display:flex;
    gap:20px;
    margin-bottom:30px;
}
.card{
    flex:1;
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 6px 15px rgba(0,0,0,0.1);
    text-align:center;
    transition:0.3s;
}
.card:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}
.card h2{font-size:2rem;margin:0;color:#4a3f35;}
.card p{margin:5px 0 0;font-size:14px;color:#666;}

/* Table */
.panel{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
    margin-bottom:30px;
}
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:12px;
    text-align:left;
    border-bottom:1px solid #ddd;
}
th{
    background:#f0e6d2;
    color:#4a3f35;
}
tr:hover{background:#faf5f0;}
form input{
    padding:8px;
    border-radius:6px;
    border:1px solid #ccc;
    margin-right:6px;
}
form button{
    padding:8px 12px;
    border:none;
    border-radius:6px;
    background:#5f8cff;
    color:#fff;
    cursor:pointer;
}

/* Responsive */
@media(max-width:768px){
    .container{flex-direction:column;}
    .cards{flex-direction:column;}
}
</style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <h2>Dashboard User</h2>
        <a href="user_dashboard.php">Home</a>
        <a href="user_dashboard.php">Melihat Daftar Alat</a>
        <a href="user_dashboard.php">Mengajukan Peminjaman</a>
        <a href="../transaksi/pinjam.php">Peminjaman</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main">
        <h1>Peminjaman Saya</h1>

        <!-- Cards -->
        <div class="cards">
            <div class="card">
                <h2><?= $total_peminjaman ?></h2>
                <p>Total Peminjaman</p>
            </div>
        </div>

        <!-- Form Cari -->
        <div class="panel">
            <form method="GET" action="">
                <input type="text" name="q" placeholder="Cari ID peminjaman" value="<?= htmlspecialchars($keyword) ?>">
                <button type="submit">Cari</button>
            </form>
        </div>

        <!-- Tabel -->
        <div class="panel">
            <table>
                <tr>
                    <th>#</th>
                    <th>ID Peminjaman</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
                <?php $no = $start + 1; while($r = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($r['id_peminjaman']) ?></td>
                    <td><?= htmlspecialchars($r['tgl_pinjam'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['status'] ?? '-') ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <!-- Pagination -->
            <div style="margin-top:10px;">
            <?php for($i=1; $i<=$pages; $i++): ?>
                <a href="?page=<?= $i ?><?= ($keyword) ? '&q='.$keyword : '' ?>" style="margin-right:5px;"><?= $i ?></a>
            <?php endfor; ?>
            </div>
        </div>

    </div>
</div>

</body>
</html>


// =============================================================
// FILE: data/ajukan_peminjaman.php
// =============================================================


// =============================================================
// FILE: data/barang.php
// =============================================================

include "../login/cek_admin.php";



// =============================================================
// FILE: data/search_barang.php
// =============================================================

session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login/login.php");
    exit;
}

include __DIR__ . '/../config/koneksi.php';

/* =========================
   MODE AJAX
========================= */
if (isset($_GET['ajax'])) {
    $q = $_GET['q'] ?? '';
    $where = "";

    if ($q != '') {
        $safe = mysqli_real_escape_string($koneksi, $q);
        $where = "WHERE nama_barang LIKE '%$safe%'";
    }

    $sql = "SELECT * FROM barang $where ORDER BY nama_barang ASC";
    $query = mysqli_query($koneksi, $sql);

    if (mysqli_num_rows($query) == 0) {
        echo "<div class='empty'>🔍 Barang tidak ditemukan</div>";
        exit;
    }

    while ($b = mysqli_fetch_assoc($query)) {
        $stok = (int)$b['stok'];
        ?>
        <div class="card">
            <h4><?= htmlspecialchars($b['nama_barang']); ?></h4>

            <div class="stok">
                Stok: <?= $stok ?><br>
                <span class="badge <?= $stok > 0 ? 'ok' : 'habis' ?>">
                    <?= $stok > 0 ? 'Tersedia' : 'Habis' ?>
                </span>
            </div>

            <?php if ($stok > 0) { ?>
            <div class="action">
                <button onclick="alert('Pinjam: <?= htmlspecialchars($b['nama_barang']); ?>')">
                    ⚡ Pinjam Cepat
                </button>
            </div>
            <?php } ?>
        </div>
        <?php
    }
    exit;
}


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Barang Explorer</title>

<style>
*{box-sizing:border-box}
body{
    font-family:Inter,Arial;
    background:#f5f7ff;
    padding:30px;
}

.wrapper{
    max-width:1100px;
    margin:auto;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.search{
    width:320px;
    padding:12px 16px;
    border-radius:12px;
    border:1px solid #ddd;
    font-size:15px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:16px;
}

.card{
    background:#fff;
    border-radius:16px;
    padding:16px;
    box-shadow:0 8px 25px rgba(0,0,0,.06);
    transition:.35s;
    position:relative;
    overflow:hidden;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:0 15px 40px rgba(0,0,0,.12);
}

.card h4{
    margin:0 0 6px;
    font-size:16px;
}

.stok{font-size:14px}

.badge{
    display:inline-block;
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.ok{background:#dcfce7;color:#166534}
.habis{background:#fee2e2;color:#991b1b}

.action{
    position:absolute;
    inset:auto 0 0 0;
    padding:12px;
    background:linear-gradient(transparent,#fff 60%);
    transform:translateY(100%);
    transition:.3s;
}

.card:hover .action{
    transform:translateY(0);
}

.action button{
    width:100%;
    padding:10px;
    border:none;
    border-radius:10px;
    background:#4f46e5;
    color:#fff;
    cursor:pointer;
}

.empty{
    grid-column:1/-1;
    text-align:center;
    padding:60px;
    color:#888;
}
</style>
</head>
<body>

<div class="wrapper">
    <div class="header">
        <h2>📦 Barang Explorer</h2>
        <input type="text" id="keyword" class="search" placeholder="Cari barang...">
    </div>

    <div class="grid" id="result"></div>
</div>

<script>
let timer;

function loadBarang(){
    const q = document.getElementById('keyword').value;

    fetch(`?ajax=1&q=${encodeURIComponent(q)}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('result').innerHTML = html;
        });
}

document.getElementById('keyword').addEventListener('keyup',()=>{
    clearTimeout(timer);
    timer = setTimeout(loadBarang,300);
});

loadBarang();
</script>

</body>
</html>


// =============================================================
// FILE: data/user.php
// =============================================================

session_start();
include "../config/koneksi.php";

// Cek login
if(!isset($_SESSION['id_user'])){
    header("Location: ../auth/auth.php");
    exit;
}

// Ambil data user
$id_user = $_SESSION['id_user'];
$nama = $_SESSION['nama'];

// Ambil riwayat aktivitas
$riwayat = mysqli_query($koneksi,"SELECT * FROM activity_log WHERE id_user='$id_user' ORDER BY created_at DESC LIMIT 10");

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard User</title>
<style>
/* Reset & font */
body, html{margin:0;padding:0;font-family: 'Segoe UI', sans-serif;background:#f0f4f8;color:#333;}
a{text-decoration:none;color:inherit;}

/* Layout */
header{background:#4a90e2;color:#fff;padding:20px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 6px rgba(0,0,0,0.1);}
header h1{margin:0;font-size:24px;}
nav button{padding:10px 20px;background:#ff5e5e;border:none;border-radius:8px;color:#fff;font-weight:600;cursor:pointer;transition:0.3s;}
nav button:hover{opacity:.85;}

/* Main container */
.container{display:flex;gap:20px;padding:20px;flex-wrap:wrap;}
.card{background:#fff;border-radius:16px;padding:20px;flex:1;min-width:300px;box-shadow:0 6px 18px rgba(0,0,0,0.08);transition:0.3s;}
.card:hover{transform:translateY(-5px);box-shadow:0 10px 25px rgba(0,0,0,0.15);}

/* Riwayat table */
table{width:100%;border-collapse:collapse;margin-top:10px;}
th,td{padding:12px;text-align:left;border-bottom:1px solid #eee;}
th{background:#f5f9ff;color:#4a90e2;}
tr:hover{background:#f1f6ff;}

/* Animasi */
.fade-in{animation:fadeIn 0.8s ease;}
@keyframes fadeIn{
  0%{opacity:0; transform:translateY(20px);}
  100%{opacity:1; transform:translateY(0);}
}
</style>
</head>
<body>

<header>
    <h1>Selamat Datang, <?=htmlspecialchars($nama)?></h1>
    <nav>
        <form method="post" action="../auth/logout.php">
            <button type="submit">Logout</button>
        </form>
    </nav>
</header>

<div class="container fade-in">

    <!-- Card statistik singkat -->
    <div class="card">
        <h2>Profil</h2>
        <p><strong>Nama:</strong> <?=htmlspecialchars($nama)?></p>
        <p><strong>Role:</strong> User</p>
        <p><strong>ID User:</strong> <?=$id_user?></p>
    </div>

    <!-- Card riwayat aktivitas -->
    <div class="card">
        <h2>Riwayat Aktivitas Terakhir</h2>
        <?php if(mysqli_num_rows($riwayat)>0): ?>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Aktivitas</th>
                </tr>
            </thead>
            <tbody>
                <?php while($r = mysqli_fetch_assoc($riwayat)): ?>
                <tr>
                    <td><?=date('d M Y H:i', strtotime($r['created_at']))?></td>
                    <td><?=htmlspecialchars($r['activity'])?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Belum ada aktivitas</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>


// =============================================================
// FILE: laporan/laporanpeminjaman.php
// =============================================================


// =============================================================
// FILE: laporan/riwayatpengembalian.php
// =============================================================

include __DIR__ . '/../config/koneksi.php';

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


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Pengembalian</title>
<style>
body{
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background:#0e1014;
    color:#eaeaea;
}
.container{
    max-width:1000px;
    margin:40px auto;
    background:#141820;
    padding:25px;
    border-radius:12px;
}
.badge{
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    background:#22c55e;
    color:#000;
}
table{width:100%;border-collapse:collapse;}
th{background:#1c2230;padding:12px;text-align:left;}
td{padding:12px;border-bottom:1px solid #232a3a;}
tr:hover{background:#1a2030;}
</style>
</head>
<body>

<div class="container">
<h2>Riwayat Pengembalian</h2>
<p>Data barang yang sudah dikembalikan</p>

<table>
<tr>
    <th>ID</th>
    <th>Tanggal</th>
    <th>Barang</th>
    <th>Jumlah</th>
    <th>Status</th>
</tr>

 while($r = mysqli_fetch_assoc($q)){ ?>
<tr>
    <td><?= $r['id_peminjaman'] ?></td>
    <td><?= $r['tanggal_pinjam'] ?></td>
    <td><?= $r['nama_barang'] ?></td>
    <td><?= $r['jumlah'] ?></td>
    <td><span class="badge">Kembali</span></td>
</tr>
 } ?>

</table>
</div>

</body>
</html>


// =============================================================
// FILE: login/forgot.php
// =============================================================

session_start();
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
unset($_SESSION['msg']);


<!DOCTYPE html>
<html>
<head>
<title>Lupa Password</title>
<style>
body {margin:0; min-height:100vh; background:#f0e6d2; font-family:'Montserrat',sans-serif;
      display:flex; justify-content:center; align-items:center; color:#333;}
.container {width:400px; padding:40px; background:#fff; border-radius:15px;
            box-shadow:0 8px 20px rgba(0,0,0,0.15);}
h1 {margin:0 0 20px 0; text-align:center; font-weight:600; color:#4a3f35;}
form {display:flex; flex-direction:column; gap:15px;}
input {padding:12px; border-radius:8px; border:1px solid #ccc; outline:none; font-size:14px;
       background:#fdf6e3; color:#333;}
input:focus {border-color:#a67c52; box-shadow:0 0 5px rgba(166,124,82,0.5);}
button {padding:12px; border-radius:8px; border:none; font-weight:600; font-size:15px;
        cursor:pointer; background:#a67c52; color:#fff; transition:0.3s;}
button:hover {background:#8b5e3c; box-shadow:0 4px 10px rgba(0,0,0,0.15);}
.msg {text-align:center; font-size:14px; color:#c0392b; margin-bottom:10px;}
.link {font-size:13px; color:#4a3f35; cursor:pointer; text-align:center; text-decoration:underline;}
</style>
</head>
<body>
<div class="container">
<h1>Lupa Password</h1>
 if($msg) echo "<div class='msg'>$msg</div>"; ?>
<form method="post" action="proses_forgot.php">
<input type="email" name="email" placeholder="Masukkan Email" required>
<button>Kirim Kode OTP</button>
<div class="link"><a href="login.php">Kembali ke Login</a></div>
</form>
</div>
</body>
</html>


// =============================================================
// FILE: login/forgot_admin.php
// =============================================================

session_start();
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
unset($_SESSION['msg']);


<!DOCTYPE html>
<html>
<head>
<title>Forgot Password Admin</title>
<style>
/* Sama style login */
body{margin:0; min-height:100vh; background:#f0e6d2; font-family:'Montserrat',sans-serif; display:flex; justify-content:center; align-items:center; color:#333;}
.container{width:400px; padding:40px; background:#fff; border-radius:15px; box-shadow:0 8px 20px rgba(0,0,0,0.15);}
h1{margin:0 0 20px 0;text-align:center;font-weight:600;color:#4a3f35;}
form{display:flex;flex-direction:column;gap:15px;}
input{padding:12px;border-radius:8px;border:1px solid #ccc;outline:none;font-size:14px;background:#fdf6e3;color:#333;transition:0.2s;}
input:focus{border-color:#a67c52;box-shadow:0 0 5px rgba(166,124,82,0.5);}
button{padding:12px;border-radius:8px;border:none;font-weight:600;font-size:15px;cursor:pointer;background:#a67c52;color:#fff;transition:0.3s;}
button:hover{background:#8b5e3c;box-shadow:0 4px 10px rgba(0,0,0,0.15);}
.msg{text-align:center;font-size:14px;color:#c0392b;margin-bottom:10px;}
.link{font-size:13px;color:#4a3f35;cursor:pointer;text-align:center;text-decoration:underline;}
</style>
</head>
<body>
<div class="container">
<h1>Lupa Password Admin</h1>
 if($msg) echo "<div class='msg'>$msg</div>"; ?>
<form method="post" action="proses_forgot_admin.php">
<input type="email" name="email" placeholder="Masukkan Email" required>
<button>Kirim OTP</button>
<div class="link"><a href="login_admin.php">Kembali Login</a></div>
</form>
</div>
</body>
</html>


// =============================================================
// FILE: login/forgot_petugas.php
// =============================================================

session_start();
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
unset($_SESSION['msg']);


<!DOCTYPE html>
<html>
<head>
<title>Lupa Password Petugas</title>
<style>
/* Sama desain login */
body{
 margin:0;
 min-height:100vh;
 background: #f0e6d2;
 font-family: 'Montserrat', sans-serif;
 display:flex;
 justify-content:center;
 align-items:center;
 color:#333;
}
.container{
 width:400px;
 padding:40px;
 background:#fff;
 border-radius:15px;
 box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
h1{
 margin:0 0 20px 0;
 text-align:center;
 font-weight:600;
 color:#4a3f35;
}
form{
 display:flex;
 flex-direction:column;
 gap:15px;
}
input{
 padding:12px;
 border-radius:8px;
 border:1px solid #ccc;
 outline:none;
 font-size:14px;
 background:#fdf6e3;
 color:#333;
 transition:0.2s;
}
input:focus{
 border-color:#a67c52;
 box-shadow:0 0 5px rgba(166,124,82,0.5);
}
button{
 padding:12px;
 border-radius:8px;
 border:none;
 font-weight:600;
 font-size:15px;
 cursor:pointer;
 background:#a67c52;
 color:#fff;
 transition:0.3s;
}
button:hover{
 background:#8b5e3c;
 box-shadow:0 4px 10px rgba(0,0,0,0.15);
}
.msg{
 text-align:center;
 font-size:14px;
 color:#c0392b;
 margin-bottom:10px;
}
.link{
 font-size:13px;
 color:#4a3f35;
 cursor:pointer;
 text-align:center;
 text-decoration:underline;
}
</style>
</head>
<body>
<div class="container">
<h1>Lupa Password</h1>
 if($msg) echo "<div class='msg'>$msg</div>"; ?>
<form method="post" action="proses_forgot_petugas.php">
<input type="email" name="email" placeholder="Masukkan Email Petugas" required>
<button>Kirim OTP</button>
<div class="link"><a href="login_petugas.php">Kembali ke Login</a></div>
</form>
</div>
</body>
</html>


// =============================================================
// FILE: login/login.php
// =============================================================

session_start();
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
unset($_SESSION['msg']);


<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<style>
body {
    margin:0; min-height:100vh; background: #f0e6d2;
    font-family: 'Montserrat', sans-serif;
    display:flex; justify-content:center; align-items:center; color:#333;
}
.container {
    width:400px; padding:40px; background:#fff;
    border-radius:15px; box-shadow:0 8px 20px rgba(0,0,0,0.15);
}
h1 { margin:0 0 20px 0; text-align:center; font-weight:600; color:#4a3f35; }
form { display:flex; flex-direction:column; gap:15px; }
input { padding:12px; border-radius:8px; border:1px solid #ccc;
    outline:none; font-size:14px; background:#fdf6e3; color:#333; }
input:focus { border-color:#a67c52; box-shadow:0 0 5px rgba(166,124,82,0.5);}
button { padding:12px; border-radius:8px; border:none; font-weight:600; font-size:15px;
    cursor:pointer; background:#a67c52; color:#fff; transition:0.3s; }
button:hover { background:#8b5e3c; box-shadow:0 4px 10px rgba(0,0,0,0.15);}
.msg { text-align:center; font-size:14px; color:#c0392b; margin-bottom:10px;}
.link { font-size:13px; color:#4a3f35; cursor:pointer; text-align:center; text-decoration:underline;}
</style>
</head>
<body>

<div class="container">
<h1>Login</h1>
 if($msg) echo "<div class='msg'>$msg</div>"; ?>

<form method="post" action="proses_login.php">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button>Login</button>
<div class="link">
<a href="register.php">Belum punya akun? Register</a> | 
<a href="forgot.php">Lupa password?</a>
</div>
</form>
</div>

</body>
</html>


// =============================================================
// FILE: login/login_admin.php
// =============================================================

session_start();
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
unset($_SESSION['msg']);


<!DOCTYPE html>
<html>
<head>
<title>Login Admin</title>
<style>
body{
 margin:0; min-height:100vh; background:#f0e6d2; font-family:'Montserrat',sans-serif;
 display:flex; justify-content:center; align-items:center; color:#333;
}
.container{
 width:400px; padding:40px; background:#fff; border-radius:15px;
 box-shadow:0 8px 20px rgba(0,0,0,0.15);
}
h1{margin:0 0 20px 0;text-align:center;font-weight:600;color:#4a3f35;}
form{display:flex;flex-direction:column;gap:15px;}
input{padding:12px;border-radius:8px;border:1px solid #ccc;outline:none;font-size:14px;background:#fdf6e3;color:#333;transition:0.2s;}
input:focus{border-color:#a67c52;box-shadow:0 0 5px rgba(166,124,82,0.5);}
button{padding:12px;border-radius:8px;border:none;font-weight:600;font-size:15px;cursor:pointer;background:#a67c52;color:#fff;transition:0.3s;}
button:hover{background:#8b5e3c;box-shadow:0 4px 10px rgba(0,0,0,0.15);}
.msg{text-align:center;font-size:14px;color:#c0392b;margin-bottom:10px;}
.link{font-size:13px;color:#4a3f35;cursor:pointer;text-align:center;text-decoration:underline;}
</style>
</head>
<body>
<div class="container">
<h1>Login Admin</h1>
 if($msg) echo "<div class='msg'>$msg</div>"; ?>
<form method="post" action="proses_login_admin.php">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button>Login</button>
<div class="link"><a href="register_admin.php">Belum punya akun? Register</a> | <a href="forgot_admin.php">Lupa Password?</a></div>
</form>
</div>
</body>
</html>


// =============================================================
// FILE: login/login_petugas.php
// =============================================================

session_start();
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
unset($_SESSION['msg']);


<!DOCTYPE html>
<html>
<head>
<title>Login Petugas</title>
<style>
body{
 margin:0;
 min-height:100vh;
 background: #f0e6d2;
 font-family: 'Montserrat', sans-serif;
 display:flex;
 justify-content:center;
 align-items:center;
 color:#333;
}
.container{
 width:400px;
 padding:40px;
 background:#fff;
 border-radius:15px;
 box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
h1{
 margin:0 0 20px 0;
 text-align:center;
 font-weight:600;
 color:#4a3f35;
}
form{
 display:flex;
 flex-direction:column;
 gap:15px;
}
input{
 padding:12px;
 border-radius:8px;
 border:1px solid #ccc;
 outline:none;
 font-size:14px;
 background:#fdf6e3;
 color:#333;
 transition:0.2s;
}
input:focus{
 border-color:#a67c52;
 box-shadow:0 0 5px rgba(166,124,82,0.5);
}
button{
 padding:12px;
 border-radius:8px;
 border:none;
 font-weight:600;
 font-size:15px;
 cursor:pointer;
 background:#a67c52;
 color:#fff;
 transition:0.3s;
}
button:hover{
 background:#8b5e3c;
 box-shadow:0 4px 10px rgba(0,0,0,0.15);
}
.msg{
 text-align:center;
 font-size:14px;
 color:#c0392b;
 margin-bottom:10px;
}
.link{
 font-size:13px;
 color:#4a3f35;
 cursor:pointer;
 text-align:center;
 text-decoration:underline;
}
</style>
</head>
<body>
<div class="container">
<h1>Login Petugas</h1>
 if($msg) echo "<div class='msg'>$msg</div>"; ?>
<form method="post" action="proses_login_petugas.php">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button>Login</button>
<div class="link">
 <a href="register_petugas.php">Belum Punya Akun? Register Petugas</a> | 
 <a href="forgot_petugas.php">Lupa Password?</a>
</div>
</form>
</div>
</body>
</html>


// =============================================================
// FILE: login/logout.php
// =============================================================

session_start();
session_destroy();
header("Location: login.php");
exit;


// =============================================================
// FILE: login/proses_admin.php
// =============================================================

session_start();
include "../config/koneksi.php";

if(isset($_POST['email'],$_POST['password'])){
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT id_user,nama,password FROM users WHERE email=? AND role='admin'");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user && password_verify($pass,$user['password'])){
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = 'admin';
        header("Location: ../dashboard/admin.php");
        exit;
    }else{
        $_SESSION['msg'] = "Login gagal, email atau password salah!";
        header("Location: login_admin.php");
        exit;
    }
}



// =============================================================
// FILE: login/proses_forgot.php
// =============================================================

session_start();
include "../config/koneksi.php";
require_once "../config/mailer.php";

if(isset($_POST['email'])){
    $email = trim($_POST['email']);

    // cek email
    $stmt = $koneksi->prepare("SELECT id_user FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows == 0){
        $_SESSION['msg'] = "Email tidak terdaftar!";
        header("Location: forgot.php");
        exit;
    }

    // buat OTP
    $otp = random_int(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_email'] = $email;
    $_SESSION['otp_time'] = time();

    sendOTP($email, $otp); // function dari mailer.php

    $_SESSION['msg'] = "Kode OTP sudah dikirim ke email Anda!";
    header("Location: reset_password.php");
    exit;
} else {
    $_SESSION['msg'] = "Form tidak lengkap!";
    header("Location: forgot.php");
}


// =============================================================
// FILE: login/proses_forgot_petugas.php
// =============================================================

session_start();
include "../config/koneksi.php";
require_once "../config/mailer.php"; // fungsi kirim email

if(isset($_POST['email'])){
    $email = $_POST['email'];

    // cek email di DB
    $stmt = $koneksi->prepare("SELECT id_user FROM users WHERE email=? AND role='petugas'");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        // generate OTP 6 digit
        $otp = random_int(100000,999999);
        $_SESSION['otp_petugas'] = $otp;
        $_SESSION['otp_email_petugas'] = $email;
        $_SESSION['otp_time_petugas'] = time();

        sendOTP($email,$otp); // kirim OTP ke email
        $_SESSION['msg'] = "Kode OTP sudah dikirim ke email.";
        header("Location: verify_petugas.php");
        exit;
    } else {
        $_SESSION['msg'] = "Email tidak terdaftar!";
        header("Location: forgot_petugas.php");
        exit;
    }
}


// =============================================================
// FILE: login/proses_login.php
// =============================================================

session_start();
include "../config/koneksi.php";

if(isset($_POST['email'], $_POST['password'])){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT id_user, nama, password, role, status FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $user = $result->fetch_assoc();
        
        if($user['status'] != 'aktif'){
            $_SESSION['msg'] = "Akun tidak aktif!";
            header("Location: login.php");
            exit;
        }

        if(password_verify($password, $user['password'])){
            // set session
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            // redirect sesuai role
            if($user['role']=='admin'){
                header("Location: ../dashboard/admin.php");
            } elseif($user['role']=='petugas'){
                header("Location: ../dashboard/petugas.php");
            } else {
                header("Location: ../dashboard/user.php");
            }
            exit;
        } else {
            $_SESSION['msg'] = "Password salah!";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['msg'] = "Email tidak terdaftar!";
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION['msg'] = "Form tidak lengkap!";
    header("Location: login.php");
    exit;
}


// =============================================================
// FILE: login/proses_login_admin.php
// =============================================================

session_start();
include "../config/koneksi.php";

if(isset($_POST['email'],$_POST['password'])){
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT id_user,nama,password FROM users WHERE email=? AND role='admin'");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user && password_verify($pass,$user['password'])){
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = 'admin';
        header("Location: ../dashboard/admin.php");
        exit;
    }else{
        $_SESSION['msg'] = "Login gagal, email atau password salah!";
        header("Location: login_admin.php");
        exit;
    }
}



// =============================================================
// FILE: login/proses_login_petugas.php
// =============================================================

session_start();
include "../config/koneksi.php";

if(isset($_POST['email'], $_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT id_user,nama,password,role FROM users WHERE email=? AND role='petugas'");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($user = $result->fetch_assoc()){
        if(password_verify($password, $user['password'])){
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            header("Location: ../dashboard/petugas.php");
            exit;
        } else {
            $_SESSION['msg'] = "Password salah!";
            header("Location: login_petugas.php");
            exit;
        }
    } else {
        $_SESSION['msg'] = "Email tidak terdaftar atau bukan petugas!";
        header("Location: login_petugas.php");
        exit;
    }
}



// =============================================================
// FILE: login/proses_register.php
// =============================================================

session_start();
include "../config/koneksi.php";

if(isset($_POST['nama'], $_POST['email'], $_POST['password'])){
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // cek email sudah ada
    $stmt = $koneksi->prepare("SELECT id_user FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $_SESSION['msg'] = "Email sudah terdaftar!";
        header("Location: register.php");
        exit;
    }

    $stmt->close();

    // insert user baru
    $stmt = $koneksi->prepare("INSERT INTO users (nama,email,password,role,status) VALUES (?, ?, ?, 'user', 'aktif')");
    $stmt->bind_param("sss", $nama, $email, $password);
    if($stmt->execute()){
        $_SESSION['msg'] = "Register berhasil, silakan login!";
        header("Location: login.php");
    } else {
        $_SESSION['msg'] = "Terjadi kesalahan: ".$stmt->error;
        header("Location: register.php");
    }
    $stmt->close();
} else {
    $_SESSION['msg'] = "Form tidak lengkap!";
    header("Location: register.php");
}


// =============================================================
// FILE: login/proses_register_admin.php
// =============================================================

session_start();
include "../config/koneksi.php";

if(isset($_POST['nama'], $_POST['email'], $_POST['password'])){
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah email sudah ada di tabel users
    $stmt = $koneksi->prepare("SELECT id_user FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $_SESSION['msg'] = "Email sudah terdaftar!";
        header("Location: register_admin.php");
        exit;
    }
    $stmt->close();

    // Insert admin baru
    $stmt = $koneksi->prepare("INSERT INTO users(nama,email,password,role,status) VALUES(?,?,?,'admin','aktif')");
    $stmt->bind_param("sss",$nama,$email,$password);

    if($stmt->execute()){
        $_SESSION['msg'] = "Register berhasil, silakan login!";
        header("Location: login_admin.php");
    }else{
        $_SESSION['msg'] = "Terjadi kesalahan: ".$stmt->error;
        header("Location: register_admin.php");
    }
}



// =============================================================
// FILE: login/proses_register_petugas.php
// =============================================================

session_start();
include "../config/koneksi.php";

if(isset($_POST['nama'], $_POST['email'], $_POST['password'])){
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // cek email apakah sudah terdaftar
    $stmt = $koneksi->prepare("SELECT id_user FROM users WHERE email=? AND role='petugas'");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $_SESSION['msg'] = "Email sudah terdaftar!";
        header("Location: register_petugas.php");
        exit;
    }

    // insert data baru
    $stmt = $koneksi->prepare("INSERT INTO users (nama,email,password,role,status) VALUES (?,?,?,'petugas','aktif')");
    $stmt->bind_param("sss",$nama,$email,$password);

    if($stmt->execute()){
        $_SESSION['msg'] = "Registrasi berhasil, silakan login.";
        header("Location: login_petugas.php");
        exit;
    } else {
        $_SESSION['msg'] = "Terjadi kesalahan: " . $stmt->error;
        header("Location: register_petugas.php");
        exit;
    }
}


// =============================================================
// FILE: login/proses_reset.php
// =============================================================

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include "../config/koneksi.php";

if(isset($_POST['otp'], $_POST['password'])){
    if(!isset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['otp_time'])){
        $_SESSION['msg'] = "OTP tidak valid!";
        header("Location: forgot.php");
        exit;
    }

    if($_POST['otp'] != $_SESSION['otp'] || (time() - $_SESSION['otp_time']) > 300){
        $_SESSION['msg'] = "OTP salah atau kadaluarsa!";
        header("Location: reset_password.php");
        exit;
    }

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_SESSION['otp_email'];

    $stmt = $koneksi->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->bind_param("ss", $password, $email);
    if($stmt->execute()){
        session_unset();
        $_SESSION['msg'] = "Password berhasil diubah, silakan login!";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['msg'] = "Terjadi kesalahan: ".$stmt->error;
        header("Location: reset_password.php");
        exit;
    }
} else {
    $_SESSION['msg'] = "Form tidak lengkap!";
    header("Location: reset_password.php");
}


// =============================================================
// FILE: login/proses_reset_admin.php
// =============================================================

session_start();
include "../config/koneksi.php";

if(isset($_POST['otp'],$_POST['password'])){
    if(!isset($_SESSION['otp']) || !isset($_SESSION['otp_email'])){
        $_SESSION['msg'] = "Sesi OTP hilang!";
        header("Location: forgot_admin.php");
        exit;
    }

    $otp = $_POST['otp'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $email = $_SESSION['otp_email'];

    if($otp != $_SESSION['otp'] || (time() - $_SESSION['otp_time']) > 300){
        $_SESSION['msg'] = "OTP salah atau kadaluarsa!";
        header("Location: reset_admin.php");
        exit;
    }

    $stmt = $koneksi->prepare("UPDATE users SET password=? WHERE email=? AND role='admin'");
    $stmt->bind_param("ss",$password,$email);

    if($stmt->execute()){
        session_destroy();
        $_SESSION['msg'] = "Password berhasil direset, silakan login!";
        header("Location: login_admin.php");
    }else{
        $_SESSION['msg'] = "Terjadi kesalahan: ".$stmt->error;
        header("Location: reset_admin.php");
    }
}



// =============================================================
// FILE: login/proses_reset_petugas.php
// =============================================================

session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['step_reset_petugas'])){
    $_SESSION['msg'] = "Silakan lakukan verifikasi OTP terlebih dahulu.";
    header("Location: forgot_petugas.php");
    exit;
}

if(isset($_POST['password'])){
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_SESSION['otp_email_petugas'];

    $stmt = $koneksi->prepare("UPDATE users SET password=? WHERE email=? AND role='petugas'");
    $stmt->bind_param("ss",$password,$email);

    if($stmt->execute()){
        unset($_SESSION['step_reset_petugas']);
        unset($_SESSION['otp_petugas']);
        unset($_SESSION['otp_email_petugas']);
        unset($_SESSION['otp_time_petugas']);

        $_SESSION['msg'] = "Password berhasil direset, silakan login.";
        header("Location: login_petugas.php");
        exit;
    } else {
        $_SESSION['msg'] = "Gagal reset password!";
        header("Location: reset_petugas.php");
        exit;
    }
}


// =============================================================
// FILE: login/proses_verify_petugas.php
// =============================================================

session_start();

if(isset($_POST['otp'])){
    if(!isset($_SESSION['otp_petugas'])){
        $_SESSION['msg'] = "Silakan request OTP terlebih dahulu.";
        header("Location: forgot_petugas.php");
        exit;
    }

    $otp_input = $_POST['otp'];
    $otp_session = $_SESSION['otp_petugas'];
    $otp_time = $_SESSION['otp_time_petugas'];

    if($otp_input == $otp_session && (time() - $otp_time) < 300){
        $_SESSION['step_reset_petugas'] = true;
        header("Location: reset_petugas.php");
        exit;
    } else {
        $_SESSION['msg'] = "OTP salah atau kadaluarsa!";
        header("Location: verify_petugas.php");
        exit;
    }
}


// =============================================================
// FILE: login/register.php
// =============================================================

session_start();
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
unset($_SESSION['msg']);


<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<style>
body {margin:0; min-height:100vh; background:#f0e6d2; font-family:'Montserrat',sans-serif;
      display:flex; justify-content:center; align-items:center; color:#333;}
.container {width:400px; padding:40px; background:#fff; border-radius:15px;
            box-shadow:0 8px 20px rgba(0,0,0,0.15);}
h1 {margin:0 0 20px 0; text-align:center; font-weight:600; color:#4a3f35;}
form {display:flex; flex-direction:column; gap:15px;}
input {padding:12px; border-radius:8px; border:1px solid #ccc; outline:none; font-size:14px;
       background:#fdf6e3; color:#333;}
input:focus {border-color:#a67c52; box-shadow:0 0 5px rgba(166,124,82,0.5);}
button {padding:12px; border-radius:8px; border:none; font-weight:600; font-size:15px;
        cursor:pointer; background:#a67c52; color:#fff; transition:0.3s;}
button:hover {background:#8b5e3c; box-shadow:0 4px 10px rgba(0,0,0,0.15);}
.msg {text-align:center; font-size:14px; color:#c0392b; margin-bottom:10px;}
.link {font-size:13px; color:#4a3f35; cursor:pointer; text-align:center; text-decoration:underline;}
</style>
</head>
<body>
<div class="container">
<h1>Register</h1>
 if($msg) echo "<div class='msg'>$msg</div>"; ?>
<form method="post" action="proses_register.php">
<input type="text" name="nama" placeholder="Nama Lengkap" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button>Register</button>
<div class="link"><a href="login.php">Sudah punya akun? Login</a></div>
</form>
</div>
</body>
</html>


// =============================================================
// FILE: login/register_admin.php
// =============================================================

session_start();
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
unset($_SESSION['msg']);


<!DOCTYPE html>
<html>
<head>
<title>Register Admin</title>
<style>
/* Sama seperti login */
body{margin:0; min-height:100vh; background:#f0e6d2; font-family:'Montserrat',sans-serif; display:flex; justify-content:center; align-items:center; color:#333;}
.container{width:400px; padding:40px; background:#fff; border-radius:15px; box-shadow:0 8px 20px rgba(0,0,0,0.15);}
h1{margin:0 0 20px 0;text-align:center;font-weight:600;color:#4a3f35;}
form{display:flex;flex-direction:column;gap:15px;}
input{padding:12px;border-radius:8px;border:1px solid #ccc;outline:none;font-size:14px;background:#fdf6e3;color:#333;transition:0.2s;}
input:focus{border-color:#a67c52;box-shadow:0 0 5px rgba(166,124,82,0.5);}
button{padding:12px;border-radius:8px;border:none;font-weight:600;font-size:15px;cursor:pointer;background:#a67c52;color:#fff;transition:0.3s;}
button:hover{background:#8b5e3c;box-shadow:0 4px 10px rgba(0,0,0,0.15);}
.msg{text-align:center;font-size:14px;color:#c0392b;margin-bottom:10px;}
.link{font-size:13px;color:#4a3f35;cursor:pointer;text-align:center;text-decoration:underline;}
</style>
</head>
<body>
<div class="container">
<h1>Register Admin</h1>
 if($msg) echo "<div class='msg'>$msg</div>"; ?>
<form method="post" action="proses_register_admin.php">
<input type="text" name="nama" placeholder="Nama" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button>Register</button>
<div class="link"><a href="login_admin.php">Sudah punya akun? Login</a></div>
</form>
</div>
</body>
</html>


// =============================================================
// FILE: login/register_petugas.php
// =============================================================

session_start();
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
unset($_SESSION['msg']);


<!DOCTYPE html>
<html>
<head>
<title>Register Petugas</title>
<style>
/* Sama desain login */
body{
 margin:0;
 min-height:100vh;
 background: #f0e6d2;
 font-family: 'Montserrat', sans-serif;
 display:flex;
 justify-content:center;
 align-items:center;
 color:#333;
}
.container{
 width:400px;
 padding:40px;
 background:#fff;
 border-radius:15px;
 box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
h1{
 margin:0 0 20px 0;
 text-align:center;
 font-weight:600;
 color:#4a3f35;
}
form{
 display:flex;
 flex-direction:column;
 gap:15px;
}
input{
 padding:12px;
 border-radius:8px;
 border:1px solid #ccc;
 outline:none;
 font-size:14px;
 background:#fdf6e3;
 color:#333;
 transition:0.2s;
}
input:focus{
 border-color:#a67c52;
 box-shadow:0 0 5px rgba(166,124,82,0.5);
}
button{
 padding:12px;
 border-radius:8px;
 border:none;
 font-weight:600;
 font-size:15px;
 cursor:pointer;
 background:#a67c52;
 color:#fff;
 transition:0.3s;
}
button:hover{
 background:#8b5e3c;
 box-shadow:0 4px 10px rgba(0,0,0,0.15);
}
.msg{
 text-align:center;
 font-size:14px;
 color:#c0392b;
 margin-bottom:10px;
}
.link{
 font-size:13px;
 color:#4a3f35;
 cursor:pointer;
 text-align:center;
 text-decoration:underline;
}
</style>
</head>
<body>
<div class="container">
<h1>Register Petugas</h1>
 if($msg) echo "<div class='msg'>$msg</div>"; ?>
<form method="post" action="proses_register_petugas.php">
<input type="text" name="nama" placeholder="Nama Lengkap" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button>Register</button>
<div class="link"><a href="login_petugas.php">Kembali ke Login</a></div>
</form>
</div>
</body>
</html>


// =============================================================
// FILE: login/reset_admin.php
// =============================================================

session_start();
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
unset($_SESSION['msg']);

if(!isset($_SESSION['otp_email'])){
    $_SESSION['msg'] = "Sesi OTP hilang!";
    header("Location: forgot_admin.php");
    exit;
}


<!DOCTYPE html>
<html>
<head>
<title>Reset Password Admin</title>
<style>
/* Sama style login */
body{margin:0; min-height:100vh; background:#f0e6d2; font-family:'Montserrat',sans-serif; display:flex; justify-content:center; align-items:center; color:#333;}
.container{width:400px; padding:40px; background:#fff; border-radius:15px; box-shadow:0 8px 20px rgba(0,0,0,0.15);}
h1{margin:0 0 20px 0;text-align:center;font-weight:600;color:#4a3f35;}
form{display:flex;flex-direction:column;gap:15px;}
input{padding:12px;border-radius:8px;border:1px solid #ccc;outline:none;font-size:14px;background:#fdf6e3;color:#333;transition:0.2s;}
input:focus{border-color:#a67c52;box-shadow:0 0 5px rgba(166,124,82,0.5);}
button{padding:12px;border-radius:8px;border:none;font-weight:600;font-size:15px;cursor:pointer;background:#a67c52;color:#fff;transition:0.3s;}
button:hover{background:#8b5e3c;box-shadow:0 4px 10px rgba(0,0,0,0.15);}
.msg{text-align:center;font-size:14px;color:#c0392b;margin-bottom:10px;}
.link{font-size:13px;color:#4a3f35;cursor:pointer;text-align:center;text-decoration:underline;}
</style>
</head>
<body>
<div class="container">
<h1>Reset Password Admin</h1>
 if($msg) echo "<div class='msg'>$msg</div>"; ?>
<form method="post" action="proses_reset_admin.php">
<input type="text" name="otp" placeholder="Masukkan OTP" required>
<input type="password" name="password" placeholder="Password Baru" required>
<button>Reset Password</button>
<div class="link"><a href="login_admin.php">Kembali Login</a></div>
</form>
</div>
</body>
</html>


// =============================================================
// FILE: login/reset_password.php
// =============================================================

session_start();
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
unset($_SESSION['msg']);

if(!isset($_SESSION['otp_email'])){
    $_SESSION['msg'] = "Akses ditolak!";
    header("Location: forgot.php");
    exit;
}


<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>
<style>
body {margin:0; min-height:100vh; background:#f0e6d2; font-family:'Montserrat',sans-serif;
      display:flex; justify-content:center; align-items:center; color:#333;}
.container {width:400px; padding:40px; background:#fff; border-radius:15px;
            box-shadow:0 8px 20px rgba(0,0,0,0.15);}
h1 {margin:0 0 20px 0; text-align:center; font-weight:600; color:#4a3f35;}
form {display:flex; flex-direction:column; gap:15px;}
input {padding:12px; border-radius:8px; border:1px solid #ccc; outline:none; font-size:14px;
       background:#fdf6e3; color:#333;}
input:focus {border-color:#a67c52; box-shadow:0 0 5px rgba(166,124,82,0.5);}
button {padding:12px; border-radius:8px; border:none; font-weight:600; font-size:15px;
        cursor:pointer; background:#a67c52; color:#fff; transition:0.3s;}
button:hover {background:#8b5e3c; box-shadow:0 4px 10px rgba(0,0,0,0.15);}
.msg {text-align:center; font-size:14px; color:#c0392b; margin-bottom:10px;}
.link {font-size:13px; color:#4a3f35; cursor:pointer; text-align:center; text-decoration:underline;}
</style>
</head>
<body>
<div class="container">
<h1>Reset Password</h1>
 if($msg) echo "<div class='msg'>$msg</div>"; ?>
<form method="post" action="proses_reset.php">
<input type="text" name="otp" placeholder="Masukkan OTP" required>
<input type="password" name="password" placeholder="Password Baru" required>
<button>Reset Password</button>
<div class="link"><a href="login.php">Kembali ke Login</a></div>
</form>
</div>
</body>
</html>


// =============================================================
// FILE: login/reset_petugas.php
// =============================================================

session_start();
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
unset($_SESSION['msg']);

if(!isset($_SESSION['step_reset_petugas'])){
    $_SESSION['msg'] = "Silakan lakukan verifikasi OTP terlebih dahulu.";
    header("Location: forgot_petugas.php");
    exit;
}


<!DOCTYPE html>
<html>
<head>
<title>Reset Password Petugas</title>
<style>
/* Sama desain login */
body{margin:0;min-height:100vh;background:#f0e6d2;font-family:'Montserrat',sans-serif;display:flex;justify-content:center;align-items:center;color:#333;}
.container{width:400px;padding:40px;background:#fff;border-radius:15px;box-shadow:0 8px 20px rgba(0,0,0,0.15);}
h1{margin:0 0 20px 0;text-align:center;font-weight:600;color:#4a3f35;}
form{display:flex;flex-direction:column;gap:15px;}
input{padding:12px;border-radius:8px;border:1px solid #ccc;outline:none;font-size:14px;background:#fdf6e3;color:#333;transition:0.2s;}
input:focus{border-color:#a67c52;box-shadow:0 0 5px rgba(166,124,82,0.5);}
button{padding:12px;border-radius:8px;border:none;font-weight:600;font-size:15px;cursor:pointer;background:#a67c52;color:#fff;transition:0.3s;}
button:hover{background:#8b5e3c;box-shadow:0 4px 10px rgba(0,0,0,0.15);}
.msg{text-align:center;font-size:14px;color:#c0392b;margin-bottom:10px;}
.link{font-size:13px;color:#4a3f35;cursor:pointer;text-align:center;text-decoration:underline;}
</style>
</head>
<body>
<div class="container">
<h1>Reset Password</h1>
 if($msg) echo "<div class='msg'>$msg</div>"; ?>
<form method="post" action="proses_reset_petugas.php">
<input type="password" name="password" placeholder="Password Baru" required>
<button>Reset Password</button>
<div class="link"><a href="login_petugas.php">Kembali ke Login</a></div>
</form>
</div>
</body>
</html>


// =============================================================
// FILE: login/verify_petugas.php
// =============================================================

session_start();
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
unset($_SESSION['msg']);

if(!isset($_SESSION['otp_petugas'])){
    $_SESSION['msg'] = "Silakan lakukan request OTP terlebih dahulu.";
    header("Location: forgot_petugas.php");
    exit;
}


<!DOCTYPE html>
<html>
<head>
<title>Verifikasi OTP Petugas</title>
<style>
/* Sama desain login */
body{margin:0;min-height:100vh;background:#f0e6d2;font-family:'Montserrat',sans-serif;display:flex;justify-content:center;align-items:center;color:#333;}
.container{width:400px;padding:40px;background:#fff;border-radius:15px;box-shadow:0 8px 20px rgba(0,0,0,0.15);}
h1{margin:0 0 20px 0;text-align:center;font-weight:600;color:#4a3f35;}
form{display:flex;flex-direction:column;gap:15px;}
input{padding:12px;border-radius:8px;border:1px solid #ccc;outline:none;font-size:14px;background:#fdf6e3;color:#333;transition:0.2s;}
input:focus{border-color:#a67c52;box-shadow:0 0 5px rgba(166,124,82,0.5);}
button{padding:12px;border-radius:8px;border:none;font-weight:600;font-size:15px;cursor:pointer;background:#a67c52;color:#fff;transition:0.3s;}
button:hover{background:#8b5e3c;box-shadow:0 4px 10px rgba(0,0,0,0.15);}
.msg{text-align:center;font-size:14px;color:#c0392b;margin-bottom:10px;}
.link{font-size:13px;color:#4a3f35;cursor:pointer;text-align:center;text-decoration:underline;}
</style>
</head>
<body>
<div class="container">
<h1>Verifikasi OTP</h1>
 if($msg) echo "<div class='msg'>$msg</div>"; ?>
<form method="post" action="proses_verify_petugas.php">
<input type="text" name="otp" placeholder="Masukkan OTP" required>
<button>Verifikasi</button>
<div class="link"><a href="forgot_petugas.php">Kirim ulang OTP</a></div>
</form>
</div>
</body>
</html>


// =============================================================
// FILE: petugas/approvepeminjaman.php
// =============================================================

session_start();
include "../config/koneksi.php";

$id = $_GET['id'];

mysqli_query($koneksi,"
  UPDATE peminjaman 
  SET status='approved'
  WHERE id_peminjaman='$id'
");

header("Location: index.php");


// =============================================================
// FILE: petugas/detailpeminjaman.php
// =============================================================

include "../config/koneksi.php";
$id = $_GET['id'];

$q = mysqli_query($koneksi,"
  SELECT b.nama_barang, d.jumlah
  FROM peminjaman_detail d
  JOIN barang b ON d.id_barang=b.id_barang
  WHERE d.id_peminjaman='$id'
");


<h3>Detail Barang</h3>
<ul>
 while($r=mysqli_fetch_assoc($q)){ ?>
  <li><?= $r['nama_barang'] ?> x <?= $r['jumlah'] ?></li>
 } ?>
</ul>


// =============================================================
// FILE: petugas/pengembalian.php
// =============================================================

include "../config/koneksi.php";

$q = mysqli_query($koneksi,"
  SELECT p.*, u.nama
  FROM peminjaman p
  JOIN users u ON p.id_user=u.id_user
  WHERE p.status='returned'
");


<table border="1">
<tr>
  <th>User</th><th>Bukti</th><th>Denda</th>
</tr>

 while($r=mysqli_fetch_assoc($q)){ ?>
<tr>
  <td><?= $r['nama'] ?></td>
  <td>
    <a href="../uploads/<?= $r['bukti'] ?>" target="_blank">
      Lihat
    </a>
  </td>
  <td><?= $r['denda'] ?></td>
</tr>
 } ?>
</table>


// =============================================================
// FILE: transaksi/bayar_denda.php
// =============================================================

session_start();
include "../config/koneksi.php";

$id_peminjaman = $_POST['id_peminjaman'];
$id_user = $_SESSION['id_user'];
$jumlah = $_POST['jumlah'];

mysqli_query($koneksi, "
    INSERT INTO pembayaran_denda 
    (id_peminjaman, id_user, jumlah, tgl_bayar, metode)
    VALUES 
    ('$id_peminjaman','$id_user','$jumlah',CURDATE(),'cash')
");

echo "Denda dibayar, akun aktif kembali";


// =============================================================
// FILE: transaksi/kembali.php
// =============================================================

include __DIR__ . '/../config/koneksi.php';

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


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengembalian Barang</title>
<link rel="stylesheet" href="../assets/style.css">
<style>
body{
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background:#0e1014;
    color:#eaeaea;
}
.container{
    max-width:900px;
    margin:40px auto;
    background:#141820;
    padding:25px;
    border-radius:12px;
    box-shadow:0 0 20px rgba(0,0,0,0.5);
}
h2{margin:0;}
.subtitle{color:#9aa0aa;margin-bottom:20px;}
table{width:100%;border-collapse:collapse;}
th{
    background:#1c2230;
    padding:12px;
    text-align:left;
}
td{
    padding:12px;
    border-bottom:1px solid #232a3a;
}
tr:hover{background:#1a2030;}
.btn{
    padding:8px 14px;
    background:#2f6bff;
    color:#fff;
    text-decoration:none;
    border-radius:6px;
    font-size:13px;
    transition:0.2s;
}
.btn:hover{background:#1f52cc;}
</style>
</head>

<body>

<div class="container">
    <h2>Pengembalian Barang</h2>
    <p class="subtitle">Daftar barang yang sedang dipinjam</p>

    <table>
        <tr>
            <th>ID</th>
            <th>Barang</th>
            <th>Jumlah</th>
            <th>Aksi</th>
        </tr>

        <?php while($r = mysqli_fetch_assoc($q)) { ?>
        <tr>
            <td><?= $r['id_peminjaman'] ?></td>
            <td><?= $r['nama_barang'] ?></td>
            <td><?= $r['jumlah'] ?></td>
            <td>
                <a class="btn" href="proses_kembali.php?id=<?= $r['id_peminjaman'] ?>">
                    Kembalikan
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>


// =============================================================
// FILE: transaksi/pinjam.php
// =============================================================

session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login/cek_petugas.php");
    exit;
}

include __DIR__ . '/../config/koneksi.php';

$id_user = $_SESSION['id_user'];

// ambil barang tersedia
$qBarang = mysqli_query($koneksi, "SELECT * FROM barang");

if(!$qBarang){
    die("Query error: " . mysqli_error($koneksi));
}


<!DOCTYPE html>
<html>
<head>
<title>Pinjam Barang</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
:root{
 --bg:#0e1014; --panel:#141821; --card:#171c26; --border:#1f2533;
 --text:#e6e9ef; --muted:#9aa4b2; --accent:#5f8cff;
}
body{
    margin:0;
    font-family:'Montserrat',sans-serif;
    background:var(--bg);
    color:var(--text);
}
.container{
    display:flex;
    max-width:1200px;
    margin:auto;
    min-height:100vh;
}
/* Sidebar */
.sidebar{
    width:220px;
    background:#1f2533;
    padding:20px;
}
.sidebar h2{
    margin-top:0;
    font-size:20px;
    color:var(--accent);
}
.sidebar a{
    display:block;
    color:var(--text);
    text-decoration:none;
    padding:10px;
    margin:8px 0;
    border-radius:6px;
    transition:0.3s;
}
.sidebar a:hover{
    background:var(--accent);
    color:#fff;
}

/* Main */
.main{
    flex:1;
    padding:20px;
}

/* Panel Form */
.panel{
    background:var(--panel);
    padding:20px;
    border-radius:14px;
    box-shadow:0 4px 12px rgba(0,0,0,0.2);
    max-width:600px;
    margin:auto;
}
.panel h3{
    margin-top:0;
    color:var(--accent);
}

/* Form */
form{
    display:flex;
    flex-direction:column;
}
form label{
    margin-top:10px;
    margin-bottom:5px;
    font-weight:500;
    color:var(--text);
}
form input, form select{
    padding:10px;
    border-radius:8px;
    border:1px solid var(--border);
    background:var(--card);
    color:var(--text);
}
form button{
    margin-top:15px;
    padding:10px;
    background:var(--accent);
    border:none;
    color:#fff;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}
form button:hover{
    opacity:0.85;
}

/* Responsive */
@media(max-width:768px){
    .container{
        flex-direction:column;
    }
    .panel{
        width:100%;
        margin:auto;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <h2>Dashboard User</h2>
        <a href="user_dashboard.php">Home</a>
        <a href="../user/ajukan_peminjaman.php">Peminjaman</a>
        <a href="../transaksi/pinjam.php">Pinjam Barang</a>
        <a href="../login/logout.php">Logout</a>
    </div>

    <div class="main">
        <div class="panel">
            <h3>Form Peminjaman Barang</h3>
            <form action="proses_pinjam.php" method="POST">
                <label>Barang</label>
                <select name="id_barang" required>
                    <option value="">-- Pilih Barang --</option>
                    <?php while($barang = mysqli_fetch_assoc($qBarang)): ?>
                        <option value="<?= $barang['id_barang'] ?>">
                            <?= htmlspecialchars($barang['nama_barang']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label>Jumlah</label>
                <input type="number" name="jumlah" min="1" required>

                <label>Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" required>

                <button type="submit">Pinjam Barang</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>


// =============================================================
// FILE: user/ajukan_peminjaman.php
// =============================================================

session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login/cek_petugas.php");
    exit;
}

include __DIR__ . '/../config/koneksi.php';

$id_user = $_SESSION['id_user'];

// ambil barang tersedia
$qBarang = mysqli_query($koneksi, "SELECT * FROM barang");
if (!$qBarang) die("Query error: " . mysqli_error($koneksi));


<!DOCTYPE html>
<html>
<head>
<title>Dashboard User - Peminjaman</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
:root{
  --bg:#f5f2ec;
  --panel:#fff;
  --accent:#5f8cff;
  --text:#333;
  --muted:#666;
}
*{box-sizing:border-box;margin:0;padding:0;font-family:'Montserrat',sans-serif;}
body{background:var(--bg);}
.wrapper{display:flex;min-height:100vh;}

/* Sidebar */
.sidebar{
  width:220px;
  background:#141821;
  color:#e6e9ef;
  display:flex;
  flex-direction:column;
  padding:20px;
}
.sidebar h2{margin-bottom:30px;color:var(--accent);}
.sidebar a{
  text-decoration:none;
  color:#e6e9ef;
  padding:12px 15px;
  border-radius:8px;
  margin-bottom:8px;
  display:block;
  transition:0.3s;
}
.sidebar a:hover{background:var(--accent);color:#fff;}

/* Main content */
.main{
  flex:1;
  padding:30px;
}
.panel{
  background:var(--panel);
  padding:25px;
  border-radius:15px;
  box-shadow:0 6px 15px rgba(0,0,0,0.1);
  max-width:600px;
  margin:auto;
}
.panel h2{
  text-align:center;
  margin-bottom:20px;
  color:var(--accent);
}
.form-group{margin-bottom:15px;}
.form-group label{display:block;margin-bottom:5px;color:var(--text);font-weight:600;}
.form-group input, .form-group select{
  width:100%;
  padding:10px;
  border-radius:8px;
  border:1px solid #ccc;
  font-size:14px;
}
button{
  width:100%;
  padding:12px;
  background:var(--accent);
  color:#fff;
  border:none;
  border-radius:8px;
  font-size:16px;
  font-weight:600;
  cursor:pointer;
  transition:0.3s;
}
button:hover{background:#4f7ce0;}

/* Responsive */
@media(max-width:768px){
  .wrapper{flex-direction:column;}
  .sidebar{width:100%;flex-direction:row;flex-wrap:wrap;}
  .sidebar a{flex:1;text-align:center;}
}
</style>
</head>
<body>

<div class="wrapper">

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Dashboard</h2>
    <a href="user_dashboard.php">Beranda</a>
    <a href="pinjam_barang.php">Pinjam Barang</a>
    <a href="riwayat.php">Riwayat</a>
    <a href="../login/logout.php">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main">
    <div class="panel">
      <h2>Ajukan Peminjaman Barang</h2>
      <form action="proses_pinjam.php" method="post">
        
        <div class="form-group">
          <label>Barang</label>
          <select name="id_barang" required>
            <option value="">-- Pilih Barang --</option>
            <?php while($barang = mysqli_fetch_assoc($qBarang)): ?>
            <option value="<?= $barang['id_barang'] ?>">
              <?= htmlspecialchars($barang['nama_barang']) ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="form-group">
          <label>Jumlah</label>
          <input type="number" name="jumlah" min="1" required>
        </div>

        <div class="form-group">
          <label>Tanggal Pinjam</label>
          <input type="date" name="tanggal_pinjam" required>
        </div>

        <button type="submit">Ajukan Peminjaman</button>
      </form>
    </div>
  </div>

</div>

</body>
</html>


// =============================================================
// FILE: user/pengembalian.php
// =============================================================

session_start();
include "../config/koneksi.php";

$id_user = $_SESSION['id_user'];

$q = mysqli_query($koneksi,"
  SELECT * FROM peminjaman
  WHERE id_user='$id_user' AND status='approved'
");


<table border="1">
<tr>
  <th>Tanggal Pinjam</th>
  <th>Aksi</th>
</tr>

 while($p=mysqli_fetch_assoc($q)){ ?>
<tr>
  <td><?= $p['tgl_pinjam'] ?></td>
  <td>
    <a href="proses_kembali.php?id=<?= $p['id_peminjaman'] ?>">
      Kembalikan
    </a>
  </td>
</tr>
 } ?>
</table>


// =============================================================
// FILE: user/proses_kembali.php
// =============================================================

include "../config/koneksi.php";

$id = $_POST['id'];

$namaFile = time().'_'.$_FILES['bukti']['name'];
move_uploaded_file(
  $_FILES['bukti']['tmp_name'],
  "../uploads/".$namaFile
);

mysqli_query($koneksi,"
  UPDATE peminjaman
  SET status='returned',
      tgl_kembali=CURDATE(),
      bukti='$namaFile'
  WHERE id_peminjaman='$id'
");

header("Location: pengembalian.php");


// =============================================================
// FILE: user/proses_pinjam.php
// =============================================================

session_start();
include "../config/koneksi.php";

$id_user = $_SESSION['id_user'];

// CEK STATUS USER
$q = mysqli_query($koneksi, "
    SELECT status, blacklist 
    FROM users 
    WHERE id_user='$id_user'
");
$u = mysqli_fetch_assoc($q);

if ($u['blacklist'] == 'ya') {
    die("Akun diblacklist permanen");
}

if ($u['status'] == 'suspend') {
    die("Akun disuspend, lunasi denda dulu");
}

// LANJUT INSERT (DB TRIGGER JUGA CEK)
mysqli_query($koneksi, "
    INSERT INTO peminjaman (id_user, tgl_pinjam, status)
    VALUES ('$id_user', CURDATE(), 'pending')
");

echo "Peminjaman berhasil diajukan";
