<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>insert</title>
    <link rel="stylesheet" href="../../CSS/style.css">

</head>

<body>


    <header>
        <?php
        include('manager3.php');
        // nacteni zakladniho menu
        ?>
    </header>

    <h1>Vložení nového managera</h1>
    <br><br>
    <form action="manager_insert.php" method="GET">
        <input name="username" type="text" placeholder="Nový manažer">
        <input name="password" type="text" placeholder="Nové heslo">
        <label for="active">Aktivní:</label>
        <select id="active" name="active">
            <option value="1">Ano</option>
            <option value="0">Ne</option>
        </select>
        <br><br>
        <input name="comment" type="text" size="100" placeholder="Nový komentář">
        <br><br>

        <!-- Tady se zadávají hodnoty noveho managera -->
        <br><br>

        <input type="submit" value="odeslat">
        <br><br>
        <?php
        include('../../connection.php');
        // pripojeni databaze
        


        if (!empty($_GET)) {



            $username = $_GET["username"];
            $password = $_GET["password"];
            $active = $_GET["active"];
            $comment = $_GET["comment"];


            $sql = "INSERT INTO `login3` (`id`, `username`, `password`,`active`,`comment`) VALUES (NULL, '$username', '$password', '$active', '$comment')";


            if ($conn->query($sql) === TRUE) {

                echo "</br>";
                echo "Nový manager byl úspěšně vložen.</br>";
                echo "Rekapitulace:</br></br>";
                echo "Uživatelské jméno: $username</br>";
                echo "Heslo: $password</br>";
                echo "Aktivní: $active</br>";
                echo "Comment: $comment</br>";

                // vlozeni noveho zaznamu
            }
        }
        ?>
</body>

</html>