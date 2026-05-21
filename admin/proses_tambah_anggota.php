<?php
// 1. Hubungkan koneksi database
include '../config/koneksi.php';
global $conn;

// 2. Ambil data kiriman dari form POST
$Username    = $_POST['Username'];
$Password    = $_POST['Password'];
$NamaLengkap = $_POST['NamaLengkap'];
$TempatLahir = $_POST['TempatLahir'];
$TanggalLahir = $_POST['TanggalLahir'];
$JenisKelamin = $_POST['JenisKelamin'];
$NoHP = $_POST['NoHP'];
$Role = $_POST['Role'];
$Bagian = $_POST['Bagian'];


// Keamanan: Mencegah SQL Injection dengan escaping data inputan
$Username    = mysqli_real_escape_string($conn, $Username);
$Password    = mysqli_real_escape_string($conn, $Password);
$NamaLengkap = mysqli_real_escape_string($conn, $NamaLengkap);
$TempatLahir = mysqli_real_escape_string($conn, $TempatLahir);
$TanggalLahir = mysqli_real_escape_string($conn, $TanggalLahir);
$JenisKelamin = mysqli_real_escape_string($conn, $JenisKelamin);
$NoHP = mysqli_real_escape_string($conn, $NoHP);
$Role = mysqli_real_escape_string($conn, $Role);
$Bagian = mysqli_real_escape_string($conn, $Bagian);

// 3. Jalankan query INSERT untuk menambahkan data ke tabel anggota
// Sesuaikan nama kolom berikut jika ada perbedaan dengan database asli kamu
$query_insert = "INSERT INTO anggota (Username, Password, NamaLengkap, TempatLahir, TanggalLahir, JenisKelamin, NoHP, Role, Bagian) 
                 VALUES ('$Username', '$Password', '$NamaLengkap', '$TempatLahir', '$TanggalLahir', '$JenisKelamin', '$NoHP', '$Role', '$Bagian')";

if (mysqli_query($conn, $query_insert)) {
    // Jika berhasil, munculkan pesan sukses lalu alihkan kembali ke dashboard utama (index.php)
    echo "<script>
            alert('Anggota baru berhasil ditambahkan!'); 
            window.location='anggota.php';
          </script>";
} else {
    // Jika gagal, munculkan pesan error
    echo "<script>
            alert('Gagal menambah data! Error: " . mysqli_error($conn) . "'); 
            window.location='tambah_anggota.php';
          </script>";
}
?>