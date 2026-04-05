<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ostatní zakázky</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>





    <H1>Menu ostatní zakázky</H1>

    <header>
        <div class="container">

            <nav>
                <ul>
                    <a href="control.php">
                        <li>Zpět</li>
                    </a>


                </ul>
            </nav>
        </div>
    </header>

    <hr />

    <h1>Třídit zakázky podle:</h1>
    <form action="control_other.php" method="POST">
        <label for="id_event">ID zakázka:</label>
        <input type="number" id="id_event" name="id_event" placeholder="Zadejte ID zakázky">
        <label for="id_job">ID práce:</label>
        <input type="number" id="id_job" name="id_job" placeholder="Zadejte ID práce">
        <label for="name_job">Název zakázky:</label>
        <input type="text" id="name_job" name="name_job" placeholder="Zadejte název práce">
        <br><br>
        <label for="price_hour">Nejnižší hodinová sazba:</label>
        <input type="number" id="price_hour" name="price_hour" placeholder="Zadejte nejnižší hodinovou sazbu">
        <label for="begin_event">Začátek práce:</label>
        <input type="date" id="begin_event" name="begin_event" placeholder="Zadejte předpokládaný začátek práce">
        <label for="hours">Počet hodin max:</label>
        <input type="number" id="hours" name="hours" placeholder="Zadejte počet hodin max">
        <label for="salary">Minimální plat:</label>
        <input type="number" id="salary" name="salary" placeholder="Zadejte minimální plat">
        <br><br>
        <label for="id_worker">ID pracovníka:</label>
        <input type="number" id="id_worker" name="id_worker" placeholder="Zadejte ID pracovníka">
        <label for="workername">Jméno pracovníka:</label>
        <input type="text" id="workername" name="workername" placeholder="Zadejte jméno pracovníka">
        <label for="birth_number">Rodné číslo pracovníka:</label>
        <input type="text" id="birth_number" name="birth_number" placeholder="Zadejte rodné číslo pracovníka">
        <label for="hpp">Hpp:</label>
        <select type="number" id="hpp" name="hpp">
            <option value="">Vše</option>
            <option value="1">Ano</option>
            <option value="0">Ne</option>
        </select>
        <br><br>
        <label for="start_event">Start práce:</label>
        <input type="date" id="start_event" name="start_event" placeholder="Start práce">
        <label for="stop_event">Konec práce:</label>
        <input type="date" id="stop_event" name="stop_event" placeholder="Konec práce">

        <label for="control">Kontrola:</label>
        <select type="text" id="control" name="control">
            <option value="">Vše</option>
            <option value="Ready">Připraveno-Ready</option>
            <option value="Ok">Schváleno-Ok</option>
            <option value="False">Odmítnuto-False</option>
            <option value="Storno">Storno</option>
            <option value="Empty">Prázdné-Empty</option>
        </select>
        <br><br>
        <input type="submit" value="vyhledat">
        <br><br>
    </form>
    <?php
    session_start();

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
    $workername = isset($_POST['workername']) ? $_POST['workername'] : null;
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




    // Základní SQL dotaz
    $sql = "SELECT * FROM event where 1=1 ";

    $params = [];
    $types = "";

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
        $sql .= " AND begin_event LIKE ?";
        $params[] = "%" . $begin_event . "%";
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

    if ($id_worker !== null && $id_worker !== "") {
        $sql .= " AND id_worker = ?";
        $params[] = $id_worker;
        $types .= "i"; // Typ integer
    }
    if (!empty($workername)) {
        $sql .= " AND workername LIKE ?";
        $params[] = "%" . $workername . "%";
        $types .= "s"; // Typ string
    }
    if (!empty($birth_number)) {
        $sql .= " AND birth_number LIKE ?";
        $params[] = "%" . $birth_number . "%";
        $types .= "s"; // Typ string
    }
    if ($hpp !== null && $hpp !== "") {
        $sql .= " AND hpp = ?";
        $params[] = $hpp;
        $types .= "i"; // Typ integer
    }
    if (!empty($start_event)) {
        $sql .= " AND start_event LIKE ?";
        $params[] = "%" . $start_event . "%";
        $types .= "s"; // Typ string
    }
    if (!empty($stop_event)) {
        $sql .= " AND stop_event LIKE ?";
        $params[] = "%" . $stop_event . "%";
        $types .= "s"; // Typ string
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
    // Generování tabulky s radio tlačítky
    if (!isset($_POST['uzivatel_id'])) {
        if ($result->num_rows > 0) {



            // Výpis výsledků
// HTML tabulka
            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<thead> <tr><th>Vybrat</th><th>ID zakázky</th> <th>ID práce</th> <th>Název práce</th> <th>Hodinová sazba</th> <th>Předpokládaný začátek</th> <th>Počet hodin</th> <th>Výdělek</th> <th>ID pracovníka</th> <th>Název pracovníka</th> <th>Rodné číslo pracovníka</th> <th>Hpp</th><th>Daň</th> <th>Start</th> <th>Stop</th> <th>Kontrola</th> </tr> </thead>";

                ?>
                <form action="control_other.php" method="POST">
                    <button><input type="submit" value="Upravit vybraný řádek"></button>
                    <br><br>





                    <?php

                    echo "<tbody>";
                    echo "<td></td>";
                    // Zobrazení dat
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><input type='radio' name='uzivatel_id' value='" . $row['id_event'] . "'></td>";
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
                        echo "<td colspan='16' style='padding: 6px 4px; background: #f7f7f7; text-align: left;'>";

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
        }
    } else {
        echo "Uživatel se vybírá.";
        echo "<br><br>";
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['uzivatel_id'])) {
        $vybrany_id = intval($_POST['uzivatel_id']); // Získání ID vybraného uživatele
    

        //   session_start(); // Spustíme session zablokováno kvuli session nahore
    
        if (isset($_POST['uzivatel_id'])) {
            $_SESSION['uzivatel_id'] = (int) $_POST['uzivatel_id']; // Převedeme hodnotu na číslo a uložíme do session
            echo "Byl vybrán řádek: " . $_SESSION['uzivatel_id'];
            ?>

                <meta http-equiv="refresh" content="2;url=control_other2.php">
                </meta>
                <?php

        } else {
            echo "Nebylo nic vybráno.";
        }

    } else {
        echo "Nebyl vybrán žádný řádek: " . $conn->error;
    }



    // Uzavření spojení
    $conn->close();
    ?>










</body>

</html>