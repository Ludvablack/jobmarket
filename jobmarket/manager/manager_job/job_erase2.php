<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>erase 2</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <?php
    // Připojení k databázi
    include('../../connection.php'); // Vložení souboru s připojením k databázi
    // Kontrola připojení
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    session_start(); // Spustíme session
    // Správné nastavení SQL dotazu
    $sql = "SELECT * FROM job WHERE id_job = ?";
    $stmt = $conn->prepare($sql);
    // Přiřazení ID z SESSION do dotazu
    $uzivatel_id = $_SESSION['uzivatel_id'];
    $stmt->bind_param("i", $uzivatel_id);
    $stmt->execute();
    $result = $stmt->get_result();
    // Generování tabulky 
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Název práce</th><th>Hodinová sazba</th><th>Comment</th></tr>";
        echo "<td></td>";
        ?>
        <br><br>
        <?php
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
        echo "Nebyl vybrán žádný řádek.";
    }
    // SQL dotaz pro smazání vybraného uživatele
    $sql = "DELETE FROM job WHERE id_job = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $uzivatel_id);
        $stmt->execute();
        echo "<table border='1'>";
        echo "</table>";
    } else {
        echo "Řádek nebyl nalezen.";
    }
    if ($stmt->affected_rows > 0) {
        echo "Uživatel s ID byl úspěšně smazán.";
    } else {
        echo "Nepodařilo se smazat uživatele. Zkontrolujte, zda ID existuje.";
    }
    $stmt->close();
    $conn->close();
    // Odpojení databáze
    ?>
    <meta http-equiv="refresh" content="2;url=job_erase.php">
    </meta>
</body>

</html>