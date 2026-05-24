<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['submit_komentar'])) {
    $id_post = $_POST['id_post'];
    $user_id = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : 1;
    $isi_komentar = mysqli_real_escape_string($conn, $_POST['isi_komentar']);

    $query = "INSERT INTO komentar_post (id_post, user_id, isi_komentar) VALUES ('$id_post', '$user_id', '$isi_komentar')";
    
    mysqli_query($conn, $query);
    header("Location: index.php");
}
?>