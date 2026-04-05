<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>insert</title>
    <link rel="stylesheet" href="../../CSS/style.css">

</head>
<header>
    <?php
    include('worker3.php');
    // nacteni zakladniho menu
    ?>
</header>

<body>
    <h1>Vložení nového pracovníka</h1>
    <br><br>
    <form action="worker_insert.php" method="GET">
        <input name="workername" type="text" placeholder="Nový pracovník">
        <input name="password" type="text" placeholder="Nové heslo">
        <label for="active">Aktivní:</label>
        <select id="active" name="active">
            <option value="1">Ano</option>
            <option value="0">Ne</option>
        </select>
        <label for="birth_number">Rodné číslo:</label>
        <input type="text" id="birth_number" name="birth_number" placeholder="Zadejte rodné číslo">
        <label for="hpp">Hpp:</label>
        <select type="number" id="hpp" name="hpp">

            <option value="1">Ano</option>
            <option value="0">Ne</option>
        </select>
        <br><br>
        <input name="comment" type="text" size="100" placeholder="Nový komentář">
        <br><br>

        <!-- Tady se zadávají hodnoty noveho workera -->
        <br><br>

        <input type="submit" value="odeslat">
        <br><br>
        <?php
        include('../../connection.php');
        // pripojeni databaze
        


        if (!empty($_GET)) {



            $workername = $_GET["workername"];
            $password = $_GET["password"];
            $active = $_GET["active"];
            $birth_number = $_GET["birth_number"];
            $hpp = $_GET["hpp"];
            $comment = $_GET["comment"];


            $sql = "INSERT INTO `login1` (`id_worker`, `workername`, `password`,`active`, `birth_number`,`hpp`,`comment`) VALUES (NULL, '$workername', '$password', '$active','$birth_number','$hpp','$comment')";


            if ($conn->query($sql) === TRUE) {

                echo "</br>";
                echo "Nový worker byl úspěšně vložen.</br>";
                echo "Rekapitulace:</br></br>";
                echo "Uživatelské jméno: $workername</br>";
                echo "Heslo: $password</br>";
                echo "Aktivní: $active</br>";
                echo "Rodné číslo: $birth_number</br>";
                echo "Hpp: $hpp</br>";
                echo "Comment: $comment</br>";

                // vlozeni noveho zaznamu
            }
        }
        ?>
</body>

</html>