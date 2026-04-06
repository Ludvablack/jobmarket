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
        include('worker3.php');
        // nacteni zakladniho menu
        ?>
    </header>
    <?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        ?>
        <h1>Vybrat podle:</h1>
        <form action="worker_erase.php" method="POST">
            <label for="id_worker">ID:</label>
            <input type="number" id="id_worker" name="id_worker" placeholder="Zadejte ID">

            <label for="workername">Username:</label>
            <input type="text" id="workername" name="workername" placeholder="Zadejte uživatelské jméno">

            <label for="password">Password:</label>
            <input type="text" id="password" name="password" placeholder="Zadejte heslo">

            <label for="active">Active:</label>
            <select type="number" id="active" name="active">
                <option value="">Vše</option>
                <option value="1">Ano</option>
                <option value="0">Ne</option>
            </select>
            <label for="birth_number">Rodné číslo:</label>
            <input type="text" id="birth_number" name="birth_number" placeholder="Zadejte rodné číslo">
            <label for="hpp">Hpp:</label>
            <select type="number" id="hpp" name="hpp">
                <option value="">Vše</option>
                <option value="1">Ano</option>
                <option value="0">Ne</option>
            </select>




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
    $id = isset($_POST['id_worker']) ? $_POST['id_worker'] : null;
    $workername = isset($_POST['workername']) ? $_POST['workername'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $active = isset($_POST['active']) ? $_POST['active'] : null;
    $birth_number = isset($_POST['birth_number']) ? $_POST['birth_number'] : null;
    $hpp = isset($_POST['hpp']) ? $_POST['hpp'] : null;
    $comment = isset($_POST['comment']) ? $_POST['comment'] : null;

    // Základní SQL dotaz
    $sql = "SELECT * FROM login1 where 1=1 ";
    $params = [];
    $types = "";




    if (!empty($id)) {
        $sql .= " AND id_worker = ?";
        $params[] = $id;
        $types .= "i"; // Typ integer
    }

    if (!empty($workername)) {
        $sql .= " AND workername LIKE ?";
        $params[] = "%" . $workername . "%";
        $types .= "s"; // Typ string
    }

    if (!empty($password)) {
        $sql .= " AND password LIKE ?";
        $params[] = "%" . $password . "%";
        $types .= "s"; // Typ string
    }

    if ($active !== null && $active !== "") {
        $sql .= " AND active = ?";
        $params[] = $active;
        $types .= "i"; // Typ integer
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

    if (!empty($comment)) {
        $sql .= " AND comment LIKE ?";
        $params[] = "%" . $comment . "%";
        $types .= "s"; // Typ string
    }
    $sql .= " ORDER BY id_worker ASC ";

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
            echo "<tr><th>Vybrat</th><th>ID</th><th>Username</th><th>Password</th><th>Active</th><th>Rodné číslo</th><th>Hpp</th><th>Comment</th></tr>";
            echo "<td></td>";
            ?>
            <form action="worker_erase.php" method="POST">
                <button><input type="submit" value="Smazat vybraný řádek"></button>
                <br><br>





                <?php
                while ($row = $result->fetch_assoc()) {

                    echo "<tr>";
                    echo "<td><input type='radio' name='uzivatel_id' value='" . $row['id_worker'] . "'></td>";
                    echo "<td>" . htmlspecialchars($row['id_worker']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['workername']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['password']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['active']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['birth_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['hpp']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['comment']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </form>
            <?php
            echo "</table>";
        }
    } else {
        echo "Uživatel se vybírá.";
        echo "<br><br>";
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['uzivatel_id'])) {
        $vybrany_id = intval($_POST['uzivatel_id']); // Získání ID vybraného uživatele
    

        session_start(); // Spustíme session
    
        if (isset($_POST['uzivatel_id'])) {
            $_SESSION['uzivatel_id'] = (int) $_POST['uzivatel_id']; // Převedeme hodnotu na číslo a uložíme do session
            echo "Byl vybrán řádek: " . $_SESSION['uzivatel_id'];
            ?>

            <meta http-equiv="refresh" content="2;url=worker_erase2.php">
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