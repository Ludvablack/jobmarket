<?php
session_start();



include('../../connection.php');
$id_job = (int) $_SESSION['uzivatel_id'];

// 2) Načti data jobu
$sql = "SELECT id_job, name_job, price_hour, comment_job FROM job WHERE id_job = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_job);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Job s ID $id_job neexistuje.";
    exit;
}

$job = $result->fetch_assoc();

// 3) Zpracování formuláře
$zprava = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $begin_event = $_POST["begin_event"] ?? null;
    $hours = (float) ($_POST["hours"] ?? 0);

    $comment_leader = $_POST["comment_leader"] ?? '';
    // pevně daná hodnota
    $control = "Ready";
    $salary = $job['price_hour'] * $hours;

    $sqlInsert = "
        INSERT INTO event 
            (id_job, name_job, price_hour, begin_event, hours, salary,control, comment_job, comment_leader)
        VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?,?)
    ";

    $ins = $conn->prepare($sqlInsert);
    $ins->bind_param(
        "isdsdssss",
        $job['id_job'],
        $job['name_job'],
        $job['price_hour'],
        $begin_event,
        $hours,
        $salary,
        $control,               // ← tady se vloží "Ready"
        $job['comment_job'],
        $comment_leader
    );
    if ($ins->execute()) {
        $id_event = $ins->insert_id;

        // uložíme ID vložené práce do session
        $_SESSION['vlozena_prace'] = [
            'id_event' => $id_event,
            'id_job' => $job['id_job'],
            'name_job' => $job['name_job'],
            'price_hour' => $job['price_hour'],
            'begin_event' => $begin_event,
            'hours' => $hours,
            'salary' => $salary,
            'comment_job' => $job['comment_job'],
            'comment_leader' => $comment_leader
        ];
        unset($_SESSION['uzivatel_id']);
        session_write_close();

        // přesměrování na stránku s výsledkem
        header("Location: insert3.php");
        exit;
    }


}

$conn->close();
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Nová zakázka</title>
    <link rel="stylesheet" href="../../CSS/style.css">





</head>

<body>

    <h1>Menu nové zakázky</h1>

    <header>
        <div class="container">
            <nav>
                <ul>
                    <a href="insert.php">
                        <li>Zpět</li>
                    </a>
                </ul>
            </nav>
        </div>
    </header>

    <hr>

    <h1>Vložení nové zakázky</h1>

    <div class="box">

        <?php if ($zprava): ?>
            <div class="zprava">
                <?php echo $zprava; ?>
            </div>
        <?php endif; ?>

        <h2>Vybraná práce</h2>
        <p>
            ID job:
            <?php echo $job['id_job']; ?><br>
            Název:
            <?php echo $job['name_job']; ?><br>
            Cena/hod:
            <?php echo $job['price_hour']; ?><br>
            Komentář:
            <?php echo $job['comment_job']; ?><br>
        </p>

        <h2>Upřesnit zakázku</h2>
        <form action="" method="POST" class="form-block">

            <div class="form-row">
                <label for="begin_event">Začátek práce:</label>
                <input name="begin_event" type="datetime-local" required>
            </div>

            <div class="form-row">
                <label for="hours">Počet hodin:</label>
                <input name="hours" type="number" step="1" min="0" required>
            </div>

            <div class="form-row">
                <label for="comment_leader">Comment vedoucí:</label>
                <input name="comment_leader" type="text" size="60">
            </div>

            <input type="submit" value="Odeslat" class="submit-btn">

        </form>


    </div>

</body>

</html>