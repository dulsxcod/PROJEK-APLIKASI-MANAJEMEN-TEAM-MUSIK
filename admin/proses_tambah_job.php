<?php
// 1. Hubungkan koneksi database
include '../config/koneksi.php';
global $conn;

// 2. Ambil data kiriman dari form POST
$NamaTuanRumah    = $_POST['NamaTuanRumah'];
$Tanggal    = $_POST['Tanggal'];
$Alamat = $_POST['Alamat'];
$NamaGroup = $_POST['NamaGroup'];
$Seragam = $_POST['Seragam'];



// Keamanan: Mencegah SQL Injection dengan escaping data inputan
$NamaTuanRumah    = mysqli_real_escape_string($conn, $NamaTuanRumah);
$Tanggal    = mysqli_real_escape_string($conn, $Tanggal);
$Alamat = mysqli_real_escape_string($conn, $Alamat);
$NamaGroup = mysqli_real_escape_string($conn, $NamaGroup);
$Seragam = mysqli_real_escape_string($conn, $Seragam);


// 3. Jalankan query INSERT untuk menambahkan data ke tabel anggota
// Sesuaikan nama kolom berikut jika ada perbedaan dengan database asli kamu
//  BENAR (Tidak ada koma setelah field 'Seragam')
$query_tambah = "INSERT INTO job (NamaTuanRumah, Tanggal, Alamat, NamaGroup, Seragam) 
                 VALUES ('$NamaTuanRumah', '$Tanggal', '$Alamat', '$NamaGroup', '$Seragam')";
if (mysqli_query($conn, $query_tambah)) {
    // Jika berhasil, munculkan pesan sukses lalu alihkan kembali ke dashboard utama (index.php)
    echo "<script>
            alert('Job baru berhasil ditambahkan!'); 
            window.location='job.php';
          </script>";
} else {
    // Jika gagal, munculkan pesan error
    echo "<script>
            alert('Gagal menambah data! Error: " . mysqli_error($conn) . "'); 
            window.location='tambah_job.php';
          </script>";
}
?>