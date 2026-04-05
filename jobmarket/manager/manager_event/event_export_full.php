<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu_setup</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>




    <H1>Plný export dat</H1>
    <!-- toto je menu správce-->
    <header>
        <div class="container">

            <nav>
                <ul>
                    <a href="event_export.php">
                        <li>Zpět</li>
                    </a>


                </ul>
            </nav>
        </div>
    </header>

    <hr />

    <form action="event_export_full.php" method="POST">

        <div style="display:flex; gap:10px; align-items:center;">

            <input type="number" name="rok" value="2026" min="2000" max="2100"
                style="width:80px; padding:6px 8px; font-size:15px;">

            <select name="mesic" style="padding:6px 10px; font-size:15px;">
                <option value="rok">Celý rok</option>
                <option value="1">Leden</option>
                <option value="2">Únor</option>
                <option value="3">Březen</option>
                <option value="4">Duben</option>
                <option value="5">Květen</option>
                <option value="6">Červen</option>
                <option value="7">Červenec</option>
                <option value="8">Srpen</option>
                <option value="9">Září</option>
                <option value="10">Říjen</option>
                <option value="11">Listopad</option>
                <option value="12">Prosinec</option>
            </select>
            <input type="submit" value="Vyber období" style="padding:6px 10px; font-size:15px;">
        </div>
    </form>


    <br>
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
    $id_worker = isset($_POST['id_worker']) ? $_POST['id_worker'] : null;
    $username = isset($_POST['workername']) ? $_POST['workername'] : null;
    $birth_number = isset($_POST['birth_number']) ? $_POST['birth_number'] : null;
    $hpp = isset($_POST['hpp']) ? $_POST['hpp'] : null;
    $tax_event = isset($_POST['tax_event']) ? $_POST['tax_event'] : null;
    $start_event = isset($_POST['start_event']) ? $_POST['start_event'] : null;
    $stop_event = isset($_POST['stop_event']) ? $_POST['stop_event'] : null;
    $control = isset($_POST['control']) ? $_POST['control'] : null;
    $comment_job = isset($_POST['comment_job']) ? $_POST['comment_job'] : null;
    $comment_leader = isset($_POST['comment_leader']) ? $_POST['comment_leader'] : null;
    $comment_event = isset($_POST['comment_event']) ? $_POST['comment_event'] : null;
    $comment_control = isset($_POST['comment_control']) ? $_POST['comment_control'] : null;
    $leadername = isset($_POST['leadername']) ? $_POST['leadername'] : null;
    $rok = isset($_POST['rok']) ? $_POST['rok'] : null;
    $mesic = isset($_POST['mesic']) ? $_POST['mesic'] : null;


    // Základní SQL dotaz
    $sql = "SELECT * FROM event where 1=1 ";
    $params = [];
    $types = "";

    // Dynamické přidávání podmínek
    
    // Filtrování podle roku a měsíce
    if (!empty($rok) && !empty($mesic)) {

        if ($mesic === "rok") {
            // Celý rok
            $sql .= " AND begin_event LIKE ?";
            $params[] = $rok . "%";
            $types .= "s";

        } else {
            // Konkrétní měsíc
            $mesic = str_pad($mesic, 2, "0", STR_PAD_LEFT); // 1 → 01
            $sql .= " AND begin_event LIKE ?";
            $params[] = $rok . "-" . $mesic . "%";
            $types .= "s";
        }
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
    if ($result->num_rows > 0) { ?>
        <form action="event_export_full2.php" method="POST">
            <input type="hidden" name="rok" value="<?php echo $rok; ?>">
            <input type="hidden" name="mesic" value="<?php echo $mesic; ?>">
            <button type="submit">Export .CSV plné</button>
            <br><br>
        </form>


        <?php

        echo "<table border='1'>";

        echo "<thead> <tr> <th>ID zakázky</th> <th>ID práce</th> <th>Název práce</th> <th>Hodinová sazba</th> <th>Předpokládaný začátek</th> <th>Počet hodin</th> <th>Výdělek</th> <th>ID pracovníka</th> <th>Název pracovníka</th> <th>Rodné číslo pracovníka</th> <th>Hpp</th><th>Daň</th><th>Start</th> <th>Stop</th> <th>Kontrola</th> </tr> </thead>";
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
            echo "<td>" . htmlspecialchars($row['workername']) . "</td>";
            echo "<td>" . htmlspecialchars($row['birth_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['hpp']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tax_event']) . "</td>";
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