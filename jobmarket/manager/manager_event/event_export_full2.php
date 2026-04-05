<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu_export</title>
    <link rel="stylesheet" href="../../CSS/style.css">

</head>

<body>




    <H1>Export .CSV</H1>
    <!-- Plný export CSV-->
    < <hr />
    <?php
    // event_export_full2.php
    
    // Připojení k databázi
    include('../../connection.php');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Získání hodnot z POST
    $rok = isset($_POST['rok']) ? $_POST['rok'] : null;
    $mesic = isset($_POST['mesic']) ? $_POST['mesic'] : null;

    // Základ SQL
    $sql = "SELECT * FROM event WHERE 1=1 ";
    $params = [];
    $types = "";

    // Filtrování podle roku/měsíce
    if (!empty($rok) && !empty($mesic)) {

        if ($mesic === "rok") {
            $sql .= " AND begin_event LIKE ?";
            $params[] = $rok . "%";
            $types .= "s";
        } else {
            $mesic = str_pad($mesic, 2, "0", STR_PAD_LEFT);
            $sql .= " AND begin_event LIKE ?";
            $params[] = $rok . "-" . $mesic . "%";
            $types .= "s";
        }
    }

    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Nastavení hlaviček pro CSV stažení
    $filename = "export_" . date("Y-m-d_H-i-s") . ".csv";

    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=$filename");

    // Otevření výstupu
    $output = fopen("php://output", "w");

    // UTF‑8 BOM pro Excel
    fwrite($output, "\xEF\xBB\xBF");

    // Hlavička CSV
    fputcsv($output, [
        "ID zakázky",
        "ID práce",
        "Název práce",
        "Hodinová sazba",
        "Předpokládaný začátek",
        "Počet hodin",
        "Výdělek",
        "ID pracovníka",
        "Název pracovníka",
        "Rodné číslo",
        "HPP",
        "Daň",
        "Start",
        "Stop",
        "Kontrola",
        ";",
        "Comment-job",
        ";",
        "Comment-leader",
        ";",
        "Comment-worker",
        ";",
        "Comment-control",
        ";",
        "Kontroloval:"
    ], ";");

    // Data
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['id_event'],
            $row['id_job'],
            $row['name_job'],
            $row['price_hour'],
            $row['begin_event'],
            $row['hours'],
            $row['salary'],
            $row['id_worker'],
            $row['workername'],
            $row['birth_number'],
            $row['hpp'],
            $row['tax_event'],
            $row['start_event'],
            $row['stop_event'],
            $row['control'],
            ";",
            $row['comment_job'],
            ";",
            $row['comment_leader'],
            ";",
            $row['comment_event'],
            ";",
            $row['comment_control'],
            ";",
            $row['leadername']
        ], ";");
    }

    fclose($output);
    $conn->close();
    exit;
    ?>




</body>

</html>