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
    include('../../connection.php');

    $uzivatel_id = (int) $_POST['id_leader'];
    $leadername = $_POST['leadername'];
    $password = $_POST['password'];
    $active = (int) $_POST['active'];
    $comment_leader = $_POST['comment_leader'];

    $stmt = $conn->prepare("UPDATE login2 SET leadername = ?, password = ?, active = ?, comment_leader = ? WHERE id_leader = ?");
    $stmt->bind_param("ssisi", $leadername, $password, $active, $comment_leader, $uzivatel_id);

    if ($stmt->execute()) {
        echo "Záznam byl úspěšně aktualizován.";

    } else {
        echo "Chyba při aktualizaci: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    // Odpojení databáze
    ?>
    <meta http-equiv="refresh" content="2;url=leader_edit.php">

</body>

</html>