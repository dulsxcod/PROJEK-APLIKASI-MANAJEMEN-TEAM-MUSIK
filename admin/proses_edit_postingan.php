<?php
include '../config/koneksi.php';
global $conn;

// Pastikan proses ini berjalan karena tombol "Simpan Perubahan" diklik
if (isset($_POST['update'])) {
    
    // Amankan data inputan dari karakter aneh / SQL Injection
    $id_post          = mysqli_real_escape_string($conn, $_POST['id_post']);
    $isi_postingan  = mysqli_real_escape_string($conn, $_POST['isi_postingan']);
    $file_media        = mysqli_real_escape_string($conn, $_POST['file_media']);
    
    // Query UPDATE untuk memperbarui data job berdasarkan JobID
    $query_update = "UPDATE postingan SET 
                        
                        isi_postingan = '$isi_postingan',
                        file_media = '$file_media'
                     WHERE id_post = '$id_post'";

    // Eksekusi query ke database
    $eksekusi = mysqli_query($conn, $query_update);

    // Cek apakah pembaruan data berhasil atau gagal
    if ($eksekusi) {
        echo "<script>
                alert('Data postingan berhasil diperbarui!');
                window.location = 'postingan.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Gagal memperbarui data postingan: " . mysqli_error($conn) . "');
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