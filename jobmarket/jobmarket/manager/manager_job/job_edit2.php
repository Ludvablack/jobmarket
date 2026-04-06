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
    $sql = "SELECT * FROM job WHERE id_job = ?";
    $stmt = $conn->prepare($sql);
    // Přiřazení ID z SESSION do dotazu
    $uzivatel_id = $_SESSION['uzivatel_id'];

    $stmt->bind_param("i", $uzivatel_id);
    $stmt->execute();
    $result = $stmt->get_result();
    // Generování tabulky 
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Název práce</th><th>Hodinová sazba</th><th>Comment</th></tr>";
        echo "<td></td>";
        ?>
        <br><br>
        <?php
        while ($row = $result->fetch_assoc()) {
            $name_job = $row['name_job'];
            $price_hour = $row['price_hour'];
            $comment_job = $row['comment_job'];

            echo "<tr>";

            echo "<td>" . htmlspecialchars($row['id_job']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name_job']) . "</td>";
            echo "<td>" . htmlspecialchars($row['price_hour']) . "</td>";
            echo "<td>" . htmlspecialchars($row['comment_job']) . "</td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nebyl vybrán žádný řádek.";
    }







    ?>
    <br><br>
    <form action="job_edit3.php" method="POST">

        <input type="hidden" name="id_job" value="<?php echo $uzivatel_id; ?>">


        <label for="name_job">Název práce:</label>
        <input type="text" name="name_job" value="<?php echo htmlspecialchars($name_job); ?>">



        <label for="price_hour">Hodinová sazba:</label>
        <input type="number" name="price_hour" value="<?php echo htmlspecialchars($price_hour); ?>">
        <br><br>
        <label for="comment_job">Comment:</label>
        <input type="text" name="comment_job" size="100" value="<?php echo htmlspecialchars($comment_job); ?>">

        <br><br>

        <input type="submit" value="Uložit změny">


    </form>








</body>

</html>