<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit 3</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>

    <?php
    include('../../connection.php');

    // Načtení hodnot z formuláře
    $hours = isset($_POST['hours']) ? (int) $_POST['hours'] : null;
    $limitsalary = isset($_POST['limitsalary']) ? (int) $_POST['limitsalary'] : null;
    $tax = isset($_POST['tax']) ? (int) $_POST['tax'] : null;

    // UPDATE pouze řádku id = 1
    $stmt = $conn->prepare("UPDATE setup SET hours = ?, limitsalary = ?, tax = ? WHERE id = 1");
    $stmt->bind_param("iii", $hours, $limitsalary, $tax);

    if ($stmt->execute()) {
        echo "Záznam byl úspěšně aktualizován.";
    } else {
        echo "Chyba při aktualizaci: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    ?>

    <meta http-equiv="refresh" content="2;url=../menu3.php">

</body>

</html>