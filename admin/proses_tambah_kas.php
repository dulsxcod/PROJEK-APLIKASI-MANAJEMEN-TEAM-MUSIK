<?php
// 1. Hubungkan koneksi database
include '../config/koneksi.php';
global $conn;

// 2. Ambil data kiriman dari form POST
$Tanggal    = $_POST['Tanggal'];
$Pemasukan    = $_POST['Pemasukan'];
$Pengeluaran = $_POST['Pengeluaran'];
$Keterangan = $_POST['Keterangan'];



// Keamanan: Mencegah SQL Injection dengan escaping data inputan
$Tanggal    = mysqli_real_escape_string($conn, $Tanggal);
$Pemasukan    = mysqli_real_escape_string($conn, $Pemasukan);
$Pengeluaran = mysqli_real_escape_string($conn, $Pengeluaran);
$Keterangan = mysqli_real_escape_string($conn, $Keterangan);



// 3. Jalankan query INSERT untuk menambahkan data ke tabel anggota
// Sesuaikan nama kolom berikut jika ada perbedaan dengan database asli kamu
//  BENAR (Tidak ada koma setelah field 'Seragam')
$query_tambah = "INSERT INTO kas (Tanggal, Pemasukan, Pengeluaran, Keterangan) 
                 VALUES ('$Tanggal', '$Pemasukan', '$Pengeluaran', '$Keterangan')";
if (mysqli_query($conn, $query_tambah)) {
    // Jika berhasil, munculkan pesan sukses lalu alihkan kembali ke dashboard utama (index.php)
    echo "<script>
            alert('Kas baru berhasil ditambahkan!'); 
            window.location='kas.php';
          </script>";
} else {
    // Jika gagal, munculkan pesan error
    echo "<script>
            alert('Gagal menambah data! Error: " . mysqli_error($conn) . "'); 
            window.location='tambah_kas.php';
          </script>";
}
?>