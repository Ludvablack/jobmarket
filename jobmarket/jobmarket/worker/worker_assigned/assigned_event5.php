<?php
session_start();

$workername = $_SESSION['workername'] ?? null;
$uzivatel_id = $_SESSION['uzivatel_id'] ?? null;
$start_event = $_SESSION['stop_event'] ?? null;
$comment_event = $_SESSION['comment_event'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free_event2</title>
    <link rel="stylesheet" href="../../CSS/style.css">

    <script>
        function delayStop(form) {
            document.getElementById("loadingStop").style.display = "block";
            setTimeout(() => form.submit(), 1500);
            return false;
        }

        function delayNote(form) {
            document.getElementById("loadingNote").style.display = "block";
            setTimeout(() => form.submit(), 1500);
            return false;
        }
    </script>
</head>

<body>
    <h1>Vybrané zakázky</h1>
    <p>Zakázky pracovníka: <strong>
            <?php echo $_SESSION['workername']; ?>
        </strong></p>

    <header>
        <div class="container">
            <nav>
                <ul>
                    <a href="assigned_event.php">
                        <li>Zpět</li>
                    </a>
                </ul>
            </nav>
        </div>
    </header>
    <hr />

    <?php
    include('../../connection.php');

    $sql = "SELECT * FROM event WHERE id_event = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uzivatel_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID zakázky</th><th>ID práce</th><th>Název práce</th><th>Hodinová sazba</th><th>Předpokládaný začátek</th><th>Počet hodin</th><th>Výdělek s daní</th><th>Start</th></tr>";
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
            echo "<td>" . htmlspecialchars($row['start_event']) . "</td>";

            echo "</tr>";

            echo "<tr>";
            echo "<td colspan='15' style='padding: 6px 4px; background: #f7f7f7; text-align: left;'>";

            echo "<details>";
            echo "<summary style='cursor:pointer; font-weight:bold;'>Zobrazit komentáře</summary>";

            echo "<div style='padding: 8px 0 0 0;'>";
            echo "<p><strong>Comment-job:</strong> " . htmlspecialchars($row['comment_job']) . "</p>";
            echo "<p><strong>Comment-leader:</strong> " . htmlspecialchars($row['comment_leader']) . "</p>";
            echo "<p><strong>Comment-work:</strong> " . htmlspecialchars($row['comment_event']) . "</p>";
            echo "</div>";

            echo "</details>";

            echo "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "Nebyl vybrán žádný řádek.";
    }
    ?>
    <div style="display:flex; gap:100px; align-items:flex-start; margin-top:20px;">

        <!-- Formulář STOP -->
        <br><br>
        <form action="assigned_event6.php" method="POST" onsubmit="return delayStart(this);" style="margin:0;">
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
            box-shadow:0 0 10px rgba(0,0,0,0.3);
        ">
                ✔ Ukončit zakázku
            </button>
        </form>

        <!-- Formulář POZNÁMKA -->
        <br><br>
        <form action="assigned_event4.php" method="POST" onsubmit="return delayNote(this);" style="margin:0;">
            <input type="hidden" name="id_event" value="<?php echo $uzivatel_id; ?>">


            <button type="submit" style="
            background:#1976d2;
            color:white;
            padding:15px 30px;
            font-size:22px;
            border:none;
            border-radius:10px;
            cursor:pointer;
            font-weight:bold;
            box-shadow:0 0 10px rgba(0,0,0,0.3);
        ">
                ✔ Vlož poznámku
            </button>
            <br><br>
            <label for="comment_event"><strong>Poznámka k zakázce:</strong></label><br>
            <textarea name="comment_event" id="comment_event" rows="3" cols="50"
                placeholder="Sem napište poznámku..."></textarea>



        </form>

    </div>





    <!-- Hlášky -->
    <div id="loadingStop" style="display:none; font-weight:bold; color:#d32f2f; margin-top:20px;">
        Probíhá ukončení zakázky…
    </div>


    <div id="loadingNote" style="display:none; font-weight:bold; color:#1976d2; margin-top:20px;">
        Ukládám poznámku…
    </div>


</body>

</html>