<?php
session_start();
header('Content-Type: application/json');
include '../config/koneksi.php';
global $conn;

// Ambil ID aman langsung dari SESSION, bukan dari manipulasi input FORM
$id_login = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kirim_pesan'])) {
    if (!$id_login) {
        echo json_encode(['status' => 'error', 'message' => 'Sesi login habis.']);
        exit();
    }

    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);

    if (!empty(trim($pesan))) {
        $query_insert = "INSERT INTO chat_group (UserID, pesan) VALUES ('$id_login', '$pesan')";
        if (mysqli_query($conn, $query_insert)) {
            echo json_encode(['status' => 'success']);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database.']);
            exit();
        }
    }
    echo json_encode(['status' => 'error', 'message' => 'Pesan kosong.']);
    exit();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Akses ilegal.']);
    exit();
}
?>