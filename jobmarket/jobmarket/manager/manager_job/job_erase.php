<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>erase 1</title>
    <link rel="stylesheet" href="../../CSS/style.css">

</head>

<body>


    <header>
        <?php
        include('job3.php');
        // nacteni zakladniho menu
        ?>
    </header>
    <?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        ?>
        <h1>Vybrat podle:</h1>
        <form action="job_erase.php" method="POST">
            <label for="id_job">ID práce:</label>
            <input type="number" id="id_job" name="id_job" placeholder="Zadejte ID práce">

            <label for="name_job">Název práce:</label>
            <input type="text" id="name_job" name="name_job" placeholder="Zadejte název práce">



            <input type="submit" value="Vyber">
            <br><br>


        </form>
        <?php
    }
    // Připojení k databázi
    include('../../connection.php'); // Vložení souboru s připojením k databázi
    
    // Kontrola připojení
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Získání hodnot z formuláře
    $id = isset($_POST['id_job']) ? $_POST['id_job'] : null;
    $name_job = isset($_POST['name_job']) ? $_POST['name_job'] : null;
    $price_hour = isset($_POST['price_hour']) ? $_POST['price_hour'] : null;
    $comment_job = isset($_POST['comment_job']) ? $_POST['comment_job'] : null;

    // Základní SQL dotaz
    $sql = "SELECT * FROM job where 1=1 ";
    $params = [];
    $types = "";




    if (!empty($id)) {
        $sql .= " AND id_job = ?";
        $params[] = $id;
        $types .= "i"; // Typ integer
    }

    if (!empty($name_job)) {
        $sql .= " AND name_job LIKE ?";
        $params[] = "%" . $name_job . "%";
        $types .= "s"; // Typ string
    }




    if (!empty($comment_job)) {
        $sql .= " AND comment_job LIKE ?";
        $params[] = "%" . $comment_job . "%";
        $types .= "s"; // Typ string
    }
    $sql .= " ORDER BY id_job ASC ";


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
            echo "<table border='1'>";
            echo "<tr><th>Vybrat</th><th>ID</th><th>Název práce</th><th>Hodinová sazba</th><th>Comment</th></tr>";
            echo "<td></td>";
            ?>
            <form action="job_erase.php" method="POST">
                <button><input type="submit" value="Smazat vybraný řádek"></button>
                <br><br>





                <?php
                while ($row = $result->fetch_assoc()) {

                    echo "<tr>";
                    echo "<td><input type='radio' name='uzivatel_id' value='" . $row['id_job'] . "'></td>";
                    echo "<td>" . htmlspecialchars($row['id_job']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name_job']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['price_hour']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['comment_job']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </form>
            <?php
            echo "</table>";
        }
    } else {
        echo "Práce se vybírá.";
        echo "<br><br>";
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['uzivatel_id'])) {
        $vybrany_id = intval($_POST['uzivatel_id']); // Získání ID vybrané práce
    

        session_start(); // Spustíme session
    
        if (isset($_POST['uzivatel_id'])) {
            $_SESSION['uzivatel_id'] = (int) $_POST['uzivatel_id']; // Převedeme hodnotu na číslo a uložíme do session
            echo "Byl vybrán řádek: " . $_SESSION['uzivatel_id'];
            ?>

            <meta http-equiv="refresh" content="2;url=job_erase2.php">
            </meta>
            <?php

        } else {
            echo "Nebylo nic vybráno.";
        }

    } else {
        echo "Nebyl vybrán žádný řádek: " . $conn->error;
    }

    $conn->close();
    // Odpojení databáze
    
    ?>

</body>

</html>