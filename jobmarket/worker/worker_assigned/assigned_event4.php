<?php
session_start();
include('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_event = (int) ($_POST['id_event'] ?? 0);
    $comment_event = trim($_POST['comment_event'] ?? '');
    // pokud nic nevložím, text se nepřepíše
    if ($id_event > 0 && $comment_event !== '') {

        $sql = "UPDATE event 
                SET comment_event = ?
                WHERE id_event = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $comment_event, $id_event);
        $stmt->execute();
    }
}

header("Location: assigned_event2.php");
exit;
