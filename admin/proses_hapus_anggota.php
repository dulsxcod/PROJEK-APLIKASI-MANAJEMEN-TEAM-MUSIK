<?php
include '../config/koneksi.php';
global $conn;

// Menangkap ID Anggota yang akan dihapus dari URL
if (isset($_GET['id'])) {
    // Mengamankan ID inputan untuk mencegah SQL Injection
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 1. Validasi opsional: Cek apakah data anggota memang ada di database sebelum dihapus
    $cek_anggota = mysqli_query($conn, "SELECT * FROM anggota WHERE UserID = '$id'");
    $data = mysqli_fetch_assoc($cek_anggota);

    if (!$data) {
        // Jika data tidak ditemukan, kembalikan ke halaman daftar anggota
        echo "<script>
                alert('Data anggota tidak ditemukan!'); 
                window.location = 'anggota.php';
              </script>";
        exit;
    }

    // 2. Mencegah Admin tidak sengaja menghapus akun dirinya sendiri (Proteksi Keamanan)
    // Catatan: Jika Anda menggunakan session login (misal: $_SESSION['UserID']), Anda bisa mengaktifkan proteksi ini
    
    session_start();
    if ($id == $_SESSION['UserID']) {
        echo "<script>
                alert('Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif!'); 
                window.location = 'anggota.php';
              </script>";
        exit;
    }
    

    // 3. Jalankan Query DELETE untuk menghapus data berdasarkan UserID
    $query_hapus = mysqli_query($conn, "DELETE FROM anggota WHERE UserID = '$id'");

    if ($query_hapus) {
        // Jika berhasil dihapus, tampilkan pesan sukses dan kembali ke halaman utama anggota
        echo "<script>
                alert('Data anggota " . htmlspecialchars($data['NamaLengkap']) . " berhasil dihapus!'); 
                window.location = 'anggota.php';
              </script>";
        exit;
    } else {
        // Jika gagal karena relasi database atau error lainnya, tampilkan error
        echo "<script>
                alert('Gagal menghapus data: " . mysqli_error($conn) . "'); 
                window.location = 'anggota.php';
              </script>";
        exit;
    }

} else {
    // Jika file ini diakses langsung tanpa adanya parameter ID di URL, lempar kembali ke halaman utama anggota
    header("Location: anggota.php");
    exit;
}
?>