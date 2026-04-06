<?php
session_start();

$id_worker = $_SESSION['id_worker'] ?? null;
$workername = $_SESSION['workername'] ?? null;

include('../../connection.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$mesice = [
    1 => "leden",
    2 => "únor",
    3 => "březen",
    4 => "duben",
    5 => "květen",
    6 => "červen",
    7 => "červenec",
    8 => "srpen",
    9 => "září",
    10 => "říjen",
    11 => "listopad",
    12 => "prosinec"
];

$aktualni_mesic = $mesice[(int) date("n")];






/* ---------------------------------------------------------
   1) NAČTENÍ HPP (daňová povinnost)
--------------------------------------------------------- */
$sql = "SELECT id_worker, hpp FROM login1 WHERE id_worker = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_worker);
$stmt->execute();
$res = $stmt->get_result();
$worker = $res->fetch_assoc();
$id_worker = (int) $worker['id_worker'];
$hpp = (int) $worker['hpp'];

/* ---------------------------------------------------------
   2) NAČTENÍ LIMITŮ ZE SETUP
--------------------------------------------------------- */
$sql = "SELECT limithours, limitsalary, tax FROM setup LIMIT 1";
$res = $conn->query($sql);

if (!$res || $res->num_rows === 0) {
    die("Chyba: nelze načíst data ze setup.");
}

$setup = $res->fetch_assoc();

$limitHours = (int) $setup['limithours'];
$limitSalary = (int) $setup['limitsalary'];
$taxPercent = (int) $setup['tax'];
if ($hpp == 1) {
    $tax_var = $taxPercent;
} else {
    $tax_var = 0;
}

/* ---------------------------------------------------------
   3) ROČNÍ SOUČET HODIN
--------------------------------------------------------- */
$sql_hours = "
    SELECT SUM(hours) AS hoursyear
    FROM event
    WHERE id_worker = ?
      AND YEAR(begin_event) = YEAR(NOW())
     AND control <> 'Storno'

";
$stmt = $conn->prepare($sql_hours);
$stmt->bind_param("i", $id_worker);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

$hoursyear = (float) ($row['hoursyear'] ?? 0);

/* ---------------------------------------------------------
   4) MĚSÍČNÍ SOUČET PLATU
--------------------------------------------------------- */
$sql_salary = "
    SELECT SUM(salary) AS salarymonth
    FROM event
    WHERE id_worker = ?
      AND YEAR(begin_event) = YEAR(NOW())
      AND MONTH(begin_event) = MONTH(NOW())
      AND control <> 'Storno'

";
$stmt = $conn->prepare($sql_salary);
$stmt->bind_param("i", $id_worker);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

$salarymonth = (float) ($row['salarymonth'] ?? 0);

/* ---------------------------------------------------------
   5) VÝPOČET PLATU S DANÍ
--------------------------------------------------------- */
if ($hpp == 1) {
    $salarymonth_taxed = $salarymonth * (1 - $taxPercent / 100);
} else {
    $salarymonth_taxed = $salarymonth;
}

/* ---------------------------------------------------------
   6) POLE STARÝCH ZAČÁTKŮ
--------------------------------------------------------- */
$old_start = [];
$sql = "SELECT begin_event 
        FROM event 
        WHERE id_worker = ? 
          AND control <> 'Storno'
          AND (
                begin_event IS NOT NULL
                AND begin_event > '1970-01-01'
              )";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_worker);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $old_start[] = $row['begin_event'];
}

/* ---------------------------------------------------------
   7) POLE STARÝCH KONCŮ
--------------------------------------------------------- */
$old_stop = [];
$sql = "SELECT begin_event, hours 
        FROM event 
        WHERE id_worker = ?
          AND control <> 'Storno'
          AND (
                begin_event IS NOT NULL
                AND begin_event > '1970-01-01'
              )";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_worker);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $start = $row['begin_event'];
    $hours = (float) $row['hours'];
    $stop = date('Y-m-d H:i:s', strtotime("$start + $hours hours"));
    $old_stop[] = $stop;
}

/* ---------------------------------------------------------
   8) ZÁKLADNÍ SQL PRO VOLNÉ ZAKÁZKY
--------------------------------------------------------- */
$sql_free = "SELECT * FROM event 
             WHERE (id_worker IS NULL OR id_worker = 0)
             AND (
                    start_event IS NULL
                    OR start_event < '1970-01-01'
                 )
             AND (
                    stop_event IS NULL
                    OR stop_event < '1970-01-01'
                 )
             AND begin_event > NOW()
             AND control = 'Ready'";


