<?php
include('../../connection.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vždy pracujeme jen s řádkem id = 1
$sql = "SELECT * FROM setup WHERE id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit 1</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>

    <H1>Nastavení limitů hodin, platu a daně</H1>
    <!-- toto je menu správce-->
    <header>
        <div class="container">

            <nav>
                <ul>
                    <a href="../menu3.php">
                        <li>Zpět</li>
                    </a>




                </ul>
            </nav>
        </div>
    </header>

    <hr />





    <table border="1">
        <tr>
            <th>Limit ročních hodin</th>
            <th>Limit měsíčního platu</th>
            <th>Daň</th>
        </tr>
        <?php if ($row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['limithours']); ?></td>
                <td><?php echo htmlspecialchars($row['limitsalary']); ?></td>
                <td><?php echo htmlspecialchars($row['tax']); ?></td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="3">Řádek s ID 1 nebyl nalezen.</td>
            </tr>
        <?php endif; ?>
    </table>

    <form action="setup_edit2.php" method="POST">
        <input type="hidden" name="id" value="1">
        <br><br>
        <input type="submit" value="Upravit řádek">
    </form>

</body>

</html>