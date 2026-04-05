<!DOCTYPE html>
<html lang="cs">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobmarket</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>


<body>

    <!-- hlavní soubor databaze, volá se kaskadovy styl, menu a obrázek-->

    <header>
        <?php
        include('mainmenu.php');
        ?>
    </header>

    <div class="modul">
        <div class="modul1"><img src="./img/zdeni.png" width="500" height="300" alt="toto je obrazek"></div>

        <?php
        // připojení k databázi (mysqli)
        
        include('connection.php');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        $conn = new mysqli($server, $uzivatel, $heslo, $databaze);

        // kontrola připojení
        if ($conn->connect_error) {
            die("Chyba připojení: " . $conn->connect_error);

        }
        // Automatická deaktivace propadlých zakázek (Empty)
        // Deaktivace se provede pouze první den v měsíci
        if (date('j') == 1) {
            //  UPDATE: změnit control na Empty u všech vybraných řádků
            $sql_update = "
         UPDATE event
         SET control = 'Empty'
        WHERE control = 'Ready'
        AND (
        YEAR(begin_event) < YEAR(NOW())
        OR (
             YEAR(begin_event) = YEAR(NOW())
             AND MONTH(begin_event) < MONTH(NOW())
           )
           )
        AND (
        start_event IS NULL
        OR start_event < '1970-01-01'
            )
             ";

            $result_update = $conn->query($sql_update);

            if (!$result_update) {
                echo 'SQL UPDATE error: ' . $conn->error . '<br>';
            } else {
                echo ' Upravuje se ' . $conn->affected_rows . ' zakázek na Empty ' . '<br>';
            }

        }
        // Konec deaktivace starých zakázek
        ?>

</body>

</html>
