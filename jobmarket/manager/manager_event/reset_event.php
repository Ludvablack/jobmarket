<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>

    <h1>Zakázky</h1>

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

    <h2>Vyprázdnění seznamu zakázek</h2>

    <p style="color:red; font-weight:bold;">
        Opravdu chcete kompletně vyprázdnit tabulku Zakázky?
        Tato akce je nevratná.
    </p>

    <form method="post" action="delete_event_do.php"
        onsubmit="return confirm('Opravdu chcete smazat všechny záznamy z tabulky zakázek? Tato akce je nevratná.');">
        <input type="hidden" name="confirm" value="yes">
        <button type="submit" style="background:red; color:white; padding:10px;">
            Ano, opravdu vyprázdnit
        </button>
    </form>

</body>

</html>