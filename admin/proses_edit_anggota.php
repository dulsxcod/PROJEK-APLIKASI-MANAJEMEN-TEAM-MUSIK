<?php
include '../config/koneksi.php';
global $conn;

// Pastikan proses diakses melalui metode POST dan tombol submit 'update' telah ditekan
if (isset($_POST['update'])) {
    
    // Menangkap dan mengamankan data inputan dari form edit
    $UserID       = mysqli_real_escape_string($conn, $_POST['UserID']);
    $Username     = mysqli_real_escape_string($conn, $_POST['Username']);
    $Password     = $_POST['Password']; // Sengaja tidak di-escape dulu karena akan dicek kekosongannya
    $NamaLengkap  = mysqli_real_escape_string($conn, $_POST['NamaLengkap']);
    $TempatLahir  = mysqli_real_escape_string($conn, $_POST['TempatLahir']);
    $TanggalLahir = mysqli_real_escape_string($conn, $_POST['TanggalLahir']);
    $JenisKelamin = mysqli_real_escape_string($conn, $_POST['JenisKelamin']);
    $NoHP         = mysqli_real_escape_string($conn, $_POST['NoHP']);
    $Role         = mysqli_real_escape_string($conn, $_POST['Role']);
    $Bagian       = mysqli_real_escape_string($conn, $_POST['Bagian']);

    // 1. Validasi ganda untuk memastikan Username tidak bentrok/dipakai oleh anggota lain
    $cek_username = mysqli_query($conn, "SELECT * FROM anggota WHERE Username = '$Username' AND UserID != '$UserID'");
    if (mysqli_num_rows($cek_username) > 0) {
        echo "<script>
                alert('Username sudah digunakan oleh anggota lain! Silakan gunakan username lain.');
                window.history.back();
              </script>";
        exit;
    }

    // 2. Logika Pengecekan Password
    // Jika kolom password diisi, maka update password baru. Jika kosong, gunakan password yang lama (tidak diubah).
    if (!empty($Password)) {
        // Amankan password baru
        $Password_aman = mysqli_real_escape_string($conn, $Password);
        
        // CATATAN: Silakan sesuaikan enkripsi di bawah dengan skema login aplikasi Anda.
        // Jika menggunakan MD5 bawaan lama:
        $password_fix = md5($Password_aman);
        
        // Jika aplikasi Anda menggunakan standar PHP modern (password_hash), aktifkan baris di bawah ini:
        // $password_fix = password_hash($Password_aman, PASSWORD_DEFAULT);

        // Query UPDATE termasuk Password baru
        $query_update = "UPDATE anggota SET 
                            Username = '$Username',
                            Password = '$password_fix',
                            NamaLengkap = '$NamaLengkap',
                            TempatLahir = '$TempatLahir',
                            TanggalLahir = '$TanggalLahir',
                            JenisKelamin = '$JenisKelamin',
                            NoHP = '$NoHP',
                            Role = '$Role',
                            Bagian = '$Bagian'
                         WHERE UserID = '$UserID'";
    } else {
        // Query UPDATE tanpa mengubah Password lama
        $query_update = "UPDATE anggota SET 
                            Username = '$Username',
                            NamaLengkap = '$NamaLengkap',
                            TempatLahir = '$TempatLahir',
                            TanggalLahir = '$TanggalLahir',
                            JenisKelamin = '$JenisKelamin',
                            NoHP = '$NoHP',
                            Role = '$Role',
                            Bagian = '$Bagian'
                         WHERE UserID = '$UserID'";
    }

    // 3. Eksekusi Query ke Database
    $eksekusi = mysqli_query($conn, $query_update);

    if ($eksekusi) {
        // Jika berhasil, munculkan notifikasi sukses dan arahkan kembali ke halaman daftar anggota
        echo "<script>
                alert('Data anggota berhasil diperbarui!');
                window.location = 'anggota.php';
              </script>";
        exit;
    } else {
        // Jika gagal karena error database, tampilkan pesan errornya
        echo "<script>
                alert('Gagal memperbarui data: " . mysqli_error($conn) . "');
                window.history.back();
              </script>";
        exit;
    }

} else {
    // Jika file ini diakses langsung tanpa melalui form edit, kunci dan kembalikan ke halaman daftar anggota
    header("Location: anggota.php");
    exit;
}
?>