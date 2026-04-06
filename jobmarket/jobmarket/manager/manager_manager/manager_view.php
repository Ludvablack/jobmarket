<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view</title>
    <link rel="stylesheet" href="../../CSS/style.css">

</head>

<body>


    <header>
        <?php
        include('manager3.php');
        // nacteni zakladniho menu
        ?>
    </header>
    <h1>Vybrat podle:</h1>
    <form action="manager_view.php" method="POST">
        <label for="id">ID:</label>
        <input type="number" id="id" name="id" placeholder="Zadejte ID">

        <label for="username">Username:</label>
        <input type="varchar" id="username" name="username" placeholder="Zadejte uživatelské jméno">

        <label for="password">Password:</label>
        <input type="varchar" id="password" name="password" placeholder="Zadejte heslo">

        <label for="active">Active:</label>
        <select type="number" id="active" name="active">
            <option value="">Vše</option>
            <option value="1">Ano</option>
            <option value="0">Ne</option>
        </select>
        <br><br>
        <input type="submit" value="vyhledat">
        <br><br>
    </form>
    <?php
    // Připojení k databázi
    include('../../connection.php'); // Vložení souboru s připojením k databázi
    
    // Kontrola připojení
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Získání hodnot z formuláře
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $active = isset($_POST['active']) ? $_POST['active'] : null;
    $comment = isset($_POST['comment']) ? $_POST['comment'] : null;
    // Základní SQL dotaz
    $sql = "SELECT * FROM login3 where 1=1 ";
    $params = [];
    $types = "";

    // Dynamické přidávání podmínek
    if (!empty($id)) {
        $sql .= " AND id = ?";
        $params[] = $id;
        $types .= "i"; // Typ integer
    }

    if (!empty($username)) {
        $sql .= " AND username LIKE ?";
        $params[] = "%" . $username . "%";
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

    if (!empty($comment)) {
        $sql .= " AND comment LIKE ?";
        $params[] = "%" . $comment . "%";
        $types .= "s"; // Typ sring
    }



    $sql .= " ORDER BY id ASC ";






    // Připravení dotazu
    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    // Spuštění dotazu
//Hodnoty se připojují pomocí připraveného dotazu (Prepared Statements), což chrání před SQL injection útoky.
    $stmt->execute();
    $result = $stmt->get_result();

    // Výpis výsledků
// HTML tabulka
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tbody><thread><tr><th>ID</th><th>Jméno</th><th>Heslo </th><th>Active</th> <th>Comment</th></tr></thread></tbody>";
        echo "<td></td>";

        // Zobrazení dat
        while ($row = $result->fetch_assoc()) {

            echo "<tr>";

            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['password']) . "</td>";
            echo "<td>" . htmlspecialchars($row['active']) . "</td>";
            echo "<td>" . htmlspecialchars($row['comment']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Žádná data k zobrazení.";
    }

    // Uzavření spojení
    $conn->close();
    ?>






</body>

</html>