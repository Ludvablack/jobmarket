<?php
session_start();

$start_event = $_SESSION['start_event'] ?? null;
$uzivatel_id = $_SESSION['uzivatel_id'] ?? null;
$stop_event = $_POST['stop_event'] ?? '';

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přidělení zakázky </title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <?php

    include('../../connection.php');



    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id_event = (int) ($_POST['id_event'] ?? 0);

        if ($id_event > 0) {

            // Uložení reálného času startu
            $sql = "UPDATE event SET stop_event = NOW() WHERE id_event = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_event);
            $stmt->execute();
        }
    }


    // Návrat zpět na detail zakázky a zpoždění 5 sekund
    echo "<h2 style='color:red;'>Zakázka byla ukončena.</h2>";
    header("Refresh: 3; url=assigned_event.php");


    // Odpojení databáze
    ?>


</body>

</html>