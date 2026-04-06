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
        include('job3.php');
        // nacteni zakladniho menu
        ?>
    </header>

    <h1>Vložení nové práce</h1>
    <br><br>
    <form action="job_insert.php" method="GET">
        <label for="name_job">Název práce:</label>
        <input name="name_job" type="text" placeholder="Název práce">
        <label for="price_hour">Hodinová sazba:</label>
        <input name="price_hour" type="number" placeholder="Hodinová sazba">
        <br><br>
        <label for="comment_job">Comment:</label>
        <input name="comment_job" type="text" size="100" placeholder=" Komentář">
        <br><br>

        <!-- Tady se zadávají hodnoty noveho managera -->
        <br><br>

        <input type="submit" value="odeslat">
        <br><br>
        <?php
        include('../../connection.php');
        // pripojeni databaze
        


        if (!empty($_GET)) {



            $name_job = $_GET["name_job"];
            $price_hour = $_GET["price_hour"];
            $comment_job = $_GET["comment_job"];


            $sql = "INSERT INTO `job` (`id_job`, `name_job`, `price_hour`,`comment_job`) VALUES (NULL, '$name_job', '$price_hour','$comment_job')";


            if ($conn->query($sql) === TRUE) {

                echo "</br>";
                echo "Nový práce byla úspěšně vložena.</br>";
                echo "Rekapitulace:</br></br>";
                echo "Název práce: $name_job</br>";
                echo "Hodinová sazba: $price_hour</br>";
                echo "Comment: $comment_job</br>";

                // vlozeni noveho zaznamu
            }
        }
        ?>
</body>

</html>