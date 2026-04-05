<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view</title>
    <link rel="stylesheet" href="../../CSS/style.css">

</head>

<body>


    <header>
        <?php
        include('job3.php');
        // nacteni zakladniho menu
        ?>
    </header>
    <h1>Třídit práci podle:</h1>
    <form action="job_view.php" method="POST">
        <label for="id_job">ID:</label>
        <input type="number" id="id_job" name="id_job" placeholder="Zadejte ID">

        <label for="name_job">Název práce:</label>
        <input type="text" id="name_job" name="name_job" placeholder="Zadejte název práce">

        <label for="price_hour">Nejnižší hodinová sazba:</label>
        <input type="number" id="price_hour" name="price_hour" placeholder="Zadejte nejnižší hodinovou sazbu">
        <br><br>
        <input type="submit" value="vyhledat">
        <br><br>
    </form>
    <?php
    // Připojení k databázi
    include('../../connection.php'); // Vložení souboru s připojením k databázi
    
    // Kontrola připojení
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Získání hodnot z formuláře
    $id = isset($_POST['id_job']) ? $_POST['id_job'] : null;
    $name_job = isset($_POST['name_job']) ? $_POST['name_job'] : null;
    $price_hour = isset($_POST['price_hour']) ? $_POST['price_hour'] : null;
    $comment_job = isset($_POST['comment_job']) ? $_POST['comment_job'] : null;
    // Základní SQL dotaz
    $sql = "SELECT * FROM job where 1=1 ";
    $params = [];
    $types = "";

    // Dynamické přidávání podmínek
    if (!empty($id)) {
        $sql .= " AND id_job = ?";
        $params[] = $id;
        $types .= "i"; // Typ integer
    }

    if (!empty($name_job)) {
        $sql .= " AND name_job LIKE ?";
        $params[] = "%" . $name_job . "%";
        $types .= "s"; // Typ string
    }



    if ($price_hour !== null && $price_hour !== "") {
        $sql .= " AND price_hour >= ?";
        $params[] = $price_hour;
        $types .= "i"; // Typ integer
    }

    if (!empty($comment_job)) {
        $sql .= " AND comment_job LIKE ?";
        $params[] = "%" . $comment_job . "%";
        $types .= "s"; // Typ sring
    }



    $sql .= " ORDER BY id_job ASC ";






    // Připravení dotazu
    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    // Spuštění dotazu
//Hodnoty se připojují pomocí připraveného dotazu (Prepared Statements), což chrání před SQL injection útoky.
    $stmt->execute();
    $result = $stmt->get_result();

    // Výpis výsledků
// HTML tabulka
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tbody><thread><tr><th>ID</th><th>Název práce</th><th>Hodinová sazba</th><th>Comment</th></tr></thread></tbody>";
        echo "<td></td>";
        // Zobrazení dat
        while ($row = $result->fetch_assoc()) {

            echo "<tr>";

            echo "<td>" . htmlspecialchars($row['id_job']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name_job']) . "</td>";
            echo "<td>" . htmlspecialchars($row['price_hour']) . "</td>";
            echo "<td>" . htmlspecialchars($row['comment_job']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Žádná data k zobrazení.";
    }

    // Uzavření spojení
    $conn->close();
    ?>






</body>

</html>