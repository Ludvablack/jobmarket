<?php
session_start(); // Spustíme session

?>


<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Výběr zakázky</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <h1>Menu nové zakázky</h1>

    <header>
        <div class="container">
            <nav>
                <ul>
                    <a href="../menu2.php">
                        <li>Zpět</li>
                    </a>
                </ul>
            </nav>
        </div>
    </header>

    <hr>




    <h1>Vyber práci:</h1>
    <form method="POST">
        <input type="hidden" name="form_type" value="filter">

        <label for="id_job">ID:</label>
        <input type="number" id="id_job" name="id_job">

        <label for="name_job">Název práce:</label>
        <input type="text" id="name_job" name="name_job">

        <label for="price_hour">Hodinová sazba:</label>
        <input type="number" id="price_hour" name="price_hour">

        <input type="submit" value="Filtrovat">
    </form>



    <hr>

    <?php
    // Připojení k databázi
    include('../../connection.php'); // Vložení souboru s připojením k databázi
    
    // Kontrola připojení
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Načtení filtrů
    $id = $_POST['id_job'] ?? null;
    $name_job = $_POST['name_job'] ?? null;
    $price_hour = $_POST['price_hour'] ?? null;

    // SQL dotaz
    $sql = "SELECT * FROM job WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($id)) {
        $sql .= " AND id_job = ?";
        $params[] = $id;
        $types .= "i";
    }

    if (!empty($name_job)) {
        $sql .= " AND name_job LIKE ?";
        $params[] = "%$name_job%";
        $types .= "s";
    }

    if (!empty($price_hour)) {
        $sql .= " AND price_hour = ?";
        $params[] = $price_hour;
        $types .= "i";
    }

    $sql .= " ORDER BY id_job ASC";





    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // Výpis tabulky
    if ($result->num_rows > 0) {
        echo "<form method='POST'>";
        echo "<input type='hidden' name='form_type' value='select'>";
        echo "<input type='submit' value='Vybrat zakázku'>";

        echo "<br><br>";

        echo "<table border='1'>";
        echo "<tr><th>Vybrat</th><th>ID</th><th>Název</th><th>Sazba</th><th>Komentář</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><input type='radio' name='uzivatel_id' value='{$row['id_job']}' required></td>";
            echo "<td>{$row['id_job']}</td>";
            echo "<td>{$row['name_job']}</td>";
            echo "<td>{$row['price_hour']}</td>";
            echo "<td>{$row['comment_job']}</td>";
            echo "</tr>";
        }

        echo "</table><br>";

        echo "</form>";
    } else {
        echo "Žádné výsledky.";
    } {

        echo "<br><br>";
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['uzivatel_id'])) {
        $vybrany_id = intval($_POST['uzivatel_id']); // Získání ID vybraného uživatele
    
        if ($_POST['form_type'] === 'select') {
            if (isset($_POST['uzivatel_id'])) {
                $_SESSION['uzivatel_id'] = (int) $_POST['uzivatel_id'];
                echo "Byl vybrán řádek: " . $_SESSION['uzivatel_id'];
                echo '<meta http-equiv="refresh" content="2;url=insert2.php">';
            }
        }

    } else {
        echo "Nebyl vybrán žádný řádek: " . $conn->error;
    }
    $conn->close();
    ?>

</body>

</html>