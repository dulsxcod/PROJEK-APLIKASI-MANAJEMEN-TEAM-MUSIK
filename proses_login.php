<?php
include 'config/koneksi.php';
global $conn;

session_start();

// Proteksi: Mencegah akses langsung tanpa submit form
if (!isset($_POST['Username']) || !isset($_POST['Password'])) {
    header("location:index.php");
    exit();
}

$Username = mysqli_real_escape_string($conn, $_POST['Username']);
$Password = mysqli_real_escape_string($conn, $_POST['Password']);
$Role     = $_POST['Role']; // Mengambil role dari form select (anggota / admin)

// Query ditambahkan pengecekan Role agar datanya sinkron dengan database
$query = mysqli_query($conn, "SELECT * FROM anggota WHERE BINARY Username='$Username' AND BINARY Password='$Password' AND Role='$Role'");
$cek = mysqli_num_rows($query);

if ($cek > 0) {
    // Mengambil satu baris data user dari database hasil query
    $data_user = mysqli_fetch_assoc($query);

    $_SESSION['status'] = "login";
    
    // === TAMBAHKAN BARIS INI ===
    $_SESSION['UserID'] = $data_user['UserID']; 
    // ===========================

    $_SESSION['Username'] = $Username;
    $_SESSION['Role'] = $data_user['Role'];
    $_SESSION['NamaLengkap'] = $data_user['NamaLengkap']; 

    // Logika Pengalihan Halaman Menyesuaikan Pilihan Role
    if ($data_user['Role'] == 'admin') {
        header("location:admin/index.php");
        exit();
    } else if ($data_user['Role'] == 'anggota') {
        header("location:home_anggota.php");
        exit();
    }
}
?>