/* ---------------------------------------------------------
   9) DYNAMICKÉ FILTRY
--------------------------------------------------------- */
$id = $_POST['id_event'] ?? null;
$id_job = $_POST['id_job'] ?? null;
$name_job = $_POST['name_job'] ?? null;
$price_hour = $_POST['price_hour'] ?? null;
$begin_event = $_POST['begin_event'] ?? null;
$hours = $_POST['hours'] ?? null;
$salary = $_POST['salary'] ?? null;

$params = [];
$types = "";

if (!empty($id)) {
    $sql_free .= " AND id_event = ?";
    $params[] = $id;
    $types .= "i";
}
if (!empty($id_job)) {
    $sql_free .= " AND id_job = ?";
    $params[] = $id_job;
    $types .= "i";
}
if (!empty($name_job)) {
    $sql_free .= " AND name_job LIKE ?";
    $params[] = "%$name_job%";
    $types .= "s";
}
if (!empty($price_hour)) {
    $sql_free .= " AND price_hour >= ?";
    $params[] = $price_hour;
    $types .= "i";
}
if (!empty($begin_event)) {
    $sql_free .= " AND begin_event > ?";
    $params[] = $begin_event;
    $types .= "s";
}
if (!empty($hours)) {
    $sql_free .= " AND hours <= ?";
    $params[] = $hours;
    $types .= "i";
}
if (!empty($salary)) {
    $sql_free .= " AND salary >= ?";
    $params[] = $salary;
    $types .= "i";
}

$sql_free .= " ORDER BY id_event ASC";

$stmt = $conn->prepare($sql_free);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Přehled volných zakázek</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>

    <h1>Volné zakázky</h1>
    <p>Přihlášený pracovník: <strong>
            <?= htmlspecialchars($workername) ?> </strong></p>

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

    <?php

    //TABULKA S INFORMACEMI O PRACOVNÍKOVI 
    
    echo "<table border='1' cellpadding='6' cellspacing='0'>
            <thead>
                <tr>
                    <th>Pracovník</th>
                    <th>Roční počet hodin</th>
                    <th>Měsíční plat včetně daně ({$aktualni_mesic})</th>
                    <th>Měsíční plat bez daně ({$aktualni_mesic})</th>
                    <th>roční limit hodin</th>
                    <th>Měsíční limit platu</th>
                    <th>Daň (%)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>" . htmlspecialchars($workername) . "</td>
                    <td>" . htmlspecialchars($hoursyear) . "</td>
                    <td>" . number_format($salarymonth, 2, ',', ' ') . " Kč</td>
                    <td>" . number_format($salarymonth_taxed, 2, ',', ' ') . " Kč</td>
                    <td>" . htmlspecialchars($limitHours) . "</td>
                    <td>" . number_format($limitSalary, 0, ',', ' ') . " Kč</td>
                    <td>" . htmlspecialchars($tax_var) . " %</td>
                </tr>
            </tbody>
          </table>";
    echo "<p>Měsíční plat i roční počet hodin je uváděn včetně neukončených zakázek ze strany zaměstnance.</p>";

    ?>




    <h1>Třídit zakázky podle:</h1>

    <form action="free_event.php" method="POST">
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
    /* ---------------------------------------------------------
       10) VÝPIS VOLNÝCH ZAKÁZEK
    --------------------------------------------------------- */
    if ($result->num_rows > 0) {

        echo '<form action="free_event.php" method="POST">';
        echo "<br><button type='submit'>Vybrat zakázku</button><br><br>";

        echo '<table border="1">
            <thead>
                <tr>
                    <th>Vyber</th>
                    <th>ID zakázky</th>
                    <th>ID práce</th>
                    <th>Název práce</th>
                    <th>Hodinová sazba</th>
                    <th>Předpokládaný začátek</th>
                    <th>Počet hodin</th>
                    <th>Výdělek s daní</th>
                </tr>
            </thead>
            <tbody>';


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
            echo "</tr>";

            echo "<tr>";
            echo "<td colspan='15' style='padding: 6px 4px; background: #f7f7f7; text-align: left;'>";

            echo "<details>";
            echo "<summary style='cursor:pointer; font-weight:bold;'>Zobrazit komentáře</summary>";

            echo "<div style='padding: 8px 0 0 0;'>";
            echo "<p><strong>Comment-job:</strong> " . htmlspecialchars($row['comment_job']) . "</p>";
            echo "<p><strong>Comment-leader:</strong> " . htmlspecialchars($row['comment_leader']) . "</p>";

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

    /* ---------------------------------------------------------
       11) ZPRACOVÁNÍ VÝBĚRU ZAKÁZKY
    --------------------------------------------------------- */
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uzivatel_id'])) {

        $_SESSION['uzivatel_id'] = (int) $_POST['uzivatel_id'];

        echo "Byla vybrána zakázka ID: " . $_SESSION['uzivatel_id'];
        echo '<meta http-equiv="refresh" content="2;url=free_event2.php">';
    }

    $conn->close();
    ?>

</body>

</html>