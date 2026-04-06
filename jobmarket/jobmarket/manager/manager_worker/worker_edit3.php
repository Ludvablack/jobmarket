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

    $uzivatel_id = (int) $_POST['id_worker'];
    $workername = $_POST['workername'];
    $password = $_POST['password'];
    $active = (int) $_POST['active'];
    $birth_number = $_POST['birth_number'];
    $hpp = (int) $_POST['hpp'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("UPDATE login1 SET workername = ?, password = ?, active = ?, birth_number = ?,hpp = ?, comment = ? WHERE id_worker = ?");
    $stmt->bind_param("ssisisi", $workername, $password, $active, $birth_number, $hpp, $comment, $uzivatel_id);

    if ($stmt->execute()) {
        echo "Záznam byl úspěšně aktualizován.";

    } else {
        echo "Chyba při aktualizaci: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    // Odpojení databáze
    ?>
    <meta http-equiv="refresh" content="2;url=worker_edit.php">

</body>

</html>