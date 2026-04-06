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

    $uzivatel_id = (int) $_POST['id_job'];
    $name_job = $_POST['name_job'];
    $price_hour = (int) $_POST['price_hour'];
    $comment_job = $_POST['comment_job'];

    $stmt = $conn->prepare("UPDATE job SET name_job = ?, price_hour = ?, comment_job = ? WHERE id_job = ?");
    $stmt->bind_param("sisi", $name_job, $price_hour, $comment_job, $uzivatel_id);

    if ($stmt->execute()) {
        echo "Záznam byl úspěšně aktualizován.";

    } else {
        echo "Chyba při aktualizaci: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    // Odpojení databáze
    ?>
    <meta http-equiv="refresh" content="2;url=job_edit.php">

</body>

</html>