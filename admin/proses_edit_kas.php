<?php
include '../config/koneksi.php';
global $conn;

// Pastikan proses ini berjalan karena tombol "Simpan Perubahan" diklik
if (isset($_POST['update'])) {
    
    // Amankan data inputan dari karakter aneh / SQL Injection
    $KasID          = mysqli_real_escape_string($conn, $_POST['KasID']);
    $Tanggal  = mysqli_real_escape_string($conn, $_POST['Tanggal']);
    $Pemasukan        = mysqli_real_escape_string($conn, $_POST['Pemasukan']);
    $Pengeluaran         = mysqli_real_escape_string($conn, $_POST['Pengeluaran']);
    $Keterangan      = mysqli_real_escape_string($conn, $_POST['Keterangan']);
    
    // Query UPDATE untuk memperbarui data job berdasarkan JobID
    $query_update = "UPDATE kas SET 
                        
                        Tanggal = '$Tanggal',
                        Pemasukan = '$Pemasukan',
                        Pengeluaran = '$Pengeluaran',
                        Keterangan = '$Keterangan'
                     WHERE KasID = '$KasID'";

    // Eksekusi query ke database
    $eksekusi = mysqli_query($conn, $query_update);

    // Cek apakah pembaruan data berhasil atau gagal
    if ($eksekusi) {
        echo "<script>
                alert('Jadwal Kas berhasil diperbarui!');
                window.location = 'kas.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Gagal memperbarui data kas: " . mysqli_error($conn) . "');
                window.history.back();
              </script>";
        exit;
    }

} else {
    // Jika mencoba mengakses langsung file ini tanpa melalui form, kembalikan ke halaman job
    header("Location: kas.php");
    exit;
}
?>