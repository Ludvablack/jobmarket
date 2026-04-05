<?php
session_start();
$workername = $_SESSION['workername'] ?? null;
$uzivatel_id = $_SESSION['uzivatel_id'] ?? null;
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
    include('../../connection.php');
    // Kontrola přihlášení a výběru zakázky
    if (!isset($_SESSION['id_worker'], $_SESSION['uzivatel_id'])) {
        die("Chyba: chybí id_worker nebo vybraná zakázka (uzivatel_id).");
    }
    $id_worker = (int) $_SESSION['id_worker'];
    $uzivatel_id = (int) $_SESSION['uzivatel_id'];

    // 1) Načtení limitů ze setup
    $sql = "SELECT limithours, limitsalary, tax FROM setup LIMIT 1";
    $res = $conn->query($sql);

    if (!$res || $res->num_rows === 0) {
        die("Chyba: nelze načíst data ze setup.");
    }

    $setup = $res->fetch_assoc();
    $limitHours = (int) $setup['limithours'];
    $limitSalary = (int) $setup['limitsalary'];
    $taxPercent = (int) $setup['tax'];

    // 2) Roční součet hodin (bez nové zakázky)
    $sql = "
    SELECT SUM(hours) AS hoursyear
    FROM event
    WHERE id_worker = ?
      AND YEAR(begin_event) = YEAR(NOW())
      AND control <> 'Storno'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_worker);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $hoursyear = (float) ($row['hoursyear'] ?? 0);

    // 3) Měsíční součet platu (bez nové zakázky)
    $sql = "
    SELECT SUM(salary) AS salarymonth
    FROM event
    WHERE id_worker = ?
      AND YEAR(begin_event) = YEAR(NOW())
      AND MONTH(begin_event) = MONTH(NOW())
      AND control <> 'Storno'
";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_worker);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $salarymonth = (float) ($row['salarymonth'] ?? 0);

    // 4) Načtení všech starých začátků a konců směn
    $old_start = [];
    $old_stop = [];

    $sql = "
    SELECT begin_event, hours
    FROM event
    WHERE id_worker = ?
      AND begin_event IS NOT NULL
      AND control <> 'Storno'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_worker);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($r = $res->fetch_assoc()) {
        $start = $r['begin_event'];
        $hours = (float) $r['hours'];
        $stop = date('Y-m-d H:i:s', strtotime("$start + $hours hours"));

        $old_start[] = $start;
        $old_stop[] = $stop;
    }

    // 5) Načtení nové zakázky (začátek + hodiny)
    $sql = "SELECT * FROM event WHERE id_event = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uzivatel_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row) {
        die("Chyba: vybraná zakázka neexistuje.");
    }

    echo "<h2>Vybraná zakázka</h2>";

    echo "<table border='1' cellpadding='6' cellspacing='0'>
        <tr>
            <th>ID zakázky</th>
            <th>ID práce</th>
            <th>Název práce</th>
            <th>Hodinová sazba</th>
            <th>Předpokládaný začátek</th>
            <th>Počet hodin</th>
            <th>Výdělek s daní</th>
        </tr>
        <tr>
            <td>" . htmlspecialchars($row['id_event']) . "</td>
            <td>" . htmlspecialchars($row['id_job']) . "</td>
            <td>" . htmlspecialchars($row['name_job']) . "</td>
            <td>" . htmlspecialchars($row['price_hour']) . "</td>
            <td>" . htmlspecialchars($row['begin_event']) . "</td>
            <td>" . htmlspecialchars($row['hours']) . "</td>
            <td>" . htmlspecialchars($row['salary']) . "</td>
        </tr>
      </table>";

    echo "<br>";
    echo "<tr>";
    echo "<p><strong>Comment-job:</strong> " . htmlspecialchars($row['comment_job']) . "</p>";
    echo "<p><strong>Comment-leader:</strong> " . htmlspecialchars($row['comment_leader']) . "</p>";
    echo "</tr>";


    $new_start = $row['begin_event'];
    $new_hours = (float) $row['hours'];
    $new_salary = (float) $row['salary'];

    $new_stop = date('Y-m-d H:i:s', strtotime("$new_start + $new_hours hours"));

    // 6) Případně můžeš kontrolovat limity včetně nové zakázky:
    $hoursyear_new = $hoursyear + $new_hours;
    $salarymonth_new = $salarymonth + $new_salary;

    // 7) Kontroly podmínek
    $chyby = [];

    // 1) Roční limit hodin (podle tvé podmínky)
    if ($hoursyear_new > $limitHours) {
        $chyby[] = "Nelze přiřadit zakázku: překročen roční limit hodin ($hoursyear_new / $limitHours).";
        echo "<p>Včetně nehotových zakázek</p>";

    }

    // 2) Měsíční limit platu
    if ($salarymonth_new > $limitSalary) {
        $chyby[] = "Nelze přiřadit zakázku: překročen měsíční limit platu ("
            . number_format($salarymonth_new, 0, ',', ' ') . " Kč / "
            . number_format($limitSalary, 0, ',', ' ') . " Kč).";
        echo "<p>Včetně nehotových zakázek</p>";

    }

    // 3) Překryv směn
    foreach ($old_start as $index => $start) {
        $stop = $old_stop[$index];

        if ($start < $new_stop && $stop > $new_start) {
            $chyby[] = "Nelze přiřadit zakázku: překryv směn s existující zakázkou ($start – $stop).";
            echo "<p>Včetně nehotových zakázek</p>";
            break;

        }
    }

    // 8) Vyhodnocení
    if (!empty($chyby)) {
        echo "<h3 style='color:red;'>Zakázku nelze přiřadit:</h3><ul>";
        foreach ($chyby as $ch) {
            echo "<li>" . htmlspecialchars($ch) . "</li>";
        }
        echo "</ul>";
    }
    // 9) Přiřazení zakázky (vše v pořádku)
    
    $conn->close();
    ?>
    <br><br>
    <?php if (empty($chyby)): ?>
        <form action="free_event3.php" method="POST" style="margin-top:20px;">
            <input type="hidden" name="id_event" value="<?php echo $uzivatel_id; ?>">

            <button type="submit" style="
        background:#d32f2f;
        color:white;
        padding:15px 30px;
        font-size:22px;
        border:none;
        border-radius:10px;
        cursor:pointer;
        font-weight:bold;
        box-shadow:0 0 10px rgba(0,0,0,0.3); ">
                ✔ Potvrdit přiřazení zakázky
            </button>
        </form>
    <?php endif; ?>

</body>

</html>