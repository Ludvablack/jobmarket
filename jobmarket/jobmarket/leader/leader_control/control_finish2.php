<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit 2</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <?php
    // Připojení k databázi
    include('../../connection.php'); // Vložení souboru s připojením k databázi
    session_start();
    // Správné nastavení SQL dotazu
    $sql = "SELECT * FROM event WHERE id_event = ?";
    $stmt = $conn->prepare($sql);
    // Přiřazení ID z SESSION do dotazu
    $uzivatel_id = $_SESSION['uzivatel_id'];

    $stmt->bind_param("i", $uzivatel_id);
    $stmt->execute();
    $result = $stmt->get_result();
    // Generování tabulky 
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID zakázka</th><th>ID práce</th><th>Název zakázky</th><th>Hodinová sazba</th><th>Začátek práce</th><th>Počet hodin</th><th>Plat</th><th>ID pracovníka</th><th>Jméno pracovníka</th><th>Rodné číslo pracovníka</th><th>Hpp</th><th>Daň</th>  <th>Start práce</th><th>Konec práce</th><th>Kontrola</th></tr>";
        echo "<td></td>";
        ?>
        <br><br>
        <?php
        while ($row = $result->fetch_assoc()) {
            $id = $row['id_event'];
            $id_job = $row['id_job'];
            $name_job = $row['name_job'];
            $price_hour = $row['price_hour'];
            $begin_event = $row['begin_event'];
            $hours = $row['hours'];
            $salary = $row['salary'];
            $id_worker = $row['id_worker'];
            $workername = $row['workername'];
            $birth_number = $row['birth_number'];
            $hpp = $row['hpp'];
            $tax_event = $row['tax_event'];
            $start_event = $row['start_event'];
            $stop_event = $row['stop_event'];
            $control = $row['control'];
            $comment_control = $row['comment_control'];

            echo "<tr>";

            echo "<td>" . htmlspecialchars($row['id_event']) . "</td>";
            echo "<td>" . htmlspecialchars($row['id_job']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name_job']) . "</td>";
            echo "<td>" . htmlspecialchars($row['price_hour']) . "</td>";
            echo "<td>" . htmlspecialchars($row['begin_event']) . "</td>";
            echo "<td>" . htmlspecialchars($row['hours']) . "</td>";
            echo "<td>" . htmlspecialchars($row['salary']) . "</td>";
            echo "<td>" . htmlspecialchars($row['id_worker']) . "</td>";
            echo "<td>" . htmlspecialchars($row['workername']) . "</td>";
            echo "<td>" . htmlspecialchars($row['birth_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['hpp']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tax_event']) . "</td>";
            echo "<td>" . htmlspecialchars($row['start_event']) . "</td>";
            echo "<td>" . htmlspecialchars($row['stop_event']) . "</td>";
            echo "<td>" . htmlspecialchars($row['control']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nebyl vybrán žádný řádek.";
    }







    ?>
    <br><br>

    <form action="control_finish3.php" method="POST">
        <input type="hidden" name="id_event" value="<?php echo $id; ?>">

        <label for="control">Kontrola:</label>
        <select id="control" name="control">
            <option value="Storno">Storno</option>
            <option value="Ok">Schváleno-Ok</option>
            <option value="False">Odmítnuto-False</option>
        </select>
        <br><br>
        <label for="comment_control">Comment kontroly:</label>
        <input name="comment_control" type="text" size="100" placeholder=" Komentář">
        <br><br>
        <input type="submit" value="Zhodnotit">
    </form>








</body>

</html>