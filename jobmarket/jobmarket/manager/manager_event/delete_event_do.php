<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view</title>
    <link rel="stylesheet" href="../../CSS/style.css">

</head>

<body>

    <H1>Mazání zakázek</H1>
    <!-- toto je menu správce-->
    <header>
        <div class="container">

            <nav>
                <ul>
                    <a href="event3.php">
                        <li>Zpět</li>
                    </a>





                </ul>
            </nav>
        </div>
    </header>



    <?php
    // delete_event_do.php
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['confirm'] === 'yes') {

        require_once '../../connection.php'; // připojení k DB
    




        $sql = "TRUNCATE TABLE event";

        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>Tabulka Zakázky byla úspěšně vyprázdněna.</p>";
        } else {
            echo "<p style='color:red;'>Chyba: " . $conn->error . "</p>";
        }



    } else {
        echo "<p style='color:red;'>Operace nebyla potvrzena.</p>";
    }
    ?>
</body>

</html>