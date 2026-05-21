<?php
session_start();
include '../config/koneksi.php';
global $conn;

// Ambil UserID dari session aktif
$id_login = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : 0;

$sql_chat = "SELECT chat_group.*, anggota.NamaLengkap, anggota.Bagian 
             FROM chat_group 
             JOIN anggota ON chat_group.UserID = anggota.UserID 
             ORDER BY chat_group.tanggal_kirim ASC";
$res_chat = mysqli_query($conn, $sql_chat);

if(mysqli_num_rows($res_chat) > 0) {
    while ($chat = mysqli_fetch_assoc($res_chat)) {
        $is_me = ($chat['UserID'] == $id_login);
        $bubble_class = $is_me ? 'msg-sent' : 'msg-received';
        $name_color = $is_me ? 'var(--tertiary-color)' : 'var(--accent-color)';
        ?>
        <div class="msg-bubble <?= $bubble_class; ?>">
            <div class="fw-bold text-truncate mb-1" style="font-size: 11px; color: <?= $name_color; ?>; letter-spacing: 0.03em;">
                <?= htmlspecialchars($chat['NamaLengkap']); ?> 
                <span class="fw-normal text-white opacity-50" style="font-size: 10px;">
                    (<?= htmlspecialchars($chat['Bagian']); ?>)
                </span>
            </div>
            <div><?= nl2br(htmlspecialchars($chat['pesan'])); ?></div>
            <div class="text-end opacity-50 mt-1" style="font-size: 9px;">
                <?= date('H:i', strtotime($chat['tanggal_kirim'])); ?>
            </div>
        </div>
        <?php 
    }
} else {
    echo '<div class="text-center text-white opacity-25 my-auto">Belum ada obrolan. Mulai percakapan pertama!</div>';
}
?>