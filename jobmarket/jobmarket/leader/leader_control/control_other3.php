<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit 3 </title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <?php
    session_start();
    include('../../connection.php');
    $uzivatel_id = (int) $_POST['id_event'];
    $control = $_POST['control'];
    $comment_control = $_POST['comment_control'];
    $leadername = $_SESSION['leadername'] ?? null;

    $stmt = $conn->prepare("UPDATE event SET control=?,comment_control=?,leadername=? WHERE id_event=?");
    $stmt->bind_param("sssi", $control, $comment_control, $leadername, $uzivatel_id);


    if ($stmt->execute()) {
        echo "Zakázka byla zkontrolována.";

    } else {
        echo "Chyba při aktualizaci: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    // Odpojení databáze
    ?>
    <meta http-equiv="refresh" content="2;url=control_other.php">

</body>

</html>