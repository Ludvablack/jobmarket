<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit 2</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>

    <?php
    include('../../connection.php');

    // Načteme vždy jen řádek s id = 1
    $sql = "SELECT * FROM setup WHERE id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $hours = $row['hours'];
        $limitsalary = $row['limitsalary'];
        $tax = $row['tax'];
    } else {
        echo "Chyba: řádek s ID 1 nebyl nalezen.";
        exit;
    }
    ?>

    <h2>Aktuální hodnoty</h2>

    <table border="1">
        <tr>
            <th>Limit ročních hodin</th>
            <th>Limit měsíčního platu</th>
            <th>Daň</th>
        </tr>
        <tr>
            <td>
                <?php echo htmlspecialchars($hours); ?>
            </td>
            <td>
                <?php echo htmlspecialchars($limitsalary); ?>
            </td>
            <td>
                <?php echo htmlspecialchars($tax); ?>
            </td>
        </tr>
    </table>

    <br><br>

    <h2>Upravit hodnoty</h2>

    <form action="setup_edit3.php" method="POST">

        <label for="hours">Limit hodin za rok:</label>
        <input type="number" name="hours" value="<?php echo htmlspecialchars($hours); ?>">

        <label for="limitsalary">Limit měsíčního platu:</label>
        <input type="number" name="limitsalary" value="<?php echo htmlspecialchars($limitsalary); ?>">

        <label for="tax">Daň:</label>
        <input type="number" name="tax" value="<?php echo htmlspecialchars($tax); ?>">

        <input type="hidden" name="id" value="1">

        <br><br>
        <input type="submit" value="Uložit změny">
    </form>

</body>

</html>