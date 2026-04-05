<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit 3 </title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <?php
    include('../../connection.php');

    $uzivatel_id = (int) $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $active = (int) $_POST['active'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("UPDATE login3 SET username = ?, password = ?, active = ?, comment = ? WHERE id = ?");
    $stmt->bind_param("ssisi", $username, $password, $active, $comment, $uzivatel_id);

    if ($stmt->execute()) {
        echo "Záznam byl úspěšně aktualizován.";

    } else {
        echo "Chyba při aktualizaci: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    // Odpojení databáze
    ?>
    <meta http-equiv="refresh" content="2;url=manager_edit.php">

</body>

</html>