<?php
session_start();

$id_worker = $_SESSION['id_worker'] ?? null;
$workername = $_SESSION['workername'] ?? null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přehled zakázek</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <h1>Přehled ukončených zakázek </h1>
    <p>Zakázky pracovníka: <strong>
            <?php echo $_SESSION['workername']; ?>
        </strong></p>


    <!-- toto je menu správce-->
    <header>
        <div class="container">

            <nav>
                <ul>
                    <a href="statue_menu.php">
                        <li>Zpět</li>
                    </a>




                </ul>
            </nav>
        </div>
    </header>

    <hr />



    <h1>Třídit zakázky podle:</h1>
    <form action="statue_finish.php" method="POST">
        <label for="id_event">ID zakázka:</label>
        <input type="number" id="id_event" name="id_event" placeholder="Zadejte ID zakázky">
        <label for="id_job">ID práce:</label>
        <input type="number" id="id_job" name="id_job" placeholder="Zadejte ID práce">
        <label for="name_job">Název zakázky:</label>
        <input type="text" id="name_job" name="name_job" placeholder="Zadejte název práce">
        <br><br>
        <label for="price_hour">Nejnižší hodinová sazba:</label>
        <input type="number" id="price_hour" name="price_hour" placeholder="Zadejte nejnižší hodinovou sazbu">
        <label for="begin_event">Zakázky od:</label>
        <input type="date" id="begin_event" name="begin_event" placeholder="Zadejte předpokládaný začátek práce">
        <label for="hours">Počet hodin max:</label>
        <input type="number" id="hours" name="hours" placeholder="Zadejte počet hodin max">
        <label for="salary">Minimální plat:</label>
        <input type="number" id="salary" name="salary" placeholder="Zadejte minimální plat">
        <br><br>

        <select type="text" id="control" name="control">
            <option value="">Vše</option>
            <option value="Ready">Připraveno-Ready</option>
            <option value="Ok">Schváleno-Ok</option>
            <option value="False">Odmítnuto-False</option>
            <option value="Storno">Storno</option>

        </select>
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
    $id = isset($_POST['id_event']) ? $_POST['id_event'] : null;
    $id_job = isset($_POST['id_job']) ? $_POST['id_job'] : null;
    $name_job = isset($_POST['name_job']) ? $_POST['name_job'] : null;
    $price_hour = isset($_POST['price_hour']) ? $_POST['price_hour'] : null;
    $begin_event = isset($_POST['begin_event']) ? $_POST['begin_event'] : null;
    $hours = isset($_POST['hours']) ? $_POST['hours'] : null;
    $salary = isset($_POST['salary']) ? $_POST['salary'] : null;
    $start_event = isset($_POST['start_event']) ? $_POST['start_event'] : null;
    $stop_event = isset($_POST['stop_event']) ? $_POST['stop_event'] : null;
    $control = isset($_POST['control']) ? $_POST['control'] : null;
    $comment_job = isset($_POST['comment_job']) ? $_POST['comment_job'] : null;
    $comment_leader = isset($_POST['comment_leader']) ? $_POST['comment_leader'] : null;
    $comment_event = isset($_POST['comment_event']) ? $_POST['comment_event'] : null;
    $comment_control = isset($_POST['comment_control']) ? $_POST['comment_control'] : null;
    $leadername = isset($_POST['leadername']) ? $_POST['leadername'] : null;



    // Základní SQL dotaz
    $sql = "SELECT * FROM event 
        WHERE id_worker = ?
          AND start_event IS NOT NULL
          AND start_event > '1970-01-01'
          AND stop_event IS NOT NULL
          AND stop_event > '1970-01-01'
          AND control NOT IN ('False', 'Storno', 'Empty')
          ";

    $params = [];
    $types = "";

    $params[] = $id_worker;  // ← MUSÍ TAM BÝT
    $types .= "i";

    // Dynamické přidávání podmínek
    if (!empty($id)) {
        $sql .= " AND id_event = ?";
        $params[] = $id;
        $types .= "i"; // Typ integer
    }
    if ($id_job !== null && $id_job !== "") {
        $sql .= " AND id_job = ?";
        $params[] = $id_job;
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

    if (!empty($begin_event)) {
        $sql .= " AND begin_event > ?";
        $params[] = $begin_event;
        $types .= "s"; // Typ string
    }
    if ($hours !== null && $hours !== "") {
        $sql .= " AND hours <= ?";
        $params[] = $hours;
        $types .= "i"; // Typ integer
    }

    if ($salary !== null && $salary !== "") {
        $sql .= " AND salary >= ?";
        $params[] = $salary;
        $types .= "i"; // Typ integer
    }

    if (!empty($control)) {
        $sql .= " AND control LIKE ?";
        $params[] = "%" . $control . "%";
        $types .= "s"; // Typ string
    }

    $sql .= " ORDER BY id_event ASC ";

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
        echo "<thead> <tr> <th>ID zakázky</th> <th>ID práce</th> <th>Název práce</th> <th>Hodinová sazba</th> <th>Předpokládaný začátek</th> <th>Počet hodin</th> <th>Výdělek včetně daně</th> <th>ID pracovníka</th>    <th>Start</th> <th>Stop</th> <th>Kontrola</th> </tr> </thead>";
        echo "<tbody>";
        echo "<td></td>";
        // Zobrazení dat
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";

            echo "<td>" . htmlspecialchars($row['id_event']) . "</td>";
            echo "<td>" . htmlspecialchars($row['id_job']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name_job']) . "</td>";
            echo "<td>" . htmlspecialchars($row['price_hour']) . "</td>";
            echo "<td>" . htmlspecialchars($row['begin_event']) . "</td>";
            echo "<td>" . htmlspecialchars($row['hours']) . "</td>";
            echo "<td>" . htmlspecialchars($row['salary']) . "</td>";
            echo "<td>" . htmlspecialchars($row['id_worker']) . "</td>";
            echo "<td>" . htmlspecialchars($row['start_event']) . "</td>";
            echo "<td>" . htmlspecialchars($row['stop_event']) . "</td>";
            echo "<td>" . htmlspecialchars($row['control']) . "</td>";
            echo "</tr>";

            // samostatný řádek s komentářem 
            echo "<tr>";
            echo "<td colspan='15' style='padding: 6px 4px; background: #f7f7f7; text-align: left;'>";

            echo "<details>";
            echo "<summary style='cursor:pointer; font-weight:bold;'>Zobrazit komentáře</summary>";

            echo "<div style='padding: 8px 0 0 0;'>";
            echo "<p><strong>Comment-job:</strong> " . htmlspecialchars($row['comment_job']) . "</p>";
            echo "<p><strong>Comment-leader:</strong> " . htmlspecialchars($row['comment_leader']) . "</p>";
            echo "<p><strong>Comment-worker:</strong> " . htmlspecialchars($row['comment_event']) . "</p>";
            echo "<p><strong>Comment-control:</strong> " . htmlspecialchars($row['comment_control']) . "</p>";
            echo "<p><strong>Kontroloval:</strong> " . htmlspecialchars($row['leadername']) . "</p>";
            echo "</div>";

            echo "</details>";

            echo "</td>";
            echo "</tr>";

        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "Žádná data k zobrazení.";
    }

    // Uzavření spojení
    $conn->close();
    ?>

</body>

</html>