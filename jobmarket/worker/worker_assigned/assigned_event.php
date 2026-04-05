<?php
session_start();

$id_worker = $_SESSION['id_worker'];
$workername = $_SESSION['workername'] ?? null;
$uzivatel_id = $_SESSION['uzivatel_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přehled připravených zakázek</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>

    <h1>Připravené, probíhající zakázky</h1>
    <p>Připravené zakázky pracovníka: <strong>
            <?php echo htmlspecialchars($workername); ?>
        </strong></p>

    <header>
        <div class="container">
            <nav>
                <ul>
                    <a href="../menu1.php">
                        <li>Zpět</li>
                    </a>
                </ul>
            </nav>
        </div>
    </header>

    <hr>

    <h1>Třídit zakázky podle:</h1>

    <form action="assigned_event.php" method="POST">
        <label>ID zakázky:</label>
        <input type="number" name="id_event">

        <label>ID práce:</label>
        <input type="number" name="id_job">

        <label>Název zakázky:</label>
        <input type="text" name="name_job">

        <br><br>

        <label>Nejnižší hodinová sazba:</label>
        <input type="number" name="price_hour">

        <label>Zakázky od:</label>
        <input type="date" name="begin_event">

        <label>Počet hodin max:</label>
        <input type="number" name="hours">

        <label>Minimální plat:</label>
        <input type="number" name="salary">


        <input type="submit" value="Vyhledat">

    </form>

    <?php
    include('../../connection.php');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Načtení filtrů
    $id = $_POST['id_event'] ?? null;
    $id_job = $_POST['id_job'] ?? null;
    $name_job = $_POST['name_job'] ?? null;
    $price_hour = $_POST['price_hour'] ?? null;
    $begin_event = $_POST['begin_event'] ?? null;
    $hours = $_POST['hours'] ?? null;
    $salary = $_POST['salary'] ?? null;

    // Základní SQL
    $sql = "SELECT * FROM event 
        WHERE id_worker = ?
        
        AND stop_event IS NULL
        AND control='Ready'";

    $params = [];
    $types = "";

    $params[] = $id_worker;
    $types .= "i";


    // Dynamické filtry
    if (!empty($id)) {
        $sql .= " AND id_event = ?";
        $params[] = $id;
        $types .= "i";
    }
    if (!empty($id_job)) {
        $sql .= " AND id_job = ?";
        $params[] = $id_job;
        $types .= "i";
    }
    if (!empty($name_job)) {
        $sql .= " AND name_job LIKE ?";
        $params[] = "%$name_job%";
        $types .= "s";
    }
    if (!empty($price_hour)) {
        $sql .= " AND price_hour >= ?";
        $params[] = $price_hour;
        $types .= "i";
    }
    if (!empty($begin_event)) {
        $sql .= " AND begin_event > ?";
        $params[] = $begin_event;
        $types .= "s";
    }
    if (!empty($hours)) {
        $sql .= " AND hours <= ?";
        $params[] = $hours;
        $types .= "i";
    }
    if (!empty($salary)) {
        $sql .= " AND salary >= ?";
        $params[] = $salary;
        $types .= "i";
    }

    $sql .= " ORDER BY id_event ASC";

    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Pokud jsou výsledky, zobrazíme tabulku
    if ($result->num_rows > 0) {

        // FORMULÁŘ OBALUJE CELOU TABULKU
        echo '<form action="assigned_event.php" method="POST">';
        echo "<br><button type='submit'>Vybrat zakázku</button>";
        echo '<br></br>';
        echo '<table border="1">';
        echo '<thead><tr>
            <th>Vyber</th>
            <th>ID zakázky</th>
            <th>ID práce</th>
            <th>Název práce</th>
            <th>Hodinová sazba</th>
            <th>Předpokládaný začátek</th>
            <th>Počet hodin</th>
            <th>Výdělek včetně daně</th>
            <th>ID pracovníka</th>
            <th>Start</th>
           
            
          </tr></thead>';
        echo '<tbody>';

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
            echo "<td>" . htmlspecialchars($row['start_event']) . "</td>";


            echo "</tr>";

            echo "<tr>";
            echo "<td colspan='15' style='padding: 6px 4px; background: #f7f7f7; text-align: left;'>";

            echo "<details>";
            echo "<summary style='cursor:pointer; font-weight:bold;'>Zobrazit komentáře</summary>";

            echo "<div style='padding: 8px 0 0 0;'>";
            echo "<p><strong>Comment-job:</strong> " . htmlspecialchars($row['comment_job']) . "</p>";
            echo "<p><strong>Comment-leader:</strong> " . htmlspecialchars($row['comment_leader']) . "</p>";
            echo "<p><strong>Comment-worker:</strong> " . htmlspecialchars($row['comment_event']) . "</p>";
            echo "</div>";

            echo "</details>";

            echo "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";


        echo "</form>";

    } else {
        echo "Žádná data k zobrazení.";
    }

    // Zpracování výběru
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uzivatel_id'])) {

        $_SESSION['uzivatel_id'] = (int) $_POST['uzivatel_id'];

        echo "Byla vybrána zakázka ID: " . $_SESSION['uzivatel_id'];

        echo '<meta http-equiv="refresh" content="2;url=assigned_event2.php">';
    }

    $conn->close();
    ?>

</body>

</html>