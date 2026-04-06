<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu_setup</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>




    <H1>Export výplat</H1>
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
    <form action="event_export_salary.php" method="POST">

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
    

    $begin_event = isset($_POST['begin_event']) ? $_POST['begin_event'] : null;
    $hours = isset($_POST['hours']) ? $_POST['hours'] : null;
    $salary = isset($_POST['salary']) ? $_POST['salary'] : null;
    $id_worker = isset($_POST['id_worker']) ? $_POST['id_worker'] : null;
    $workername = isset($_POST['workername']) ? $_POST['workername'] : null;
    $birth_number = isset($_POST['birth_number']) ? $_POST['birth_number'] : null;
    $hpp = isset($_POST['hpp']) ? $_POST['hpp'] : null;
    $tax_event = isset($_POST['tax_event']) ? $_POST['tax_event'] : null;
    $start_event = isset($_POST['start_event']) ? $_POST['start_event'] : null;
    $stop_event = isset($_POST['stop_event']) ? $_POST['stop_event'] : null;
    $control = isset($_POST['control']) ? $_POST['control'] : null;
    $rok = isset($_POST['rok']) ? $_POST['rok'] : null;
    $mesic = isset($_POST['mesic']) ? $_POST['mesic'] : null;

    // Základní SQL dotaz
    $sql = "
 SELECT 
    YEAR(begin_event) AS rok,
    " . ($mesic !== "rok" ? "MONTH(begin_event) AS mesic," : "") . "
    id_worker,
    workername,
    birth_number,
    hpp,
     tax_event,
    SUM(hours) AS sum_hours,
    SUM(salary) AS sum_salary
 FROM event
 WHERE start_event IS NOT NULL
   AND stop_event IS NOT NULL
   AND start_event > '1970-01-01'
   AND stop_event > '1970-01-01'
   AND control = 'Ok'
";


    $params = [];
    $types = "";

    // rok
    if (!empty($rok)) {
        $sql .= " AND YEAR(begin_event) = ?";
        $params[] = $rok;
        $types .= "i";
    }

    // měsíc
    if (!empty($mesic) && $mesic !== "rok") {
        $sql .= " AND MONTH(begin_event) = ?";
        $params[] = $mesic;
        $types .= "i";
    }

    $sql .= "
     GROUP BY id_worker, rok
    ";

    if ($mesic !== "rok") {
        $sql .= ", mesic";
    }

    $sql .= "
    ORDER BY rok
    ";

    if ($mesic !== "rok") {
        $sql .= ", mesic";
    }

    $sql .= ", id_worker";



    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("<b>SQL chyba:</b> " . $conn->error . "<br><br><b>Dotaz:</b><br>" . nl2br($sql));
    }



    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();



    // Výpis výsledků
// HTML tabulka
    if ($result->num_rows > 0) { ?>
        <form action="event_export_salary2.php" method="POST">
            <input type="hidden" name="rok" value="<?php echo $rok; ?>">
            <input type="hidden" name="mesic" value="<?php echo $mesic; ?>">
            <button type="submit">Export .CSV výplaty</button>
            <br><br>
        </form>

        <?php
        echo "<table border='1'>";

        echo "<thead> <tr>
        <th>Období</th>
        <th>ID pracovníka</th>
        <th>Název pracovníka</th>
        <th>Rodné číslo pracovníka</th>
        <th>Hpp</th>
        <th>Daň</th>
        <th>Celkový počet hodin</th>
        <th>Celkový výdělek s daní</th>
       </tr> </thead>";
        echo "<td></td>";
        echo "<tbody>";

        // Zobrazení dat
        while ($row = $result->fetch_assoc()) {
            echo "<td>";
            if ($mesic === "rok") {
                echo htmlspecialchars($row['rok']);
            } else {
                echo htmlspecialchars($row['rok']) . "-" . htmlspecialchars($row['mesic']);
            }
            echo "</td>";
            echo "<td>" . htmlspecialchars($row['id_worker']) . "</td>";
            echo "<td>" . htmlspecialchars($row['workername']) . "</td>";
            echo "<td>" . htmlspecialchars($row['birth_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['hpp']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tax_event']) . "</td>";
            echo "<td>" . htmlspecialchars($row['sum_hours']) . "</td>";
            echo "<td>" . htmlspecialchars($row['sum_salary']) . "</td>";
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