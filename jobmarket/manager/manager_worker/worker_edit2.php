<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit 2</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <?php
    // Připojení k databázi
    include('../../connection.php'); // Vložení souboru s připojením k databázi
    session_start();
    // Správné nastavení SQL dotazu
    $sql = "SELECT * FROM login1 WHERE id_worker = ?";
    $stmt = $conn->prepare($sql);
    // Přiřazení ID z SESSION do dotazu
    $uzivatel_id = $_SESSION['uzivatel_id'];

    $stmt->bind_param("i", $uzivatel_id);
    $stmt->execute();
    $result = $stmt->get_result();
    // Generování tabulky 
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Username</th><th>Password</th><th>Active</th><th>Rodné číslo</th><th>Hpp</th><th>Comment</th></tr>";
        echo "<td></td>";
        ?>
        <br><br>
        <?php
        while ($row = $result->fetch_assoc()) {
            $workername = $row['workername'];
            $password = $row['password'];
            $active = $row['active'];
            $birth_number = $row['birth_number'];
            $hpp = $row['hpp'];
            $comment = $row['comment'];

            echo "<tr>";

            echo "<td>" . htmlspecialchars($row['id_worker']) . "</td>";
            echo "<td>" . htmlspecialchars($row['workername']) . "</td>";
            echo "<td>" . htmlspecialchars($row['password']) . "</td>";
            echo "<td>" . htmlspecialchars($row['active']) . "</td>";
            echo "<td>" . htmlspecialchars($row['birth_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['hpp']) . "</td>";
            echo "<td>" . htmlspecialchars($row['comment']) . "</td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nebyl vybrán žádný řádek.";
    }







    ?>
    <br><br>
    <form action="worker_edit3.php" method="POST">

        <input type="hidden" name="id_worker" value="<?php echo $uzivatel_id; ?>">


        <label for="workername">Username:</label>
        <input type="text" name="workername" value="<?php echo htmlspecialchars($workername); ?>">



        <label for="password">Password:</label>
        <input type="text" name="password" value="<?php echo htmlspecialchars($password); ?>">



        <label for="active">Active:</label>
        <select type="number" id="active" name="active">
            <option value="1" <?php if ($active == 1)
                echo "selected"; ?>>Ano</option>
            <option value="0" <?php if ($active == 0)
                echo "selected"; ?>>Ne</option>
        </select>

        <label for="birth_number">Password:</label>
        <input type="text" name="birth_number" value="<?php echo htmlspecialchars($birth_number); ?>">

        <label for="hpp">Hpp:</label>
        <select type="number" id="hpp" name="hpp">
            <option value="1" <?php if ($hpp == 1)
                echo "selected"; ?>>Ano</option>
            <option value="0" <?php if ($hpp == 0)
                echo "selected"; ?>>Ne</option>
        </select>

        <br><br>
        <label for="comment">Comment:</label>
        <input type="text" name="comment" size="100" value="<?php echo htmlspecialchars($comment); ?>">

        <br><br>

        <input type="submit" value="Uložit změny">


    </form>








</body>

</html>