<?php
session_start();

if (!isset($_SESSION['vlozena_prace'])) {
    echo "Chyba: žádná vložená práce.";
    exit;
}

$data = $_SESSION['vlozena_prace'];

// po načtení můžeš session klidně smazat
unset($_SESSION['vlozena_prace']);
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Výsledek vložení</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>

    <h1>Menu nové zakázky</h1>

    <header>
        <div class="container">
            <nav>
                <ul>
                    <a href="../menu2.php">
                        <li>Zpět</li>
                    </a>
                </ul>
            </nav>
        </div>
    </header>

    <hr>

    <h1>Práce byla úspěšně vložena</h1>

    <div class="box">
        <p><strong>Číslo zakázky:</strong> <?= $data['id_event'] ?></p>
        <p><strong>Číslo práce:</strong> <?= $data['id_job'] ?></p>
        <p><strong>Název práce:</strong> <?= $data['name_job'] ?></p>
        <p><strong>Hodinová sazba:</strong> <?= $data['price_hour'] ?></p>
        <p><strong>Začátek práce:</strong> <?= $data['begin_event'] ?></p>
        <p><strong>Počet hodin:</strong> <?= $data['hours'] ?></p>
        <p><strong>Plat včetně daně:</strong> <?= $data['salary'] ?></p>
        <p><strong>Comment práce:</strong> <?= $data['comment_job'] ?></p>
        <p><strong>Comment vedoucí:</strong> <?= $data['comment_leader'] ?></p>

        <br>

    </div>

</body>

</html>