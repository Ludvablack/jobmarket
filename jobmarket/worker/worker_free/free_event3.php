<?php
session_start();

$id_worker = $_SESSION['id_worker'] ?? null;

if ($id_worker === null) {
    die("Chyba: chybí id_worker v session.");
}

include('../../connection.php');

$id_event = (int) $_POST['id_event'];

/* ---------------------------------------------------------
   1) Načtení údajů o pracovníkovi
--------------------------------------------------------- */
$sql = "SELECT workername, birth_number, hpp 
        FROM login1 
        WHERE id_worker = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_worker);
$stmt->execute();
$worker = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$worker) {
    die("Chyba: Pracovník nebyl nalezen.");
}

/* ---------------------------------------------------------
   2) Načtení daně ze setup
--------------------------------------------------------- */
$sql = "SELECT tax FROM setup LIMIT 1";
$res = $conn->query($sql);
$result = $conn->query($sql);
if (!$res || $res->num_rows === 0) {
    die("Chyba: Nelze načíst daň z tabulky setup.");
}

$setup = $res->fetch_assoc();
$tax = (int) $setup['tax'];

/* ---------------------------------------------------------
   3) Výpočet tax_event
--------------------------------------------------------- */
$tax_event = ($worker['hpp'] == 1) ? $tax : 0;

/* ---------------------------------------------------------
   4) UPDATE event – doplnění údajů
--------------------------------------------------------- */
$sql = "
    UPDATE event 
    SET 
        id_worker = ?, 
        workername = ?, 
        birth_number = ?, 
        hpp = ?, 
        tax_event = ?
    WHERE id_event = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "isssii",
    $id_worker,
    $worker['workername'],
    $worker['birth_number'],
    $worker['hpp'],
    $tax_event,
    $id_event
);

if (!$stmt->execute()) {
    die("Chyba při aktualizaci: " . $stmt->error);
}

$stmt->close();
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
$comment_job = isset($_POST['comment_job']) ? $_POST['comment_job'] : null;
$comment_leader = isset($_POST['comment_leader']) ? $_POST['comment_leader'] : null;

//$leadername = isset($_POST['leadername']) ? $_POST['leadername'] : null;

//$id_worker = $_POST['id_worker'] ?? "";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free_event2</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <H1>Volné zakázky</H1>
    <p>Zakázky pracovníka: <strong>
            <?php echo $_SESSION['workername']; ?>
        </strong></p>

    <header>
        <div class="container">
            <nav>
                <ul>
                    <a href="free_event.php">
                        <li>Zpět</li>
                    </a>
                </ul>
            </nav>


        </div>
    </header>
    <hr />

    <?php
    /* ---------------------------------------------------------
    Výpis výsledků – jednoduchá verze s $result
    --------------------------------------------------------- */

    $result = $conn->query("SELECT * FROM event WHERE id_event = $id_event");

    if ($result && $result->num_rows > 0) {

        echo "<h2>Zakázka byla úspěšně přidělena.</h2>";
        echo "<h3>Uložené údaje:</h3>";

        echo "<table border='1' cellpadding='6' cellspacing='0'>";
        echo "<thead>
            <tr>
                <th>ID zakázky</th>
                <th>ID práce</th>
                <th>Název práce</th>
                <th>Hodinová sazba</th>
                <th>Předpokládaný začátek</th>
                <th>Počet hodin</th>
                <th>Výdělek</th>
                <th>ID pracovníka</th>
                <th>Název pracovníka</th>
                <th>Rodné číslo</th>
                <th>HPP</th>
                <th>Daň</th>
            </tr>
          </thead>";

        echo "<tbody>";

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
            echo "</tr>";

            // komentáře
            echo "<td colspan='12' style='padding: 6px 4px; background: #f7f7f7; text-align: left;'>";
            echo "<strong>Comment-job:</strong> " . htmlspecialchars($row['comment_job']) . "<br>";
            echo "<strong>Comment-leader:</strong> " . htmlspecialchars($row['comment_leader']);
            echo "</td></tr>";
        }

        echo "</tbody>";
        echo "</table>";

    } else {
        echo "Žádná data k zobrazení.";
    }



    ?>
</body>

</html>