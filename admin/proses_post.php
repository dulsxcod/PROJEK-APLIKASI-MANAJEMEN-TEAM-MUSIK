<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['submit_post'])) {
    // Ambil ID User yang sedang login (seperti di index.php)
    $user_id = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : 1;
    
    // Ambil isi postingan dan cegah SQL Injection
    $isi_postingan = mysqli_real_escape_string($conn, $_POST['isi_postingan']);
    
    // Insert ke database
    $query = "INSERT INTO postingan (user_id, isi_postingan) VALUES ('$user_id', '$isi_postingan')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php"); // Kembali ke halaman utama
    } else {
        echo "<script>alert('Gagal memposting!'); window.location.href='index.php';</script>";
    }
}
?>