<?php
include '../config/koneksi.php';
global $conn;

// Pastikan proses ini berjalan karena tombol "Simpan Perubahan" diklik
if (isset($_POST['update'])) {
    
    // Amankan data inputan dari karakter aneh / SQL Injection
    $JobID          = mysqli_real_escape_string($conn, $_POST['JobID']);
    $NamaTuanRumah  = mysqli_real_escape_string($conn, $_POST['NamaTuanRumah']);
    $Tanggal        = mysqli_real_escape_string($conn, $_POST['Tanggal']);
    $Alamat         = mysqli_real_escape_string($conn, $_POST['Alamat']);
    $NamaGroup      = mysqli_real_escape_string($conn, $_POST['NamaGroup']);
    $Seragam        = mysqli_real_escape_string($conn, $_POST['Seragam']);

    // Query UPDATE untuk memperbarui data job berdasarkan JobID
    $query_update = "UPDATE job SET 
                        NamaTuanRumah = '$NamaTuanRumah',
                        Tanggal = '$Tanggal',
                        Alamat = '$Alamat',
                        NamaGroup = '$NamaGroup',
                        Seragam = '$Seragam'
                     WHERE JobID = '$JobID'";

    // Eksekusi query ke database
    $eksekusi = mysqli_query($conn, $query_update);

    // Cek apakah pembaruan data berhasil atau gagal
    if ($eksekusi) {
        echo "<script>
                alert('Jadwal job berhasil diperbarui!');
                window.location = 'job.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Gagal memperbarui data job: " . mysqli_error($conn) . "');
                window.history.back();
              </script>";
        exit;
    }

} else {
    // Jika mencoba mengakses langsung file ini tanpa melalui form, kembalikan ke halaman job
    header("Location: job.php");
    exit;
}
?>