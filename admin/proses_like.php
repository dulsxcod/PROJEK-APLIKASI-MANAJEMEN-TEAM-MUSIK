<?php
session_start();
include '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id_post = $_GET['id'];
    $user_id = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : 1;

    // Cek apakah user sudah like postingan ini
    $cek_like = mysqli_query($conn, "SELECT * FROM likes_post WHERE id_post = $id_post AND user_id = $user_id");
    
    if (mysqli_num_rows($cek_like) > 0) {
        // Jika sudah like, maka batalkan like (Unlike)
        mysqli_query($conn, "DELETE FROM likes_post WHERE id_post = $id_post AND user_id = $user_id");
    } else {
        // Jika belum, tambahkan like
        mysqli_query($conn, "INSERT INTO likes_post (id_post, user_id) VALUES ('$id_post', '$user_id')");
    }
    header("Location: index.php");
}
?>